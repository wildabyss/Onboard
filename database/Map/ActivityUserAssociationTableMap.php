<?php

namespace Map;

use \ActivityUserAssociation;
use \ActivityUserAssociationQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'activity_user_assoc' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ActivityUserAssociationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ActivityUserAssociationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'onboard';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'activity_user_assoc';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ActivityUserAssociation';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ActivityUserAssociation';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'activity_user_assoc.id';

    /**
     * the column name for the activity_id field
     */
    const COL_ACTIVITY_ID = 'activity_user_assoc.activity_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'activity_user_assoc.user_id';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'activity_user_assoc.status';

    /**
     * the column name for the date_added field
     */
    const COL_DATE_ADDED = 'activity_user_assoc.date_added';

    /**
     * the column name for the alias field
     */
    const COL_ALIAS = 'activity_user_assoc.alias';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'activity_user_assoc.description';

    /**
     * the column name for the is_owner field
     */
    const COL_IS_OWNER = 'activity_user_assoc.is_owner';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'ActivityId', 'UserId', 'Status', 'DateAdded', 'Alias', 'Description', 'IsOwner', ),
        self::TYPE_CAMELNAME     => array('id', 'activityId', 'userId', 'status', 'dateAdded', 'alias', 'description', 'isOwner', ),
        self::TYPE_COLNAME       => array(ActivityUserAssociationTableMap::COL_ID, ActivityUserAssociationTableMap::COL_ACTIVITY_ID, ActivityUserAssociationTableMap::COL_USER_ID, ActivityUserAssociationTableMap::COL_STATUS, ActivityUserAssociationTableMap::COL_DATE_ADDED, ActivityUserAssociationTableMap::COL_ALIAS, ActivityUserAssociationTableMap::COL_DESCRIPTION, ActivityUserAssociationTableMap::COL_IS_OWNER, ),
        self::TYPE_FIELDNAME     => array('id', 'activity_id', 'user_id', 'status', 'date_added', 'alias', 'description', 'is_owner', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ActivityId' => 1, 'UserId' => 2, 'Status' => 3, 'DateAdded' => 4, 'Alias' => 5, 'Description' => 6, 'IsOwner' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'activityId' => 1, 'userId' => 2, 'status' => 3, 'dateAdded' => 4, 'alias' => 5, 'description' => 6, 'isOwner' => 7, ),
        self::TYPE_COLNAME       => array(ActivityUserAssociationTableMap::COL_ID => 0, ActivityUserAssociationTableMap::COL_ACTIVITY_ID => 1, ActivityUserAssociationTableMap::COL_USER_ID => 2, ActivityUserAssociationTableMap::COL_STATUS => 3, ActivityUserAssociationTableMap::COL_DATE_ADDED => 4, ActivityUserAssociationTableMap::COL_ALIAS => 5, ActivityUserAssociationTableMap::COL_DESCRIPTION => 6, ActivityUserAssociationTableMap::COL_IS_OWNER => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'activity_id' => 1, 'user_id' => 2, 'status' => 3, 'date_added' => 4, 'alias' => 5, 'description' => 6, 'is_owner' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('activity_user_assoc');
        $this->setPhpName('ActivityUserAssociation');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ActivityUserAssociation');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'BIGINT', true, null, null);
        $this->addForeignKey('activity_id', 'ActivityId', 'INTEGER', 'activity', 'id', true, 10, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', true, 10, null);
        $this->addColumn('status', 'Status', 'TINYINT', true, 3, null);
        $this->addColumn('date_added', 'DateAdded', 'TIMESTAMP', true, null, null);
        $this->addColumn('alias', 'Alias', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 255, null);
        $this->addColumn('is_owner', 'IsOwner', 'TINYINT', true, 3, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Activity', '\\Activity', RelationMap::MANY_TO_ONE, array('activity_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('DiscussionUserAssociation', '\\DiscussionUserAssociation', RelationMap::ONE_TO_MANY, array('id' => 'activity_user_assoc_id', ), 'CASCADE', null, 'DiscussionUserAssociations');
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to activity_user_assoc     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        DiscussionUserAssociationTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ActivityUserAssociationTableMap::CLASS_DEFAULT : ActivityUserAssociationTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (ActivityUserAssociation object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ActivityUserAssociationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ActivityUserAssociationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ActivityUserAssociationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ActivityUserAssociationTableMap::OM_CLASS;
            /** @var ActivityUserAssociation $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ActivityUserAssociationTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ActivityUserAssociationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ActivityUserAssociationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ActivityUserAssociation $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ActivityUserAssociationTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_ID);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_ACTIVITY_ID);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_USER_ID);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_STATUS);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_DATE_ADDED);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_ALIAS);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(ActivityUserAssociationTableMap::COL_IS_OWNER);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.activity_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.date_added');
            $criteria->addSelectColumn($alias . '.alias');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.is_owner');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ActivityUserAssociationTableMap::DATABASE_NAME)->getTable(ActivityUserAssociationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ActivityUserAssociationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ActivityUserAssociationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ActivityUserAssociationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ActivityUserAssociation or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ActivityUserAssociation object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ActivityUserAssociation) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ActivityUserAssociationTableMap::DATABASE_NAME);
            $criteria->add(ActivityUserAssociationTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ActivityUserAssociationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ActivityUserAssociationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ActivityUserAssociationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the activity_user_assoc table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ActivityUserAssociationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ActivityUserAssociation or Criteria object.
     *
     * @param mixed               $criteria Criteria or ActivityUserAssociation object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityUserAssociationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ActivityUserAssociation object
        }

        if ($criteria->containsKey(ActivityUserAssociationTableMap::COL_ID) && $criteria->keyContainsValue(ActivityUserAssociationTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ActivityUserAssociationTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ActivityUserAssociationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ActivityUserAssociationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ActivityUserAssociationTableMap::buildTableMap();
