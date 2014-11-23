<?php

namespace Base;

use \UserCommunityAssociation as ChildUserCommunityAssociation;
use \UserCommunityAssociationQuery as ChildUserCommunityAssociationQuery;
use \Exception;
use \PDO;
use Map\UserCommunityAssociationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_community_assoc' table.
 *
 *
 *
 * @method     ChildUserCommunityAssociationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserCommunityAssociationQuery orderByUserIdLeft($order = Criteria::ASC) Order by the user_id_left column
 * @method     ChildUserCommunityAssociationQuery orderByUserIdRight($order = Criteria::ASC) Order by the user_id_right column
 *
 * @method     ChildUserCommunityAssociationQuery groupById() Group by the id column
 * @method     ChildUserCommunityAssociationQuery groupByUserIdLeft() Group by the user_id_left column
 * @method     ChildUserCommunityAssociationQuery groupByUserIdRight() Group by the user_id_right column
 *
 * @method     ChildUserCommunityAssociationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserCommunityAssociationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserCommunityAssociationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserCommunityAssociationQuery leftJoinUserRelatedByUserIdLeft($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUserIdLeft relation
 * @method     ChildUserCommunityAssociationQuery rightJoinUserRelatedByUserIdLeft($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUserIdLeft relation
 * @method     ChildUserCommunityAssociationQuery innerJoinUserRelatedByUserIdLeft($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUserIdLeft relation
 *
 * @method     ChildUserCommunityAssociationQuery leftJoinUserRelatedByUserIdRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUserIdRight relation
 * @method     ChildUserCommunityAssociationQuery rightJoinUserRelatedByUserIdRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUserIdRight relation
 * @method     ChildUserCommunityAssociationQuery innerJoinUserRelatedByUserIdRight($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUserIdRight relation
 *
 * @method     \UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserCommunityAssociation findOne(ConnectionInterface $con = null) Return the first ChildUserCommunityAssociation matching the query
 * @method     ChildUserCommunityAssociation findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserCommunityAssociation matching the query, or a new ChildUserCommunityAssociation object populated from the query conditions when no match is found
 *
 * @method     ChildUserCommunityAssociation findOneById(string $id) Return the first ChildUserCommunityAssociation filtered by the id column
 * @method     ChildUserCommunityAssociation findOneByUserIdLeft(int $user_id_left) Return the first ChildUserCommunityAssociation filtered by the user_id_left column
 * @method     ChildUserCommunityAssociation findOneByUserIdRight(int $user_id_right) Return the first ChildUserCommunityAssociation filtered by the user_id_right column
 *
 * @method     ChildUserCommunityAssociation[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserCommunityAssociation objects based on current ModelCriteria
 * @method     ChildUserCommunityAssociation[]|ObjectCollection findById(string $id) Return ChildUserCommunityAssociation objects filtered by the id column
 * @method     ChildUserCommunityAssociation[]|ObjectCollection findByUserIdLeft(int $user_id_left) Return ChildUserCommunityAssociation objects filtered by the user_id_left column
 * @method     ChildUserCommunityAssociation[]|ObjectCollection findByUserIdRight(int $user_id_right) Return ChildUserCommunityAssociation objects filtered by the user_id_right column
 * @method     ChildUserCommunityAssociation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserCommunityAssociationQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\UserCommunityAssociationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'onboard', $modelName = '\\UserCommunityAssociation', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserCommunityAssociationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserCommunityAssociationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserCommunityAssociationQuery) {
            return $criteria;
        }
        $query = new ChildUserCommunityAssociationQuery();
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
     * @return ChildUserCommunityAssociation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserCommunityAssociationTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserCommunityAssociationTableMap::DATABASE_NAME);
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
     * @return ChildUserCommunityAssociation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id_left, user_id_right FROM user_community_assoc WHERE id = :p0';
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
            /** @var ChildUserCommunityAssociation $obj */
            $obj = new ChildUserCommunityAssociation();
            $obj->hydrate($row);
            UserCommunityAssociationTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUserCommunityAssociation|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id_left column
     *
     * Example usage:
     * <code>
     * $query->filterByUserIdLeft(1234); // WHERE user_id_left = 1234
     * $query->filterByUserIdLeft(array(12, 34)); // WHERE user_id_left IN (12, 34)
     * $query->filterByUserIdLeft(array('min' => 12)); // WHERE user_id_left > 12
     * </code>
     *
     * @see       filterByUserRelatedByUserIdLeft()
     *
     * @param     mixed $userIdLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByUserIdLeft($userIdLeft = null, $comparison = null)
    {
        if (is_array($userIdLeft)) {
            $useMinMax = false;
            if (isset($userIdLeft['min'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_LEFT, $userIdLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userIdLeft['max'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_LEFT, $userIdLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_LEFT, $userIdLeft, $comparison);
    }

    /**
     * Filter the query on the user_id_right column
     *
     * Example usage:
     * <code>
     * $query->filterByUserIdRight(1234); // WHERE user_id_right = 1234
     * $query->filterByUserIdRight(array(12, 34)); // WHERE user_id_right IN (12, 34)
     * $query->filterByUserIdRight(array('min' => 12)); // WHERE user_id_right > 12
     * </code>
     *
     * @see       filterByUserRelatedByUserIdRight()
     *
     * @param     mixed $userIdRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByUserIdRight($userIdRight = null, $comparison = null)
    {
        if (is_array($userIdRight)) {
            $useMinMax = false;
            if (isset($userIdRight['min'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_RIGHT, $userIdRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userIdRight['max'])) {
                $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_RIGHT, $userIdRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_RIGHT, $userIdRight, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUserIdLeft($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_LEFT, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_LEFT, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUserIdLeft() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUserIdLeft relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUserIdLeft($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByUserIdLeft');

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
            $this->addJoinObject($join, 'UserRelatedByUserIdLeft');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByUserIdLeft relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUserIdLeftQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedByUserIdLeft($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUserIdLeft', '\UserQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUserIdRight($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_RIGHT, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserCommunityAssociationTableMap::COL_USER_ID_RIGHT, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUserIdRight() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUserIdRight relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUserIdRight($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByUserIdRight');

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
            $this->addJoinObject($join, 'UserRelatedByUserIdRight');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByUserIdRight relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUserIdRightQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedByUserIdRight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUserIdRight', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserCommunityAssociation $userCommunityAssociation Object to remove from the list of results
     *
     * @return $this|ChildUserCommunityAssociationQuery The current query, for fluid interface
     */
    public function prune($userCommunityAssociation = null)
    {
        if ($userCommunityAssociation) {
            $this->addUsingAlias(UserCommunityAssociationTableMap::COL_ID, $userCommunityAssociation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_community_assoc table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserCommunityAssociationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserCommunityAssociationTableMap::clearInstancePool();
            UserCommunityAssociationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserCommunityAssociationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserCommunityAssociationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserCommunityAssociationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserCommunityAssociationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserCommunityAssociationQuery
