<?php

namespace Base;

use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \Exception;
use \PDO;
use Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByFbId($order = Criteria::ASC) Order by the fb_id column
 * @method     ChildUserQuery orderByGoogleId($order = Criteria::ASC) Order by the google_id column
 * @method     ChildUserQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildUserQuery orderByDateJoined($order = Criteria::ASC) Order by the date_joined column
 * @method     ChildUserQuery orderByDisplayName($order = Criteria::ASC) Order by the display_name column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByFbId() Group by the fb_id column
 * @method     ChildUserQuery groupByGoogleId() Group by the google_id column
 * @method     ChildUserQuery groupByStatus() Group by the status column
 * @method     ChildUserQuery groupByDateJoined() Group by the date_joined column
 * @method     ChildUserQuery groupByDisplayName() Group by the display_name column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByPhone() Group by the phone column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinActivityList($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityList relation
 * @method     ChildUserQuery rightJoinActivityList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityList relation
 * @method     ChildUserQuery innerJoinActivityList($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityList relation
 *
 * @method     ChildUserQuery leftJoinActivityUserAssociation($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityUserAssociation relation
 * @method     ChildUserQuery rightJoinActivityUserAssociation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityUserAssociation relation
 * @method     ChildUserQuery innerJoinActivityUserAssociation($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityUserAssociation relation
 *
 * @method     ChildUserQuery leftJoinUserCommunityAssociationRelatedByUserIdLeft($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdLeft relation
 * @method     ChildUserQuery rightJoinUserCommunityAssociationRelatedByUserIdLeft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdLeft relation
 * @method     ChildUserQuery innerJoinUserCommunityAssociationRelatedByUserIdLeft($relationAlias = null) Adds a INNER JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdLeft relation
 *
 * @method     ChildUserQuery leftJoinUserCommunityAssociationRelatedByUserIdRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdRight relation
 * @method     ChildUserQuery rightJoinUserCommunityAssociationRelatedByUserIdRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdRight relation
 * @method     ChildUserQuery innerJoinUserCommunityAssociationRelatedByUserIdRight($relationAlias = null) Adds a INNER JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdRight relation
 *
 * @method     \ActivityListQuery|\ActivityUserAssociationQuery|\UserCommunityAssociationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByFbId(string $fb_id) Return the first ChildUser filtered by the fb_id column
 * @method     ChildUser findOneByGoogleId(string $google_id) Return the first ChildUser filtered by the google_id column
 * @method     ChildUser findOneByStatus(int $status) Return the first ChildUser filtered by the status column
 * @method     ChildUser findOneByDateJoined(string $date_joined) Return the first ChildUser filtered by the date_joined column
 * @method     ChildUser findOneByDisplayName(string $display_name) Return the first ChildUser filtered by the display_name column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByPhone(string $phone) Return the first ChildUser filtered by the phone column
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByFbId(string $fb_id) Return ChildUser objects filtered by the fb_id column
 * @method     ChildUser[]|ObjectCollection findByGoogleId(string $google_id) Return ChildUser objects filtered by the google_id column
 * @method     ChildUser[]|ObjectCollection findByStatus(int $status) Return ChildUser objects filtered by the status column
 * @method     ChildUser[]|ObjectCollection findByDateJoined(string $date_joined) Return ChildUser objects filtered by the date_joined column
 * @method     ChildUser[]|ObjectCollection findByDisplayName(string $display_name) Return ChildUser objects filtered by the display_name column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByPhone(string $phone) Return ChildUser objects filtered by the phone column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'onboard', $modelName = '\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, fb_id, google_id, status, date_joined, display_name, email, phone FROM user WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the fb_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFbId('fooValue');   // WHERE fb_id = 'fooValue'
     * $query->filterByFbId('%fooValue%'); // WHERE fb_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fbId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByFbId($fbId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fbId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fbId)) {
                $fbId = str_replace('*', '%', $fbId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_FB_ID, $fbId, $comparison);
    }

    /**
     * Filter the query on the google_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGoogleId('fooValue');   // WHERE google_id = 'fooValue'
     * $query->filterByGoogleId('%fooValue%'); // WHERE google_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $googleId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByGoogleId($googleId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($googleId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $googleId)) {
                $googleId = str_replace('*', '%', $googleId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_GOOGLE_ID, $googleId, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(UserTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(UserTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the date_joined column
     *
     * Example usage:
     * <code>
     * $query->filterByDateJoined('2011-03-14'); // WHERE date_joined = '2011-03-14'
     * $query->filterByDateJoined('now'); // WHERE date_joined = '2011-03-14'
     * $query->filterByDateJoined(array('max' => 'yesterday')); // WHERE date_joined > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateJoined The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByDateJoined($dateJoined = null, $comparison = null)
    {
        if (is_array($dateJoined)) {
            $useMinMax = false;
            if (isset($dateJoined['min'])) {
                $this->addUsingAlias(UserTableMap::COL_DATE_JOINED, $dateJoined['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateJoined['max'])) {
                $this->addUsingAlias(UserTableMap::COL_DATE_JOINED, $dateJoined['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_DATE_JOINED, $dateJoined, $comparison);
    }

    /**
     * Filter the query on the display_name column
     *
     * Example usage:
     * <code>
     * $query->filterByDisplayName('fooValue');   // WHERE display_name = 'fooValue'
     * $query->filterByDisplayName('%fooValue%'); // WHERE display_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $displayName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByDisplayName($displayName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($displayName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $displayName)) {
                $displayName = str_replace('*', '%', $displayName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_DISPLAY_NAME, $displayName, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query by a related \ActivityList object
     *
     * @param \ActivityList|ObjectCollection $activityList  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByActivityList($activityList, $comparison = null)
    {
        if ($activityList instanceof \ActivityList) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $activityList->getUserId(), $comparison);
        } elseif ($activityList instanceof ObjectCollection) {
            return $this
                ->useActivityListQuery()
                ->filterByPrimaryKeys($activityList->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByActivityList() only accepts arguments of type \ActivityList or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ActivityList relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinActivityList($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ActivityList');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ActivityList');
        }

        return $this;
    }

    /**
     * Use the ActivityList relation ActivityList object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ActivityListQuery A secondary query class using the current class as primary query
     */
    public function useActivityListQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActivityList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ActivityList', '\ActivityListQuery');
    }

    /**
     * Filter the query by a related \ActivityUserAssociation object
     *
     * @param \ActivityUserAssociation|ObjectCollection $activityUserAssociation  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByActivityUserAssociation($activityUserAssociation, $comparison = null)
    {
        if ($activityUserAssociation instanceof \ActivityUserAssociation) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $activityUserAssociation->getUserId(), $comparison);
        } elseif ($activityUserAssociation instanceof ObjectCollection) {
            return $this
                ->useActivityUserAssociationQuery()
                ->filterByPrimaryKeys($activityUserAssociation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByActivityUserAssociation() only accepts arguments of type \ActivityUserAssociation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ActivityUserAssociation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinActivityUserAssociation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ActivityUserAssociation');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ActivityUserAssociation');
        }

        return $this;
    }

    /**
     * Use the ActivityUserAssociation relation ActivityUserAssociation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ActivityUserAssociationQuery A secondary query class using the current class as primary query
     */
    public function useActivityUserAssociationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActivityUserAssociation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ActivityUserAssociation', '\ActivityUserAssociationQuery');
    }

    /**
     * Filter the query by a related \UserCommunityAssociation object
     *
     * @param \UserCommunityAssociation|ObjectCollection $userCommunityAssociation  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserCommunityAssociationRelatedByUserIdLeft($userCommunityAssociation, $comparison = null)
    {
        if ($userCommunityAssociation instanceof \UserCommunityAssociation) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userCommunityAssociation->getUserIdLeft(), $comparison);
        } elseif ($userCommunityAssociation instanceof ObjectCollection) {
            return $this
                ->useUserCommunityAssociationRelatedByUserIdLeftQuery()
                ->filterByPrimaryKeys($userCommunityAssociation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserCommunityAssociationRelatedByUserIdLeft() only accepts arguments of type \UserCommunityAssociation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdLeft relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserCommunityAssociationRelatedByUserIdLeft($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserCommunityAssociationRelatedByUserIdLeft');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserCommunityAssociationRelatedByUserIdLeft');
        }

        return $this;
    }

    /**
     * Use the UserCommunityAssociationRelatedByUserIdLeft relation UserCommunityAssociation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserCommunityAssociationQuery A secondary query class using the current class as primary query
     */
    public function useUserCommunityAssociationRelatedByUserIdLeftQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserCommunityAssociationRelatedByUserIdLeft($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserCommunityAssociationRelatedByUserIdLeft', '\UserCommunityAssociationQuery');
    }

    /**
     * Filter the query by a related \UserCommunityAssociation object
     *
     * @param \UserCommunityAssociation|ObjectCollection $userCommunityAssociation  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserCommunityAssociationRelatedByUserIdRight($userCommunityAssociation, $comparison = null)
    {
        if ($userCommunityAssociation instanceof \UserCommunityAssociation) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userCommunityAssociation->getUserIdRight(), $comparison);
        } elseif ($userCommunityAssociation instanceof ObjectCollection) {
            return $this
                ->useUserCommunityAssociationRelatedByUserIdRightQuery()
                ->filterByPrimaryKeys($userCommunityAssociation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserCommunityAssociationRelatedByUserIdRight() only accepts arguments of type \UserCommunityAssociation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserCommunityAssociationRelatedByUserIdRight relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserCommunityAssociationRelatedByUserIdRight($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserCommunityAssociationRelatedByUserIdRight');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserCommunityAssociationRelatedByUserIdRight');
        }

        return $this;
    }

    /**
     * Use the UserCommunityAssociationRelatedByUserIdRight relation UserCommunityAssociation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserCommunityAssociationQuery A secondary query class using the current class as primary query
     */
    public function useUserCommunityAssociationRelatedByUserIdRightQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserCommunityAssociationRelatedByUserIdRight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserCommunityAssociationRelatedByUserIdRight', '\UserCommunityAssociationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
