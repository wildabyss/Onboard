<?php

namespace Base;

use \DiscussionUserAssociation as ChildDiscussionUserAssociation;
use \DiscussionUserAssociationQuery as ChildDiscussionUserAssociationQuery;
use \Exception;
use \PDO;
use Map\DiscussionUserAssociationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'discussion_user_assoc' table.
 *
 *
 *
 * @method     ChildDiscussionUserAssociationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDiscussionUserAssociationQuery orderByDiscussionId($order = Criteria::ASC) Order by the discussion_id column
 * @method     ChildDiscussionUserAssociationQuery orderByActivityUserAssociationId($order = Criteria::ASC) Order by the activity_user_assoc_id column
 * @method     ChildDiscussionUserAssociationQuery orderByStatus($order = Criteria::ASC) Order by the status column
 *
 * @method     ChildDiscussionUserAssociationQuery groupById() Group by the id column
 * @method     ChildDiscussionUserAssociationQuery groupByDiscussionId() Group by the discussion_id column
 * @method     ChildDiscussionUserAssociationQuery groupByActivityUserAssociationId() Group by the activity_user_assoc_id column
 * @method     ChildDiscussionUserAssociationQuery groupByStatus() Group by the status column
 *
 * @method     ChildDiscussionUserAssociationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDiscussionUserAssociationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDiscussionUserAssociationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDiscussionUserAssociationQuery leftJoinDiscussion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Discussion relation
 * @method     ChildDiscussionUserAssociationQuery rightJoinDiscussion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Discussion relation
 * @method     ChildDiscussionUserAssociationQuery innerJoinDiscussion($relationAlias = null) Adds a INNER JOIN clause to the query using the Discussion relation
 *
 * @method     ChildDiscussionUserAssociationQuery leftJoinActivityUserAssociation($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityUserAssociation relation
 * @method     ChildDiscussionUserAssociationQuery rightJoinActivityUserAssociation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityUserAssociation relation
 * @method     ChildDiscussionUserAssociationQuery innerJoinActivityUserAssociation($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityUserAssociation relation
 *
 * @method     \DiscussionQuery|\ActivityUserAssociationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDiscussionUserAssociation findOne(ConnectionInterface $con = null) Return the first ChildDiscussionUserAssociation matching the query
 * @method     ChildDiscussionUserAssociation findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDiscussionUserAssociation matching the query, or a new ChildDiscussionUserAssociation object populated from the query conditions when no match is found
 *
 * @method     ChildDiscussionUserAssociation findOneById(string $id) Return the first ChildDiscussionUserAssociation filtered by the id column
 * @method     ChildDiscussionUserAssociation findOneByDiscussionId(int $discussion_id) Return the first ChildDiscussionUserAssociation filtered by the discussion_id column
 * @method     ChildDiscussionUserAssociation findOneByActivityUserAssociationId(string $activity_user_assoc_id) Return the first ChildDiscussionUserAssociation filtered by the activity_user_assoc_id column
 * @method     ChildDiscussionUserAssociation findOneByStatus(int $status) Return the first ChildDiscussionUserAssociation filtered by the status column
 *
 * @method     ChildDiscussionUserAssociation[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDiscussionUserAssociation objects based on current ModelCriteria
 * @method     ChildDiscussionUserAssociation[]|ObjectCollection findById(string $id) Return ChildDiscussionUserAssociation objects filtered by the id column
 * @method     ChildDiscussionUserAssociation[]|ObjectCollection findByDiscussionId(int $discussion_id) Return ChildDiscussionUserAssociation objects filtered by the discussion_id column
 * @method     ChildDiscussionUserAssociation[]|ObjectCollection findByActivityUserAssociationId(string $activity_user_assoc_id) Return ChildDiscussionUserAssociation objects filtered by the activity_user_assoc_id column
 * @method     ChildDiscussionUserAssociation[]|ObjectCollection findByStatus(int $status) Return ChildDiscussionUserAssociation objects filtered by the status column
 * @method     ChildDiscussionUserAssociation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DiscussionUserAssociationQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\DiscussionUserAssociationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'onboard', $modelName = '\\DiscussionUserAssociation', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDiscussionUserAssociationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDiscussionUserAssociationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDiscussionUserAssociationQuery) {
            return $criteria;
        }
        $query = new ChildDiscussionUserAssociationQuery();
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
     * @return ChildDiscussionUserAssociation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DiscussionUserAssociationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
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
     * @return ChildDiscussionUserAssociation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, discussion_id, activity_user_assoc_id, status FROM discussion_user_assoc WHERE id = :p0';
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
            /** @var ChildDiscussionUserAssociation $obj */
            $obj = new ChildDiscussionUserAssociation();
            $obj->hydrate($row);
            DiscussionUserAssociationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildDiscussionUserAssociation|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the discussion_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDiscussionId(1234); // WHERE discussion_id = 1234
     * $query->filterByDiscussionId(array(12, 34)); // WHERE discussion_id IN (12, 34)
     * $query->filterByDiscussionId(array('min' => 12)); // WHERE discussion_id > 12
     * </code>
     *
     * @see       filterByDiscussion()
     *
     * @param     mixed $discussionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByDiscussionId($discussionId = null, $comparison = null)
    {
        if (is_array($discussionId)) {
            $useMinMax = false;
            if (isset($discussionId['min'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_DISCUSSION_ID, $discussionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($discussionId['max'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_DISCUSSION_ID, $discussionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_DISCUSSION_ID, $discussionId, $comparison);
    }

    /**
     * Filter the query on the activity_user_assoc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByActivityUserAssociationId(1234); // WHERE activity_user_assoc_id = 1234
     * $query->filterByActivityUserAssociationId(array(12, 34)); // WHERE activity_user_assoc_id IN (12, 34)
     * $query->filterByActivityUserAssociationId(array('min' => 12)); // WHERE activity_user_assoc_id > 12
     * </code>
     *
     * @see       filterByActivityUserAssociation()
     *
     * @param     mixed $activityUserAssociationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByActivityUserAssociationId($activityUserAssociationId = null, $comparison = null)
    {
        if (is_array($activityUserAssociationId)) {
            $useMinMax = false;
            if (isset($activityUserAssociationId['min'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ACTIVITY_USER_ASSOC_ID, $activityUserAssociationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activityUserAssociationId['max'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ACTIVITY_USER_ASSOC_ID, $activityUserAssociationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ACTIVITY_USER_ASSOC_ID, $activityUserAssociationId, $comparison);
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
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query by a related \Discussion object
     *
     * @param \Discussion|ObjectCollection $discussion The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByDiscussion($discussion, $comparison = null)
    {
        if ($discussion instanceof \Discussion) {
            return $this
                ->addUsingAlias(DiscussionUserAssociationTableMap::COL_DISCUSSION_ID, $discussion->getId(), $comparison);
        } elseif ($discussion instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DiscussionUserAssociationTableMap::COL_DISCUSSION_ID, $discussion->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDiscussion() only accepts arguments of type \Discussion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Discussion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function joinDiscussion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Discussion');

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
            $this->addJoinObject($join, 'Discussion');
        }

        return $this;
    }

    /**
     * Use the Discussion relation Discussion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DiscussionQuery A secondary query class using the current class as primary query
     */
    public function useDiscussionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDiscussion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Discussion', '\DiscussionQuery');
    }

    /**
     * Filter the query by a related \ActivityUserAssociation object
     *
     * @param \ActivityUserAssociation|ObjectCollection $activityUserAssociation The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function filterByActivityUserAssociation($activityUserAssociation, $comparison = null)
    {
        if ($activityUserAssociation instanceof \ActivityUserAssociation) {
            return $this
                ->addUsingAlias(DiscussionUserAssociationTableMap::COL_ACTIVITY_USER_ASSOC_ID, $activityUserAssociation->getId(), $comparison);
        } elseif ($activityUserAssociation instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DiscussionUserAssociationTableMap::COL_ACTIVITY_USER_ASSOC_ID, $activityUserAssociation->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildDiscussionUserAssociation $discussionUserAssociation Object to remove from the list of results
     *
     * @return $this|ChildDiscussionUserAssociationQuery The current query, for fluid interface
     */
    public function prune($discussionUserAssociation = null)
    {
        if ($discussionUserAssociation) {
            $this->addUsingAlias(DiscussionUserAssociationTableMap::COL_ID, $discussionUserAssociation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the discussion_user_assoc table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DiscussionUserAssociationTableMap::clearInstancePool();
            DiscussionUserAssociationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionUserAssociationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DiscussionUserAssociationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DiscussionUserAssociationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DiscussionUserAssociationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DiscussionUserAssociationQuery
