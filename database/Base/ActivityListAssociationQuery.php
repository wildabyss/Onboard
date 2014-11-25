<?php

namespace Base;

use \ActivityListAssociation as ChildActivityListAssociation;
use \ActivityListAssociationQuery as ChildActivityListAssociationQuery;
use \Exception;
use \PDO;
use Map\ActivityListAssociationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'activity_list_assoc' table.
 *
 *
 *
 * @method     ChildActivityListAssociationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActivityListAssociationQuery orderByActivityId($order = Criteria::ASC) Order by the activity_id column
 * @method     ChildActivityListAssociationQuery orderByListId($order = Criteria::ASC) Order by the list_id column
 * @method     ChildActivityListAssociationQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildActivityListAssociationQuery orderByDateAdded($order = Criteria::ASC) Order by the date_added column
 * @method     ChildActivityListAssociationQuery orderByAlias($order = Criteria::ASC) Order by the alias column
 * @method     ChildActivityListAssociationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildActivityListAssociationQuery orderByIsOwner($order = Criteria::ASC) Order by the is_owner column
 *
 * @method     ChildActivityListAssociationQuery groupById() Group by the id column
 * @method     ChildActivityListAssociationQuery groupByActivityId() Group by the activity_id column
 * @method     ChildActivityListAssociationQuery groupByListId() Group by the list_id column
 * @method     ChildActivityListAssociationQuery groupByStatus() Group by the status column
 * @method     ChildActivityListAssociationQuery groupByDateAdded() Group by the date_added column
 * @method     ChildActivityListAssociationQuery groupByAlias() Group by the alias column
 * @method     ChildActivityListAssociationQuery groupByDescription() Group by the description column
 * @method     ChildActivityListAssociationQuery groupByIsOwner() Group by the is_owner column
 *
 * @method     ChildActivityListAssociationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActivityListAssociationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActivityListAssociationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActivityListAssociationQuery leftJoinActivity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Activity relation
 * @method     ChildActivityListAssociationQuery rightJoinActivity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Activity relation
 * @method     ChildActivityListAssociationQuery innerJoinActivity($relationAlias = null) Adds a INNER JOIN clause to the query using the Activity relation
 *
 * @method     ChildActivityListAssociationQuery leftJoinActivityList($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityList relation
 * @method     ChildActivityListAssociationQuery rightJoinActivityList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityList relation
 * @method     ChildActivityListAssociationQuery innerJoinActivityList($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityList relation
 *
 * @method     \ActivityQuery|\ActivityListQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActivityListAssociation findOne(ConnectionInterface $con = null) Return the first ChildActivityListAssociation matching the query
 * @method     ChildActivityListAssociation findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActivityListAssociation matching the query, or a new ChildActivityListAssociation object populated from the query conditions when no match is found
 *
 * @method     ChildActivityListAssociation findOneById(string $id) Return the first ChildActivityListAssociation filtered by the id column
 * @method     ChildActivityListAssociation findOneByActivityId(int $activity_id) Return the first ChildActivityListAssociation filtered by the activity_id column
 * @method     ChildActivityListAssociation findOneByListId(int $list_id) Return the first ChildActivityListAssociation filtered by the list_id column
 * @method     ChildActivityListAssociation findOneByStatus(int $status) Return the first ChildActivityListAssociation filtered by the status column
 * @method     ChildActivityListAssociation findOneByDateAdded(string $date_added) Return the first ChildActivityListAssociation filtered by the date_added column
 * @method     ChildActivityListAssociation findOneByAlias(string $alias) Return the first ChildActivityListAssociation filtered by the alias column
 * @method     ChildActivityListAssociation findOneByDescription(string $description) Return the first ChildActivityListAssociation filtered by the description column
 * @method     ChildActivityListAssociation findOneByIsOwner(int $is_owner) Return the first ChildActivityListAssociation filtered by the is_owner column
 *
 * @method     ChildActivityListAssociation[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActivityListAssociation objects based on current ModelCriteria
 * @method     ChildActivityListAssociation[]|ObjectCollection findById(string $id) Return ChildActivityListAssociation objects filtered by the id column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByActivityId(int $activity_id) Return ChildActivityListAssociation objects filtered by the activity_id column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByListId(int $list_id) Return ChildActivityListAssociation objects filtered by the list_id column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByStatus(int $status) Return ChildActivityListAssociation objects filtered by the status column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByDateAdded(string $date_added) Return ChildActivityListAssociation objects filtered by the date_added column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByAlias(string $alias) Return ChildActivityListAssociation objects filtered by the alias column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByDescription(string $description) Return ChildActivityListAssociation objects filtered by the description column
 * @method     ChildActivityListAssociation[]|ObjectCollection findByIsOwner(int $is_owner) Return ChildActivityListAssociation objects filtered by the is_owner column
 * @method     ChildActivityListAssociation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActivityListAssociationQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ActivityListAssociationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'onboard', $modelName = '\\ActivityListAssociation', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActivityListAssociationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActivityListAssociationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActivityListAssociationQuery) {
            return $criteria;
        }
        $query = new ChildActivityListAssociationQuery();
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
     * @return ChildActivityListAssociation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ActivityListAssociationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityListAssociationTableMap::DATABASE_NAME);
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
     * @return ChildActivityListAssociation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, activity_id, list_id, status, date_added, alias, description, is_owner FROM activity_list_assoc WHERE id = :p0';
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
            /** @var ChildActivityListAssociation $obj */
            $obj = new ChildActivityListAssociation();
            $obj->hydrate($row);
            ActivityListAssociationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildActivityListAssociation|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the activity_id column
     *
     * Example usage:
     * <code>
     * $query->filterByActivityId(1234); // WHERE activity_id = 1234
     * $query->filterByActivityId(array(12, 34)); // WHERE activity_id IN (12, 34)
     * $query->filterByActivityId(array('min' => 12)); // WHERE activity_id > 12
     * </code>
     *
     * @see       filterByActivity()
     *
     * @param     mixed $activityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByActivityId($activityId = null, $comparison = null)
    {
        if (is_array($activityId)) {
            $useMinMax = false;
            if (isset($activityId['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_ACTIVITY_ID, $activityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activityId['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_ACTIVITY_ID, $activityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_ACTIVITY_ID, $activityId, $comparison);
    }

    /**
     * Filter the query on the list_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListId(1234); // WHERE list_id = 1234
     * $query->filterByListId(array(12, 34)); // WHERE list_id IN (12, 34)
     * $query->filterByListId(array('min' => 12)); // WHERE list_id > 12
     * </code>
     *
     * @see       filterByActivityList()
     *
     * @param     mixed $listId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByListId($listId = null, $comparison = null)
    {
        if (is_array($listId)) {
            $useMinMax = false;
            if (isset($listId['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_LIST_ID, $listId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listId['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_LIST_ID, $listId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_LIST_ID, $listId, $comparison);
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
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the date_added column
     *
     * Example usage:
     * <code>
     * $query->filterByDateAdded('2011-03-14'); // WHERE date_added = '2011-03-14'
     * $query->filterByDateAdded('now'); // WHERE date_added = '2011-03-14'
     * $query->filterByDateAdded(array('max' => 'yesterday')); // WHERE date_added > '2011-03-13'
     * </code>
     *
     * @param     mixed $dateAdded The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByDateAdded($dateAdded = null, $comparison = null)
    {
        if (is_array($dateAdded)) {
            $useMinMax = false;
            if (isset($dateAdded['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_DATE_ADDED, $dateAdded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateAdded['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_DATE_ADDED, $dateAdded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_DATE_ADDED, $dateAdded, $comparison);
    }

    /**
     * Filter the query on the alias column
     *
     * Example usage:
     * <code>
     * $query->filterByAlias('fooValue');   // WHERE alias = 'fooValue'
     * $query->filterByAlias('%fooValue%'); // WHERE alias LIKE '%fooValue%'
     * </code>
     *
     * @param     string $alias The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByAlias($alias = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alias)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $alias)) {
                $alias = str_replace('*', '%', $alias);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_ALIAS, $alias, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the is_owner column
     *
     * Example usage:
     * <code>
     * $query->filterByIsOwner(1234); // WHERE is_owner = 1234
     * $query->filterByIsOwner(array(12, 34)); // WHERE is_owner IN (12, 34)
     * $query->filterByIsOwner(array('min' => 12)); // WHERE is_owner > 12
     * </code>
     *
     * @param     mixed $isOwner The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByIsOwner($isOwner = null, $comparison = null)
    {
        if (is_array($isOwner)) {
            $useMinMax = false;
            if (isset($isOwner['min'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_IS_OWNER, $isOwner['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isOwner['max'])) {
                $this->addUsingAlias(ActivityListAssociationTableMap::COL_IS_OWNER, $isOwner['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityListAssociationTableMap::COL_IS_OWNER, $isOwner, $comparison);
    }

    /**
     * Filter the query by a related \Activity object
     *
     * @param \Activity|ObjectCollection $activity The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByActivity($activity, $comparison = null)
    {
        if ($activity instanceof \Activity) {
            return $this
                ->addUsingAlias(ActivityListAssociationTableMap::COL_ACTIVITY_ID, $activity->getId(), $comparison);
        } elseif ($activity instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityListAssociationTableMap::COL_ACTIVITY_ID, $activity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByActivity() only accepts arguments of type \Activity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Activity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function joinActivity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Activity');

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
            $this->addJoinObject($join, 'Activity');
        }

        return $this;
    }

    /**
     * Use the Activity relation Activity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ActivityQuery A secondary query class using the current class as primary query
     */
    public function useActivityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActivity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Activity', '\ActivityQuery');
    }

    /**
     * Filter the query by a related \ActivityList object
     *
     * @param \ActivityList|ObjectCollection $activityList The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function filterByActivityList($activityList, $comparison = null)
    {
        if ($activityList instanceof \ActivityList) {
            return $this
                ->addUsingAlias(ActivityListAssociationTableMap::COL_LIST_ID, $activityList->getId(), $comparison);
        } elseif ($activityList instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityListAssociationTableMap::COL_LIST_ID, $activityList->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildActivityListAssociation $activityListAssociation Object to remove from the list of results
     *
     * @return $this|ChildActivityListAssociationQuery The current query, for fluid interface
     */
    public function prune($activityListAssociation = null)
    {
        if ($activityListAssociation) {
            $this->addUsingAlias(ActivityListAssociationTableMap::COL_ID, $activityListAssociation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the activity_list_assoc table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityListAssociationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActivityListAssociationTableMap::clearInstancePool();
            ActivityListAssociationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityListAssociationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActivityListAssociationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ActivityListAssociationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ActivityListAssociationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ActivityListAssociationQuery