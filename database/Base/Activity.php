<?php

namespace Base;

use \Activity as ChildActivity;
use \ActivityCategoryAssociation as ChildActivityCategoryAssociation;
use \ActivityCategoryAssociationQuery as ChildActivityCategoryAssociationQuery;
use \ActivityListAssociation as ChildActivityListAssociation;
use \ActivityListAssociationQuery as ChildActivityListAssociationQuery;
use \ActivityQuery as ChildActivityQuery;
use \Discussion as ChildDiscussion;
use \DiscussionQuery as ChildDiscussionQuery;
use \Exception;
use \PDO;
use Map\ActivityTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'activity' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Activity implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ActivityTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * @var        ObjectCollection|ChildActivityCategoryAssociation[] Collection to store aggregation of ChildActivityCategoryAssociation objects.
     */
    protected $collActivityCategoryAssociations;
    protected $collActivityCategoryAssociationsPartial;

    /**
     * @var        ObjectCollection|ChildActivityListAssociation[] Collection to store aggregation of ChildActivityListAssociation objects.
     */
    protected $collActivityListAssociations;
    protected $collActivityListAssociationsPartial;

    /**
     * @var        ObjectCollection|ChildDiscussion[] Collection to store aggregation of ChildDiscussion objects.
     */
    protected $collDiscussions;
    protected $collDiscussionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivityCategoryAssociation[]
     */
    protected $activityCategoryAssociationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivityListAssociation[]
     */
    protected $activityListAssociationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDiscussion[]
     */
    protected $discussionsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Activity object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Activity</code> instance.  If
     * <code>obj</code> is an instance of <code>Activity</code>, delegates to
     * <code>equals(Activity)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Activity The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\Activity The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ActivityTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return $this|\Activity The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[ActivityTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ActivityTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ActivityTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = ActivityTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Activity'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildActivityQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collActivityCategoryAssociations = null;

            $this->collActivityListAssociations = null;

            $this->collDiscussions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Activity::setDeleted()
     * @see Activity::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildActivityQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ActivityTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->activityCategoryAssociationsScheduledForDeletion !== null) {
                if (!$this->activityCategoryAssociationsScheduledForDeletion->isEmpty()) {
                    \ActivityCategoryAssociationQuery::create()
                        ->filterByPrimaryKeys($this->activityCategoryAssociationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activityCategoryAssociationsScheduledForDeletion = null;
                }
            }

            if ($this->collActivityCategoryAssociations !== null) {
                foreach ($this->collActivityCategoryAssociations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->activityListAssociationsScheduledForDeletion !== null) {
                if (!$this->activityListAssociationsScheduledForDeletion->isEmpty()) {
                    \ActivityListAssociationQuery::create()
                        ->filterByPrimaryKeys($this->activityListAssociationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activityListAssociationsScheduledForDeletion = null;
                }
            }

            if ($this->collActivityListAssociations !== null) {
                foreach ($this->collActivityListAssociations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->discussionsScheduledForDeletion !== null) {
                if (!$this->discussionsScheduledForDeletion->isEmpty()) {
                    \DiscussionQuery::create()
                        ->filterByPrimaryKeys($this->discussionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->discussionsScheduledForDeletion = null;
                }
            }

            if ($this->collDiscussions !== null) {
                foreach ($this->collDiscussions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ActivityTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ActivityTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ActivityTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ActivityTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO activity (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ActivityTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Activity'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Activity'][$this->hashCode()] = true;
        $keys = ActivityTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collActivityCategoryAssociations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activityCategoryAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'activity_category_assocs';
                        break;
                    default:
                        $key = 'ActivityCategoryAssociations';
                }

                $result[$key] = $this->collActivityCategoryAssociations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collActivityListAssociations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activityListAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'activity_list_assocs';
                        break;
                    default:
                        $key = 'ActivityListAssociations';
                }

                $result[$key] = $this->collActivityListAssociations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDiscussions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'discussions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'discussions';
                        break;
                    default:
                        $key = 'Discussions';
                }

                $result[$key] = $this->collDiscussions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Activity
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ActivityTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Activity
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ActivityTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Activity The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ActivityTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ActivityTableMap::COL_ID)) {
            $criteria->add(ActivityTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ActivityTableMap::COL_NAME)) {
            $criteria->add(ActivityTableMap::COL_NAME, $this->name);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildActivityQuery::create();
        $criteria->add(ActivityTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Activity (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getActivityCategoryAssociations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityCategoryAssociation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getActivityListAssociations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityListAssociation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDiscussions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDiscussion($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Activity Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ActivityCategoryAssociation' == $relationName) {
            return $this->initActivityCategoryAssociations();
        }
        if ('ActivityListAssociation' == $relationName) {
            return $this->initActivityListAssociations();
        }
        if ('Discussion' == $relationName) {
            return $this->initDiscussions();
        }
    }

    /**
     * Clears out the collActivityCategoryAssociations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivityCategoryAssociations()
     */
    public function clearActivityCategoryAssociations()
    {
        $this->collActivityCategoryAssociations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivityCategoryAssociations collection loaded partially.
     */
    public function resetPartialActivityCategoryAssociations($v = true)
    {
        $this->collActivityCategoryAssociationsPartial = $v;
    }

    /**
     * Initializes the collActivityCategoryAssociations collection.
     *
     * By default this just sets the collActivityCategoryAssociations collection to an empty array (like clearcollActivityCategoryAssociations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivityCategoryAssociations($overrideExisting = true)
    {
        if (null !== $this->collActivityCategoryAssociations && !$overrideExisting) {
            return;
        }
        $this->collActivityCategoryAssociations = new ObjectCollection();
        $this->collActivityCategoryAssociations->setModel('\ActivityCategoryAssociation');
    }

    /**
     * Gets an array of ChildActivityCategoryAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivityCategoryAssociation[] List of ChildActivityCategoryAssociation objects
     * @throws PropelException
     */
    public function getActivityCategoryAssociations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityCategoryAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityCategoryAssociations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivityCategoryAssociations) {
                // return empty collection
                $this->initActivityCategoryAssociations();
            } else {
                $collActivityCategoryAssociations = ChildActivityCategoryAssociationQuery::create(null, $criteria)
                    ->filterByActivity($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivityCategoryAssociationsPartial && count($collActivityCategoryAssociations)) {
                        $this->initActivityCategoryAssociations(false);

                        foreach ($collActivityCategoryAssociations as $obj) {
                            if (false == $this->collActivityCategoryAssociations->contains($obj)) {
                                $this->collActivityCategoryAssociations->append($obj);
                            }
                        }

                        $this->collActivityCategoryAssociationsPartial = true;
                    }

                    return $collActivityCategoryAssociations;
                }

                if ($partial && $this->collActivityCategoryAssociations) {
                    foreach ($this->collActivityCategoryAssociations as $obj) {
                        if ($obj->isNew()) {
                            $collActivityCategoryAssociations[] = $obj;
                        }
                    }
                }

                $this->collActivityCategoryAssociations = $collActivityCategoryAssociations;
                $this->collActivityCategoryAssociationsPartial = false;
            }
        }

        return $this->collActivityCategoryAssociations;
    }

    /**
     * Sets a collection of ChildActivityCategoryAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activityCategoryAssociations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function setActivityCategoryAssociations(Collection $activityCategoryAssociations, ConnectionInterface $con = null)
    {
        /** @var ChildActivityCategoryAssociation[] $activityCategoryAssociationsToDelete */
        $activityCategoryAssociationsToDelete = $this->getActivityCategoryAssociations(new Criteria(), $con)->diff($activityCategoryAssociations);


        $this->activityCategoryAssociationsScheduledForDeletion = $activityCategoryAssociationsToDelete;

        foreach ($activityCategoryAssociationsToDelete as $activityCategoryAssociationRemoved) {
            $activityCategoryAssociationRemoved->setActivity(null);
        }

        $this->collActivityCategoryAssociations = null;
        foreach ($activityCategoryAssociations as $activityCategoryAssociation) {
            $this->addActivityCategoryAssociation($activityCategoryAssociation);
        }

        $this->collActivityCategoryAssociations = $activityCategoryAssociations;
        $this->collActivityCategoryAssociationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ActivityCategoryAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ActivityCategoryAssociation objects.
     * @throws PropelException
     */
    public function countActivityCategoryAssociations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityCategoryAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityCategoryAssociations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivityCategoryAssociations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivityCategoryAssociations());
            }

            $query = ChildActivityCategoryAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivity($this)
                ->count($con);
        }

        return count($this->collActivityCategoryAssociations);
    }

    /**
     * Method called to associate a ChildActivityCategoryAssociation object to this object
     * through the ChildActivityCategoryAssociation foreign key attribute.
     *
     * @param  ChildActivityCategoryAssociation $l ChildActivityCategoryAssociation
     * @return $this|\Activity The current object (for fluent API support)
     */
    public function addActivityCategoryAssociation(ChildActivityCategoryAssociation $l)
    {
        if ($this->collActivityCategoryAssociations === null) {
            $this->initActivityCategoryAssociations();
            $this->collActivityCategoryAssociationsPartial = true;
        }

        if (!$this->collActivityCategoryAssociations->contains($l)) {
            $this->doAddActivityCategoryAssociation($l);
        }

        return $this;
    }

    /**
     * @param ChildActivityCategoryAssociation $activityCategoryAssociation The ChildActivityCategoryAssociation object to add.
     */
    protected function doAddActivityCategoryAssociation(ChildActivityCategoryAssociation $activityCategoryAssociation)
    {
        $this->collActivityCategoryAssociations[]= $activityCategoryAssociation;
        $activityCategoryAssociation->setActivity($this);
    }

    /**
     * @param  ChildActivityCategoryAssociation $activityCategoryAssociation The ChildActivityCategoryAssociation object to remove.
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function removeActivityCategoryAssociation(ChildActivityCategoryAssociation $activityCategoryAssociation)
    {
        if ($this->getActivityCategoryAssociations()->contains($activityCategoryAssociation)) {
            $pos = $this->collActivityCategoryAssociations->search($activityCategoryAssociation);
            $this->collActivityCategoryAssociations->remove($pos);
            if (null === $this->activityCategoryAssociationsScheduledForDeletion) {
                $this->activityCategoryAssociationsScheduledForDeletion = clone $this->collActivityCategoryAssociations;
                $this->activityCategoryAssociationsScheduledForDeletion->clear();
            }
            $this->activityCategoryAssociationsScheduledForDeletion[]= clone $activityCategoryAssociation;
            $activityCategoryAssociation->setActivity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Activity is new, it will return
     * an empty collection; or if this Activity has previously
     * been saved, it will retrieve related ActivityCategoryAssociations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Activity.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivityCategoryAssociation[] List of ChildActivityCategoryAssociation objects
     */
    public function getActivityCategoryAssociationsJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityCategoryAssociationQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getActivityCategoryAssociations($query, $con);
    }

    /**
     * Clears out the collActivityListAssociations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivityListAssociations()
     */
    public function clearActivityListAssociations()
    {
        $this->collActivityListAssociations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivityListAssociations collection loaded partially.
     */
    public function resetPartialActivityListAssociations($v = true)
    {
        $this->collActivityListAssociationsPartial = $v;
    }

    /**
     * Initializes the collActivityListAssociations collection.
     *
     * By default this just sets the collActivityListAssociations collection to an empty array (like clearcollActivityListAssociations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivityListAssociations($overrideExisting = true)
    {
        if (null !== $this->collActivityListAssociations && !$overrideExisting) {
            return;
        }
        $this->collActivityListAssociations = new ObjectCollection();
        $this->collActivityListAssociations->setModel('\ActivityListAssociation');
    }

    /**
     * Gets an array of ChildActivityListAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivityListAssociation[] List of ChildActivityListAssociation objects
     * @throws PropelException
     */
    public function getActivityListAssociations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityListAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityListAssociations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivityListAssociations) {
                // return empty collection
                $this->initActivityListAssociations();
            } else {
                $collActivityListAssociations = ChildActivityListAssociationQuery::create(null, $criteria)
                    ->filterByActivity($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivityListAssociationsPartial && count($collActivityListAssociations)) {
                        $this->initActivityListAssociations(false);

                        foreach ($collActivityListAssociations as $obj) {
                            if (false == $this->collActivityListAssociations->contains($obj)) {
                                $this->collActivityListAssociations->append($obj);
                            }
                        }

                        $this->collActivityListAssociationsPartial = true;
                    }

                    return $collActivityListAssociations;
                }

                if ($partial && $this->collActivityListAssociations) {
                    foreach ($this->collActivityListAssociations as $obj) {
                        if ($obj->isNew()) {
                            $collActivityListAssociations[] = $obj;
                        }
                    }
                }

                $this->collActivityListAssociations = $collActivityListAssociations;
                $this->collActivityListAssociationsPartial = false;
            }
        }

        return $this->collActivityListAssociations;
    }

    /**
     * Sets a collection of ChildActivityListAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activityListAssociations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function setActivityListAssociations(Collection $activityListAssociations, ConnectionInterface $con = null)
    {
        /** @var ChildActivityListAssociation[] $activityListAssociationsToDelete */
        $activityListAssociationsToDelete = $this->getActivityListAssociations(new Criteria(), $con)->diff($activityListAssociations);


        $this->activityListAssociationsScheduledForDeletion = $activityListAssociationsToDelete;

        foreach ($activityListAssociationsToDelete as $activityListAssociationRemoved) {
            $activityListAssociationRemoved->setActivity(null);
        }

        $this->collActivityListAssociations = null;
        foreach ($activityListAssociations as $activityListAssociation) {
            $this->addActivityListAssociation($activityListAssociation);
        }

        $this->collActivityListAssociations = $activityListAssociations;
        $this->collActivityListAssociationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ActivityListAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ActivityListAssociation objects.
     * @throws PropelException
     */
    public function countActivityListAssociations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityListAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityListAssociations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivityListAssociations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivityListAssociations());
            }

            $query = ChildActivityListAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivity($this)
                ->count($con);
        }

        return count($this->collActivityListAssociations);
    }

    /**
     * Method called to associate a ChildActivityListAssociation object to this object
     * through the ChildActivityListAssociation foreign key attribute.
     *
     * @param  ChildActivityListAssociation $l ChildActivityListAssociation
     * @return $this|\Activity The current object (for fluent API support)
     */
    public function addActivityListAssociation(ChildActivityListAssociation $l)
    {
        if ($this->collActivityListAssociations === null) {
            $this->initActivityListAssociations();
            $this->collActivityListAssociationsPartial = true;
        }

        if (!$this->collActivityListAssociations->contains($l)) {
            $this->doAddActivityListAssociation($l);
        }

        return $this;
    }

    /**
     * @param ChildActivityListAssociation $activityListAssociation The ChildActivityListAssociation object to add.
     */
    protected function doAddActivityListAssociation(ChildActivityListAssociation $activityListAssociation)
    {
        $this->collActivityListAssociations[]= $activityListAssociation;
        $activityListAssociation->setActivity($this);
    }

    /**
     * @param  ChildActivityListAssociation $activityListAssociation The ChildActivityListAssociation object to remove.
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function removeActivityListAssociation(ChildActivityListAssociation $activityListAssociation)
    {
        if ($this->getActivityListAssociations()->contains($activityListAssociation)) {
            $pos = $this->collActivityListAssociations->search($activityListAssociation);
            $this->collActivityListAssociations->remove($pos);
            if (null === $this->activityListAssociationsScheduledForDeletion) {
                $this->activityListAssociationsScheduledForDeletion = clone $this->collActivityListAssociations;
                $this->activityListAssociationsScheduledForDeletion->clear();
            }
            $this->activityListAssociationsScheduledForDeletion[]= clone $activityListAssociation;
            $activityListAssociation->setActivity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Activity is new, it will return
     * an empty collection; or if this Activity has previously
     * been saved, it will retrieve related ActivityListAssociations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Activity.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivityListAssociation[] List of ChildActivityListAssociation objects
     */
    public function getActivityListAssociationsJoinActivityList(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityListAssociationQuery::create(null, $criteria);
        $query->joinWith('ActivityList', $joinBehavior);

        return $this->getActivityListAssociations($query, $con);
    }

    /**
     * Clears out the collDiscussions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDiscussions()
     */
    public function clearDiscussions()
    {
        $this->collDiscussions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDiscussions collection loaded partially.
     */
    public function resetPartialDiscussions($v = true)
    {
        $this->collDiscussionsPartial = $v;
    }

    /**
     * Initializes the collDiscussions collection.
     *
     * By default this just sets the collDiscussions collection to an empty array (like clearcollDiscussions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDiscussions($overrideExisting = true)
    {
        if (null !== $this->collDiscussions && !$overrideExisting) {
            return;
        }
        $this->collDiscussions = new ObjectCollection();
        $this->collDiscussions->setModel('\Discussion');
    }

    /**
     * Gets an array of ChildDiscussion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildActivity is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDiscussion[] List of ChildDiscussion objects
     * @throws PropelException
     */
    public function getDiscussions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDiscussionsPartial && !$this->isNew();
        if (null === $this->collDiscussions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDiscussions) {
                // return empty collection
                $this->initDiscussions();
            } else {
                $collDiscussions = ChildDiscussionQuery::create(null, $criteria)
                    ->filterByActivity($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDiscussionsPartial && count($collDiscussions)) {
                        $this->initDiscussions(false);

                        foreach ($collDiscussions as $obj) {
                            if (false == $this->collDiscussions->contains($obj)) {
                                $this->collDiscussions->append($obj);
                            }
                        }

                        $this->collDiscussionsPartial = true;
                    }

                    return $collDiscussions;
                }

                if ($partial && $this->collDiscussions) {
                    foreach ($this->collDiscussions as $obj) {
                        if ($obj->isNew()) {
                            $collDiscussions[] = $obj;
                        }
                    }
                }

                $this->collDiscussions = $collDiscussions;
                $this->collDiscussionsPartial = false;
            }
        }

        return $this->collDiscussions;
    }

    /**
     * Sets a collection of ChildDiscussion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $discussions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function setDiscussions(Collection $discussions, ConnectionInterface $con = null)
    {
        /** @var ChildDiscussion[] $discussionsToDelete */
        $discussionsToDelete = $this->getDiscussions(new Criteria(), $con)->diff($discussions);


        $this->discussionsScheduledForDeletion = $discussionsToDelete;

        foreach ($discussionsToDelete as $discussionRemoved) {
            $discussionRemoved->setActivity(null);
        }

        $this->collDiscussions = null;
        foreach ($discussions as $discussion) {
            $this->addDiscussion($discussion);
        }

        $this->collDiscussions = $discussions;
        $this->collDiscussionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Discussion objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Discussion objects.
     * @throws PropelException
     */
    public function countDiscussions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDiscussionsPartial && !$this->isNew();
        if (null === $this->collDiscussions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDiscussions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDiscussions());
            }

            $query = ChildDiscussionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByActivity($this)
                ->count($con);
        }

        return count($this->collDiscussions);
    }

    /**
     * Method called to associate a ChildDiscussion object to this object
     * through the ChildDiscussion foreign key attribute.
     *
     * @param  ChildDiscussion $l ChildDiscussion
     * @return $this|\Activity The current object (for fluent API support)
     */
    public function addDiscussion(ChildDiscussion $l)
    {
        if ($this->collDiscussions === null) {
            $this->initDiscussions();
            $this->collDiscussionsPartial = true;
        }

        if (!$this->collDiscussions->contains($l)) {
            $this->doAddDiscussion($l);
        }

        return $this;
    }

    /**
     * @param ChildDiscussion $discussion The ChildDiscussion object to add.
     */
    protected function doAddDiscussion(ChildDiscussion $discussion)
    {
        $this->collDiscussions[]= $discussion;
        $discussion->setActivity($this);
    }

    /**
     * @param  ChildDiscussion $discussion The ChildDiscussion object to remove.
     * @return $this|ChildActivity The current object (for fluent API support)
     */
    public function removeDiscussion(ChildDiscussion $discussion)
    {
        if ($this->getDiscussions()->contains($discussion)) {
            $pos = $this->collDiscussions->search($discussion);
            $this->collDiscussions->remove($pos);
            if (null === $this->discussionsScheduledForDeletion) {
                $this->discussionsScheduledForDeletion = clone $this->collDiscussions;
                $this->discussionsScheduledForDeletion->clear();
            }
            $this->discussionsScheduledForDeletion[]= clone $discussion;
            $discussion->setActivity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Activity is new, it will return
     * an empty collection; or if this Activity has previously
     * been saved, it will retrieve related Discussions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Activity.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDiscussion[] List of ChildDiscussion objects
     */
    public function getDiscussionsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDiscussionQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getDiscussions($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collActivityCategoryAssociations) {
                foreach ($this->collActivityCategoryAssociations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActivityListAssociations) {
                foreach ($this->collActivityListAssociations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDiscussions) {
                foreach ($this->collDiscussions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collActivityCategoryAssociations = null;
        $this->collActivityListAssociations = null;
        $this->collDiscussions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ActivityTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
