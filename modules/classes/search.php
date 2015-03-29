<?php

use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Propel;
use Propel\Runtime\Formatter\ObjectFormatter;
use Propel\Runtime\ActiveQuery\Criteria;
use Map\UserTableMap;
use Map\ActivityUserAssociationTableMap;

/**
 * Implements the search algorithms for Onboard
 * @author Jimmy
 *
 */
class OnboardSearch {
	
	
	/**
	 * Search for activities among the friends of $curUserId
	 * @param unknown $curUserId
	 * @param unknown $query
	 * @return Ambigous <\Propel\Runtime\Collection\Collection, multitype:, \Propel\Runtime\ActiveRecord\ActiveRecordInterface>
	 */
	public static function SearchForActivities($curUserId, $query){
		// divide query into words
		$rawWords = explode(' ', $query);
		$words = array();
		
		// format query
		foreach ($rawWords as $word){
			$word = trim($word);
			if (strlen($word) > 2){
				$words[] = strtoupper($word);
			}
		}
		$wordCount = count($words);
		if ($wordCount == 0){
			// if no valid word, then return empty result set
			return new Collection();
		}
		
		/* first sweep, find all matching results */
		
		// sql request
		$conn = Propel::getReadConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select 
				distinct a.id, a.name, aua.alias
			from
				activity a
			left join
				activity_user_assoc aua
			on
				aua.activity_id = a.id
			where
				(
EOT;
		for ($i=0; $i<$wordCount; $i++){
			if ($i>0){
				$sql = $sql." or ";
			}
			$sql = $sql."upper(aua.alias) like upper(:alias{$i}) or upper(a.name) like upper(:name{$i}) ";
		}
		$sql = $sql.") order by a.id";
		
		// prepare for parameters
		$parameters = array();
		for ($i=0; $i<$wordCount; $i++){
			$parameters["alias{$i}"] = "%{$words[$i]}%";
			$parameters["name{$i}"] = "%{$words[$i]}%";
		}
		
		// execute query
		$stmt = $conn->prepare($sql);
		$stmt->execute($parameters);
		$rawResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		/* second weep, classify results based on how closely they match */
		
		$rankedResults = array();
		
		foreach ($rawResults as $resultRow){
			$id = $resultRow['id'];
			
			// create new search result object
			$activityResult = new ActivitySearchResult();
			$activityResult->SetActivityId($id);
			
			// determine which field to use for name
			$nameCount = self::CountMatchedWords($words, $resultRow['name']);
			$aliasCount = self::CountMatchedWords($words, $resultRow['alias']);
			
			if ($nameCount >= $aliasCount){
				// use name
				$activityResult->SetActivityName($resultRow['name']);
				$activityResult->SetNumMatched($nameCount);
			} else {
				// use alias
				$activityResult->SetActivityName($resultRow['alias']);
				$activityResult->SetNumMatched($aliasCount);
			}
			
			$len = count($rankedResults);
			$insertPosition = -1;	// not inserted = -1
			
			// replace existing activity result?
			for ($i=0; $i<$len; $i++){
				if ($insertPosition == -1 && $rankedResults[$i]->GetNumMatched() < $activityResult->GetNumMatched()){
					// note insert position
					$insertPosition = $i;
				} elseif ($insertPosition > -1 && $rankedResults[$i]->GetActivityId() == $activityResult->GetActivityId()){
					array_splice($rankedResults, $i, 1);
					break;
				} elseif ($insertPosition == -1 && $rankedResults[$i]->GetActivityId() == $activityResult->GetActivityId()){
					$insertPosition = -2;
				}
			}
			
			// insert result
			if ($insertPosition > -1){
				array_splice($rankedResults, $insertPosition, 0, $activityResult);
			} elseif ($insertPosition == -1) {
				$rankedResults[] = $activityResult;
			}
		}
				
		return $rankedResults;
	}
	
	
	/**
	 * Search for active friends of $curUserId and return them as a Collection of User objects
	 * @param unknown $curUserId
	 * @param unknown $query
	 * @return Ambigous <\Propel\Runtime\Collection\Collection, multitype:, \Propel\Runtime\ActiveRecord\ActiveRecordInterface>
	 */
	public static function SearchForFriends($curUserId, $query){
		// format $query
		$query = strtoupper("%{$query}%");
		
		// sql request
		$conn = Propel::getReadConnection(UserTableMap::DATABASE_NAME);
		$sql = <<<EOT
			select distinct user.*
			from user
			where
				id in
					(select user.id
						from user_community_assoc uca
						join user on uca.user_id_right = user.id
						where
							user.status = :actstatus1
							and uca.user_id_left = :myid1
					union
					select user.id
						from user_community_assoc uca
						join user on uca.user_id_left = user.id
						where
							user.status = :actstatus2
							and uca.user_id_right = :myid2)
				and upper(display_name) like upper(:name)
			order by display_name asc
EOT;
		$stmt = $conn->prepare($sql);
		$stmt->execute(
			array(
				'actstatus1'	=> User::ACTIVE_STATUS,
				'myid1'			=> $curUserId,
				'actstatus2'	=> User::ACTIVE_STATUS,
				'myid2'			=> $curUserId,
				'name'			=> $query
			));
		
		$formatter = new ObjectFormatter();
		$formatter->setClass('\User'); //full qualified class name
		$resultArr = $formatter->format($conn->getDataFetcher($stmt));
		
		return $resultArr;
	}
	
	
	/**
	 * Return the number of matched words in a given string
	 * @param array $words
	 * @param unknown $string
	 * @return int Number of matched words
	 */
	private static function CountMatchedWords(array $words, $string){
		$total = 0;
		foreach ($words as $word){
			if (preg_match("/\\b{$word}/i", $string)){
				$total++;
			}
		}
		
		return $total;
	}
}



/**
 * Object to describe the activity search result as returned by OnboardSearch
 * @author Jimmy
 *
 */
class ActivitySearchResult{
	// activityId
	private $activityId = 0;
	public function GetActivityId(){
		return $this->activityId;
	}
	public function SetActivityId($id){
		$this->activityId = $id;
	}
	
	// name or alias
	private $activityName = "";
	public function GetActivityName(){
		return $this->activityName;
	}
	public function SetActivityName($name){
		$this->activityName = $name;
	}
	
	// description
	private $activityDescr = "";
	public function GetActivityDescription(){
		return $this->activityDescr;
	}
	public function SetActivityDescription($descr){
		$this->activityDescr = $descr;
	}
	
	// number of matches
	private $numMatches = 0;
	public function GetNumMatched(){
		return $this->numMatches;
	}
	public function SetNumMatched($numMatches){
		$this->numMatches = $numMatches;
	}
}