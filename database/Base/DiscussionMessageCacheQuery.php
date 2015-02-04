<?php

namespace Base;

use \DiscussionMessageCache as ChildDiscussionMessageCache;
use \DiscussionMessageCacheQuery as ChildDiscussionMessageCacheQuery;
use \Exception;
use \PDO;
use Map\DiscussionMessageCacheTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'discussion_msg_cache' table.
 *
 *
 *
 * @method     ChildDiscussionMessageCacheQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDiscussionMessageCacheQuery orderByDiscussionUserAssociationId($order = Criteria::ASC) Order by the discussion_user_assoc_id column
 * @method     ChildDiscussionMessageCacheQuery orderByMessage($order = Criteria::ASC) Order by the msg column
 * @method     ChildDiscussionMessageCacheQuery orderByTime($order = Criteria::ASC) Order by the time column
 *
 * @method     ChildDiscussionMessageCacheQuery groupById() Group by the id column
 * @method     ChildDiscussionMessageCacheQuery groupByDiscussionUserAssociationId() Group by the discussion_user_assoc_id column
 * @method     ChildDiscussionMessageCacheQuery groupByMessage() Group by the msg column
 * @method     ChildDiscussionMessageCacheQuery groupByTime() Group by the time column
 *
 * @method     ChildDiscussionMessageCacheQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDiscussionMessageCacheQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDiscussionMessageCacheQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDiscussionMessageCacheQuery leftJoinDiscussionUserAssociation($relationAlias = null) Adds a LEFT JOIN clause to the query using the DiscussionUserAssociation relation
 * @method     ChildDiscussionMessageCacheQuery rightJoinDiscussionUserAssociation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DiscussionUserAssociation relation
 * @method     ChildDiscussionMessageCacheQuery innerJoinDiscussionUserAssociation($relationAlias = null) Adds a INNER JOIN clause to the query using the DiscussionUserAssociation relation
 *
 * @method     \DiscussionUserAssociationQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDiscussionMessageCache findOne(ConnectionInterface $con = null) Return the first ChildDiscussionMessageCache matching the query
 * @method     ChildDiscussionMessageCache findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDiscussionMessageCache matching the query, or a new ChildDiscussionMessageCache object populated from the query conditions when no match is found
 *
 * @method     ChildDiscussionMessageCache findOneById(string $id) Return the first ChildDiscussionMessageCache filtered by the id column
 * @method     ChildDiscussionMessageCache findOneByDiscussionUserAssociationId(string $discussion_user_assoc_id) Return the first ChildDiscussionMessageCache filtered by the discussion_user_assoc_id column
 * @method     ChildDiscussionMessageCache findOneByMessage(string $msg) Return the first ChildDiscussionMessageCache filtered by the msg column
 * @method     ChildDiscussionMessageCache findOneByTime(string $time) Return the first ChildDiscussionMessageCache filtered by the time column
 *
 * @method     ChildDiscussionMessageCache[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDiscussionMessageCache objects based on current ModelCriteria
 * @method     ChildDiscussionMessageCache[]|ObjectCollection findById(string $id) Return ChildDiscussionMessageCache objects filtered by the id column
 * @method     ChildDiscussionMessageCache[]|ObjectCollection findByDiscussionUserAssociationId(string $discussion_user_assoc_id) Return ChildDiscussionMessageCache objects filtered by the discussion_user_assoc_id column
 * @method     ChildDiscussionMessageCache[]|ObjectCollection findByMessage(string $msg) Return ChildDiscussionMessageCache objects filtered by the msg column
 * @method     ChildDiscussionMessageCache[]|ObjectCollection findByTime(string $time) Return ChildDiscussionMessageCache objects filtered by the time column
 * @method     ChildDiscussionMessageCache[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DiscussionMessageCacheQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\DiscussionMessageCacheQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'onboard', $modelName = '\\DiscussionMessageCache', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDiscussionMessageCacheQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDiscussionMessageCacheQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDiscussionMessageCacheQuery) {
            return $criteria;
        }
        $query = new ChildDiscussionMessageCacheQuery();
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
     * @return ChildDiscussionMessageCache|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DiscussionMessageCacheTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DiscussionMessageCacheTableMap::DATABASE_NAME);
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
     * @return ChildDiscussionMessageCache A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, discussion_user_assoc_id, msg, time FROM discussion_msg_cache WHERE id = :p0';
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
            /** @var ChildDiscussionMessageCache $obj */
            $obj = new ChildDiscussionMessageCache();
            $obj->hydrate($row);
            DiscussionMessageCacheTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildDiscussionMessageCache|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the discussion_user_assoc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDiscussionUserAssociationId(1234); // WHERE discussion_user_assoc_id = 1234
     * $query->filterByDiscussionUserAssociationId(array(12, 34)); // WHERE discussion_user_assoc_id IN (12, 34)
     * $query->filterByDiscussionUserAssociationId(array('min' => 12)); // WHERE discussion_user_assoc_id > 12
     * </code>
     *
     * @see       filterByDiscussionUserAssociation()
     *
     * @param     mixed $discussionUserAssociationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByDiscussionUserAssociationId($discussionUserAssociationId = null, $comparison = null)
    {
        if (is_array($discussionUserAssociationId)) {
            $useMinMax = false;
            if (isset($discussionUserAssociationId['min'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_DISCUSSION_USER_ASSOC_ID, $discussionUserAssociationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($discussionUserAssociationId['max'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_DISCUSSION_USER_ASSOC_ID, $discussionUserAssociationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_DISCUSSION_USER_ASSOC_ID, $discussionUserAssociationId, $comparison);
    }

    /**
     * Filter the query on the msg column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE msg = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE msg LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_MSG, $message, $comparison);
    }

    /**
     * Filter the query on the time column
     *
     * Example usage:
     * <code>
     * $query->filterByTime('2011-03-14'); // WHERE time = '2011-03-14'
     * $query->filterByTime('now'); // WHERE time = '2011-03-14'
     * $query->filterByTime(array('max' => 'yesterday')); // WHERE time > '2011-03-13'
     * </code>
     *
     * @param     mixed $time The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByTime($time = null, $comparison = null)
    {
        if (is_array($time)) {
            $useMinMax = false;
            if (isset($time['min'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_TIME, $time['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($time['max'])) {
                $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_TIME, $time['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_TIME, $time, $comparison);
    }

    /**
     * Filter the query by a related \DiscussionUserAssociation object
     *
     * @param \DiscussionUserAssociation|ObjectCollection $discussionUserAssociation The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function filterByDiscussionUserAssociation($discussionUserAssociation, $comparison = null)
    {
        if ($discussionUserAssociation instanceof \DiscussionUserAssociation) {
            return $this
                ->addUsingAlias(DiscussionMessageCacheTableMap::COL_DISCUSSION_USER_ASSOC_ID, $discussionUserAssociation->getId(), $comparison);
        } elseif ($discussionUserAssociation instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DiscussionMessageCacheTableMap::COL_DISCUSSION_USER_ASSOC_ID, $discussionUserAssociation->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDiscussionUserAssociation() only accepts arguments of type \DiscussionUserAssociation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DiscussionUserAssociation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function joinDiscussionUserAssociation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DiscussionUserAssociation');

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
            $this->addJoinObject($join, 'DiscussionUserAssociation');
        }

        return $this;
    }

    /**
     * Use the DiscussionUserAssociation relation DiscussionUserAssociation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DiscussionUserAssociationQuery A secondary query class using the current class as primary query
     */
    public function useDiscussionUserAssociationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDiscussionUserAssociation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DiscussionUserAssociation', '\DiscussionUserAssociationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDiscussionMessageCache $discussionMessageCache Object to remove from the list of results
     *
     * @return $this|ChildDiscussionMessageCacheQuery The current query, for fluid interface
     */
    public function prune($discussionMessageCache = null)
    {
        if ($discussionMessageCache) {
            $this->addUsingAlias(DiscussionMessageCacheTableMap::COL_ID, $discussionMessageCache->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the discussion_msg_cache table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionMessageCacheTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DiscussionMessageCacheTableMap::clearInstancePool();
            DiscussionMessageCacheTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DiscussionMessageCacheTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DiscussionMessageCacheTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DiscussionMessageCacheTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DiscussionMessageCacheTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DiscussionMessageCacheQuery
