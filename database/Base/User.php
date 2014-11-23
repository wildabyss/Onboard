<?php

namespace Base;

use \ActivityList as ChildActivityList;
use \ActivityListQuery as ChildActivityListQuery;
use \ActivityUserAssociation as ChildActivityUserAssociation;
use \ActivityUserAssociationQuery as ChildActivityUserAssociationQuery;
use \Discussion as ChildDiscussion;
use \DiscussionQuery as ChildDiscussionQuery;
use \DiscussionUserAssociation as ChildDiscussionUserAssociation;
use \DiscussionUserAssociationQuery as ChildDiscussionUserAssociationQuery;
use \User as ChildUser;
use \UserCommunityAssociation as ChildUserCommunityAssociation;
use \UserCommunityAssociationQuery as ChildUserCommunityAssociationQuery;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\UserTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'user' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the fb_id field.
     * @var        string
     */
    protected $fb_id;

    /**
     * The value for the google_id field.
     * @var        string
     */
    protected $google_id;

    /**
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the date_joined field.
     * @var        \DateTime
     */
    protected $date_joined;

    /**
     * The value for the display_name field.
     * @var        string
     */
    protected $display_name;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * @var        ObjectCollection|ChildActivityList[] Collection to store aggregation of ChildActivityList objects.
     */
    protected $collActivityLists;
    protected $collActivityListsPartial;

    /**
     * @var        ObjectCollection|ChildActivityUserAssociation[] Collection to store aggregation of ChildActivityUserAssociation objects.
     */
    protected $collActivityUserAssociations;
    protected $collActivityUserAssociationsPartial;

    /**
     * @var        ObjectCollection|ChildDiscussion[] Collection to store aggregation of ChildDiscussion objects.
     */
    protected $collDiscussions;
    protected $collDiscussionsPartial;

    /**
     * @var        ObjectCollection|ChildDiscussionUserAssociation[] Collection to store aggregation of ChildDiscussionUserAssociation objects.
     */
    protected $collDiscussionUserAssociations;
    protected $collDiscussionUserAssociationsPartial;

    /**
     * @var        ObjectCollection|ChildUserCommunityAssociation[] Collection to store aggregation of ChildUserCommunityAssociation objects.
     */
    protected $collUserCommunityAssociationsRelatedByUserIdLeft;
    protected $collUserCommunityAssociationsRelatedByUserIdLeftPartial;

    /**
     * @var        ObjectCollection|ChildUserCommunityAssociation[] Collection to store aggregation of ChildUserCommunityAssociation objects.
     */
    protected $collUserCommunityAssociationsRelatedByUserIdRight;
    protected $collUserCommunityAssociationsRelatedByUserIdRightPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivityList[]
     */
    protected $activityListsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildActivityUserAssociation[]
     */
    protected $activityUserAssociationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDiscussion[]
     */
    protected $discussionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDiscussionUserAssociation[]
     */
    protected $discussionUserAssociationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserCommunityAssociation[]
     */
    protected $userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserCommunityAssociation[]
     */
    protected $userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [fb_id] column value.
     *
     * @return string
     */
    public function getFbId()
    {
        return $this->fb_id;
    }

    /**
     * Get the [google_id] column value.
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [optionally formatted] temporal [date_joined] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDateJoined($format = NULL)
    {
        if ($format === null) {
            return $this->date_joined;
        } else {
            return $this->date_joined instanceof \DateTime ? $this->date_joined->format($format) : null;
        }
    }

    /**
     * Get the [display_name] column value.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [fb_id] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setFbId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fb_id !== $v) {
            $this->fb_id = $v;
            $this->modifiedColumns[UserTableMap::COL_FB_ID] = true;
        }

        return $this;
    } // setFbId()

    /**
     * Set the value of [google_id] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setGoogleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->google_id !== $v) {
            $this->google_id = $v;
            $this->modifiedColumns[UserTableMap::COL_GOOGLE_ID] = true;
        }

        return $this;
    } // setGoogleId()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[UserTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Sets the value of [date_joined] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setDateJoined($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_joined !== null || $dt !== null) {
            if ($dt !== $this->date_joined) {
                $this->date_joined = $dt;
                $this->modifiedColumns[UserTableMap::COL_DATE_JOINED] = true;
            }
        } // if either are not null

        return $this;
    } // setDateJoined()

    /**
     * Set the value of [display_name] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setDisplayName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->display_name !== $v) {
            $this->display_name = $v;
            $this->modifiedColumns[UserTableMap::COL_DISPLAY_NAME] = true;
        }

        return $this;
    } // setDisplayName()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[UserTableMap::COL_PHONE] = true;
        }

        return $this;
    } // setPhone()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('FbId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fb_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('GoogleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->google_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('DateJoined', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date_joined = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('DisplayName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->display_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phone = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collActivityLists = null;

            $this->collActivityUserAssociations = null;

            $this->collDiscussions = null;

            $this->collDiscussionUserAssociations = null;

            $this->collUserCommunityAssociationsRelatedByUserIdLeft = null;

            $this->collUserCommunityAssociationsRelatedByUserIdRight = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->activityListsScheduledForDeletion !== null) {
                if (!$this->activityListsScheduledForDeletion->isEmpty()) {
                    \ActivityListQuery::create()
                        ->filterByPrimaryKeys($this->activityListsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activityListsScheduledForDeletion = null;
                }
            }

            if ($this->collActivityLists !== null) {
                foreach ($this->collActivityLists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->activityUserAssociationsScheduledForDeletion !== null) {
                if (!$this->activityUserAssociationsScheduledForDeletion->isEmpty()) {
                    \ActivityUserAssociationQuery::create()
                        ->filterByPrimaryKeys($this->activityUserAssociationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->activityUserAssociationsScheduledForDeletion = null;
                }
            }

            if ($this->collActivityUserAssociations !== null) {
                foreach ($this->collActivityUserAssociations as $referrerFK) {
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

            if ($this->discussionUserAssociationsScheduledForDeletion !== null) {
                if (!$this->discussionUserAssociationsScheduledForDeletion->isEmpty()) {
                    \DiscussionUserAssociationQuery::create()
                        ->filterByPrimaryKeys($this->discussionUserAssociationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->discussionUserAssociationsScheduledForDeletion = null;
                }
            }

            if ($this->collDiscussionUserAssociations !== null) {
                foreach ($this->collDiscussionUserAssociations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion !== null) {
                if (!$this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion->isEmpty()) {
                    \UserCommunityAssociationQuery::create()
                        ->filterByPrimaryKeys($this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion = null;
                }
            }

            if ($this->collUserCommunityAssociationsRelatedByUserIdLeft !== null) {
                foreach ($this->collUserCommunityAssociationsRelatedByUserIdLeft as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion !== null) {
                if (!$this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion->isEmpty()) {
                    \UserCommunityAssociationQuery::create()
                        ->filterByPrimaryKeys($this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion = null;
                }
            }

            if ($this->collUserCommunityAssociationsRelatedByUserIdRight !== null) {
                foreach ($this->collUserCommunityAssociationsRelatedByUserIdRight as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_FB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'fb_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_GOOGLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'google_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'status';
        }
        if ($this->isColumnModified(UserTableMap::COL_DATE_JOINED)) {
            $modifiedColumns[':p' . $index++]  = 'date_joined';
        }
        if ($this->isColumnModified(UserTableMap::COL_DISPLAY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'display_name';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'phone';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'fb_id':
                        $stmt->bindValue($identifier, $this->fb_id, PDO::PARAM_STR);
                        break;
                    case 'google_id':
                        $stmt->bindValue($identifier, $this->google_id, PDO::PARAM_STR);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'date_joined':
                        $stmt->bindValue($identifier, $this->date_joined ? $this->date_joined->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'display_name':
                        $stmt->bindValue($identifier, $this->display_name, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'phone':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFbId();
                break;
            case 2:
                return $this->getGoogleId();
                break;
            case 3:
                return $this->getStatus();
                break;
            case 4:
                return $this->getDateJoined();
                break;
            case 5:
                return $this->getDisplayName();
                break;
            case 6:
                return $this->getEmail();
                break;
            case 7:
                return $this->getPhone();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFbId(),
            $keys[2] => $this->getGoogleId(),
            $keys[3] => $this->getStatus(),
            $keys[4] => $this->getDateJoined(),
            $keys[5] => $this->getDisplayName(),
            $keys[6] => $this->getEmail(),
            $keys[7] => $this->getPhone(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collActivityLists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activityLists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'activity_lists';
                        break;
                    default:
                        $key = 'ActivityLists';
                }

                $result[$key] = $this->collActivityLists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collActivityUserAssociations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activityUserAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'activity_user_assocs';
                        break;
                    default:
                        $key = 'ActivityUserAssociations';
                }

                $result[$key] = $this->collActivityUserAssociations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collDiscussionUserAssociations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'discussionUserAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'discussion_user_assocs';
                        break;
                    default:
                        $key = 'DiscussionUserAssociations';
                }

                $result[$key] = $this->collDiscussionUserAssociations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserCommunityAssociationsRelatedByUserIdLeft) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userCommunityAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_community_assocs';
                        break;
                    default:
                        $key = 'UserCommunityAssociations';
                }

                $result[$key] = $this->collUserCommunityAssociationsRelatedByUserIdLeft->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserCommunityAssociationsRelatedByUserIdRight) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userCommunityAssociations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_community_assocs';
                        break;
                    default:
                        $key = 'UserCommunityAssociations';
                }

                $result[$key] = $this->collUserCommunityAssociationsRelatedByUserIdRight->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFbId($value);
                break;
            case 2:
                $this->setGoogleId($value);
                break;
            case 3:
                $this->setStatus($value);
                break;
            case 4:
                $this->setDateJoined($value);
                break;
            case 5:
                $this->setDisplayName($value);
                break;
            case 6:
                $this->setEmail($value);
                break;
            case 7:
                $this->setPhone($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFbId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setGoogleId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setStatus($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDateJoined($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDisplayName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEmail($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPhone($arr[$keys[7]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_FB_ID)) {
            $criteria->add(UserTableMap::COL_FB_ID, $this->fb_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_GOOGLE_ID)) {
            $criteria->add(UserTableMap::COL_GOOGLE_ID, $this->google_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_STATUS)) {
            $criteria->add(UserTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(UserTableMap::COL_DATE_JOINED)) {
            $criteria->add(UserTableMap::COL_DATE_JOINED, $this->date_joined);
        }
        if ($this->isColumnModified(UserTableMap::COL_DISPLAY_NAME)) {
            $criteria->add(UserTableMap::COL_DISPLAY_NAME, $this->display_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONE)) {
            $criteria->add(UserTableMap::COL_PHONE, $this->phone);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFbId($this->getFbId());
        $copyObj->setGoogleId($this->getGoogleId());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setDateJoined($this->getDateJoined());
        $copyObj->setDisplayName($this->getDisplayName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPhone($this->getPhone());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getActivityLists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityList($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getActivityUserAssociations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addActivityUserAssociation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDiscussions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDiscussion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDiscussionUserAssociations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDiscussionUserAssociation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserCommunityAssociationsRelatedByUserIdLeft() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserCommunityAssociationRelatedByUserIdLeft($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserCommunityAssociationsRelatedByUserIdRight() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserCommunityAssociationRelatedByUserIdRight($relObj->copy($deepCopy));
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
     * @return \User Clone of current object.
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
        if ('ActivityList' == $relationName) {
            return $this->initActivityLists();
        }
        if ('ActivityUserAssociation' == $relationName) {
            return $this->initActivityUserAssociations();
        }
        if ('Discussion' == $relationName) {
            return $this->initDiscussions();
        }
        if ('DiscussionUserAssociation' == $relationName) {
            return $this->initDiscussionUserAssociations();
        }
        if ('UserCommunityAssociationRelatedByUserIdLeft' == $relationName) {
            return $this->initUserCommunityAssociationsRelatedByUserIdLeft();
        }
        if ('UserCommunityAssociationRelatedByUserIdRight' == $relationName) {
            return $this->initUserCommunityAssociationsRelatedByUserIdRight();
        }
    }

    /**
     * Clears out the collActivityLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivityLists()
     */
    public function clearActivityLists()
    {
        $this->collActivityLists = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivityLists collection loaded partially.
     */
    public function resetPartialActivityLists($v = true)
    {
        $this->collActivityListsPartial = $v;
    }

    /**
     * Initializes the collActivityLists collection.
     *
     * By default this just sets the collActivityLists collection to an empty array (like clearcollActivityLists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivityLists($overrideExisting = true)
    {
        if (null !== $this->collActivityLists && !$overrideExisting) {
            return;
        }
        $this->collActivityLists = new ObjectCollection();
        $this->collActivityLists->setModel('\ActivityList');
    }

    /**
     * Gets an array of ChildActivityList objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivityList[] List of ChildActivityList objects
     * @throws PropelException
     */
    public function getActivityLists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityListsPartial && !$this->isNew();
        if (null === $this->collActivityLists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivityLists) {
                // return empty collection
                $this->initActivityLists();
            } else {
                $collActivityLists = ChildActivityListQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivityListsPartial && count($collActivityLists)) {
                        $this->initActivityLists(false);

                        foreach ($collActivityLists as $obj) {
                            if (false == $this->collActivityLists->contains($obj)) {
                                $this->collActivityLists->append($obj);
                            }
                        }

                        $this->collActivityListsPartial = true;
                    }

                    return $collActivityLists;
                }

                if ($partial && $this->collActivityLists) {
                    foreach ($this->collActivityLists as $obj) {
                        if ($obj->isNew()) {
                            $collActivityLists[] = $obj;
                        }
                    }
                }

                $this->collActivityLists = $collActivityLists;
                $this->collActivityListsPartial = false;
            }
        }

        return $this->collActivityLists;
    }

    /**
     * Sets a collection of ChildActivityList objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activityLists A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setActivityLists(Collection $activityLists, ConnectionInterface $con = null)
    {
        /** @var ChildActivityList[] $activityListsToDelete */
        $activityListsToDelete = $this->getActivityLists(new Criteria(), $con)->diff($activityLists);


        $this->activityListsScheduledForDeletion = $activityListsToDelete;

        foreach ($activityListsToDelete as $activityListRemoved) {
            $activityListRemoved->setUser(null);
        }

        $this->collActivityLists = null;
        foreach ($activityLists as $activityList) {
            $this->addActivityList($activityList);
        }

        $this->collActivityLists = $activityLists;
        $this->collActivityListsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ActivityList objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ActivityList objects.
     * @throws PropelException
     */
    public function countActivityLists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityListsPartial && !$this->isNew();
        if (null === $this->collActivityLists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivityLists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivityLists());
            }

            $query = ChildActivityListQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collActivityLists);
    }

    /**
     * Method called to associate a ChildActivityList object to this object
     * through the ChildActivityList foreign key attribute.
     *
     * @param  ChildActivityList $l ChildActivityList
     * @return $this|\User The current object (for fluent API support)
     */
    public function addActivityList(ChildActivityList $l)
    {
        if ($this->collActivityLists === null) {
            $this->initActivityLists();
            $this->collActivityListsPartial = true;
        }

        if (!$this->collActivityLists->contains($l)) {
            $this->doAddActivityList($l);
        }

        return $this;
    }

    /**
     * @param ChildActivityList $activityList The ChildActivityList object to add.
     */
    protected function doAddActivityList(ChildActivityList $activityList)
    {
        $this->collActivityLists[]= $activityList;
        $activityList->setUser($this);
    }

    /**
     * @param  ChildActivityList $activityList The ChildActivityList object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeActivityList(ChildActivityList $activityList)
    {
        if ($this->getActivityLists()->contains($activityList)) {
            $pos = $this->collActivityLists->search($activityList);
            $this->collActivityLists->remove($pos);
            if (null === $this->activityListsScheduledForDeletion) {
                $this->activityListsScheduledForDeletion = clone $this->collActivityLists;
                $this->activityListsScheduledForDeletion->clear();
            }
            $this->activityListsScheduledForDeletion[]= clone $activityList;
            $activityList->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collActivityUserAssociations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addActivityUserAssociations()
     */
    public function clearActivityUserAssociations()
    {
        $this->collActivityUserAssociations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collActivityUserAssociations collection loaded partially.
     */
    public function resetPartialActivityUserAssociations($v = true)
    {
        $this->collActivityUserAssociationsPartial = $v;
    }

    /**
     * Initializes the collActivityUserAssociations collection.
     *
     * By default this just sets the collActivityUserAssociations collection to an empty array (like clearcollActivityUserAssociations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initActivityUserAssociations($overrideExisting = true)
    {
        if (null !== $this->collActivityUserAssociations && !$overrideExisting) {
            return;
        }
        $this->collActivityUserAssociations = new ObjectCollection();
        $this->collActivityUserAssociations->setModel('\ActivityUserAssociation');
    }

    /**
     * Gets an array of ChildActivityUserAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildActivityUserAssociation[] List of ChildActivityUserAssociation objects
     * @throws PropelException
     */
    public function getActivityUserAssociations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityUserAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityUserAssociations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collActivityUserAssociations) {
                // return empty collection
                $this->initActivityUserAssociations();
            } else {
                $collActivityUserAssociations = ChildActivityUserAssociationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collActivityUserAssociationsPartial && count($collActivityUserAssociations)) {
                        $this->initActivityUserAssociations(false);

                        foreach ($collActivityUserAssociations as $obj) {
                            if (false == $this->collActivityUserAssociations->contains($obj)) {
                                $this->collActivityUserAssociations->append($obj);
                            }
                        }

                        $this->collActivityUserAssociationsPartial = true;
                    }

                    return $collActivityUserAssociations;
                }

                if ($partial && $this->collActivityUserAssociations) {
                    foreach ($this->collActivityUserAssociations as $obj) {
                        if ($obj->isNew()) {
                            $collActivityUserAssociations[] = $obj;
                        }
                    }
                }

                $this->collActivityUserAssociations = $collActivityUserAssociations;
                $this->collActivityUserAssociationsPartial = false;
            }
        }

        return $this->collActivityUserAssociations;
    }

    /**
     * Sets a collection of ChildActivityUserAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $activityUserAssociations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setActivityUserAssociations(Collection $activityUserAssociations, ConnectionInterface $con = null)
    {
        /** @var ChildActivityUserAssociation[] $activityUserAssociationsToDelete */
        $activityUserAssociationsToDelete = $this->getActivityUserAssociations(new Criteria(), $con)->diff($activityUserAssociations);


        $this->activityUserAssociationsScheduledForDeletion = $activityUserAssociationsToDelete;

        foreach ($activityUserAssociationsToDelete as $activityUserAssociationRemoved) {
            $activityUserAssociationRemoved->setUser(null);
        }

        $this->collActivityUserAssociations = null;
        foreach ($activityUserAssociations as $activityUserAssociation) {
            $this->addActivityUserAssociation($activityUserAssociation);
        }

        $this->collActivityUserAssociations = $activityUserAssociations;
        $this->collActivityUserAssociationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ActivityUserAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ActivityUserAssociation objects.
     * @throws PropelException
     */
    public function countActivityUserAssociations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collActivityUserAssociationsPartial && !$this->isNew();
        if (null === $this->collActivityUserAssociations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collActivityUserAssociations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getActivityUserAssociations());
            }

            $query = ChildActivityUserAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collActivityUserAssociations);
    }

    /**
     * Method called to associate a ChildActivityUserAssociation object to this object
     * through the ChildActivityUserAssociation foreign key attribute.
     *
     * @param  ChildActivityUserAssociation $l ChildActivityUserAssociation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addActivityUserAssociation(ChildActivityUserAssociation $l)
    {
        if ($this->collActivityUserAssociations === null) {
            $this->initActivityUserAssociations();
            $this->collActivityUserAssociationsPartial = true;
        }

        if (!$this->collActivityUserAssociations->contains($l)) {
            $this->doAddActivityUserAssociation($l);
        }

        return $this;
    }

    /**
     * @param ChildActivityUserAssociation $activityUserAssociation The ChildActivityUserAssociation object to add.
     */
    protected function doAddActivityUserAssociation(ChildActivityUserAssociation $activityUserAssociation)
    {
        $this->collActivityUserAssociations[]= $activityUserAssociation;
        $activityUserAssociation->setUser($this);
    }

    /**
     * @param  ChildActivityUserAssociation $activityUserAssociation The ChildActivityUserAssociation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeActivityUserAssociation(ChildActivityUserAssociation $activityUserAssociation)
    {
        if ($this->getActivityUserAssociations()->contains($activityUserAssociation)) {
            $pos = $this->collActivityUserAssociations->search($activityUserAssociation);
            $this->collActivityUserAssociations->remove($pos);
            if (null === $this->activityUserAssociationsScheduledForDeletion) {
                $this->activityUserAssociationsScheduledForDeletion = clone $this->collActivityUserAssociations;
                $this->activityUserAssociationsScheduledForDeletion->clear();
            }
            $this->activityUserAssociationsScheduledForDeletion[]= clone $activityUserAssociation;
            $activityUserAssociation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ActivityUserAssociations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivityUserAssociation[] List of ChildActivityUserAssociation objects
     */
    public function getActivityUserAssociationsJoinActivity(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityUserAssociationQuery::create(null, $criteria);
        $query->joinWith('Activity', $joinBehavior);

        return $this->getActivityUserAssociations($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ActivityUserAssociations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildActivityUserAssociation[] List of ChildActivityUserAssociation objects
     */
    public function getActivityUserAssociationsJoinActivityList(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildActivityUserAssociationQuery::create(null, $criteria);
        $query->joinWith('ActivityList', $joinBehavior);

        return $this->getActivityUserAssociations($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setDiscussions(Collection $discussions, ConnectionInterface $con = null)
    {
        /** @var ChildDiscussion[] $discussionsToDelete */
        $discussionsToDelete = $this->getDiscussions(new Criteria(), $con)->diff($discussions);


        $this->discussionsScheduledForDeletion = $discussionsToDelete;

        foreach ($discussionsToDelete as $discussionRemoved) {
            $discussionRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collDiscussions);
    }

    /**
     * Method called to associate a ChildDiscussion object to this object
     * through the ChildDiscussion foreign key attribute.
     *
     * @param  ChildDiscussion $l ChildDiscussion
     * @return $this|\User The current object (for fluent API support)
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
        $discussion->setUser($this);
    }

    /**
     * @param  ChildDiscussion $discussion The ChildDiscussion object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $discussion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Discussions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDiscussion[] List of ChildDiscussion objects
     */
    public function getDiscussionsJoinActivity(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDiscussionQuery::create(null, $criteria);
        $query->joinWith('Activity', $joinBehavior);

        return $this->getDiscussions($query, $con);
    }

    /**
     * Clears out the collDiscussionUserAssociations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDiscussionUserAssociations()
     */
    public function clearDiscussionUserAssociations()
    {
        $this->collDiscussionUserAssociations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDiscussionUserAssociations collection loaded partially.
     */
    public function resetPartialDiscussionUserAssociations($v = true)
    {
        $this->collDiscussionUserAssociationsPartial = $v;
    }

    /**
     * Initializes the collDiscussionUserAssociations collection.
     *
     * By default this just sets the collDiscussionUserAssociations collection to an empty array (like clearcollDiscussionUserAssociations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDiscussionUserAssociations($overrideExisting = true)
    {
        if (null !== $this->collDiscussionUserAssociations && !$overrideExisting) {
            return;
        }
        $this->collDiscussionUserAssociations = new ObjectCollection();
        $this->collDiscussionUserAssociations->setModel('\DiscussionUserAssociation');
    }

    /**
     * Gets an array of ChildDiscussionUserAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDiscussionUserAssociation[] List of ChildDiscussionUserAssociation objects
     * @throws PropelException
     */
    public function getDiscussionUserAssociations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDiscussionUserAssociationsPartial && !$this->isNew();
        if (null === $this->collDiscussionUserAssociations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDiscussionUserAssociations) {
                // return empty collection
                $this->initDiscussionUserAssociations();
            } else {
                $collDiscussionUserAssociations = ChildDiscussionUserAssociationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDiscussionUserAssociationsPartial && count($collDiscussionUserAssociations)) {
                        $this->initDiscussionUserAssociations(false);

                        foreach ($collDiscussionUserAssociations as $obj) {
                            if (false == $this->collDiscussionUserAssociations->contains($obj)) {
                                $this->collDiscussionUserAssociations->append($obj);
                            }
                        }

                        $this->collDiscussionUserAssociationsPartial = true;
                    }

                    return $collDiscussionUserAssociations;
                }

                if ($partial && $this->collDiscussionUserAssociations) {
                    foreach ($this->collDiscussionUserAssociations as $obj) {
                        if ($obj->isNew()) {
                            $collDiscussionUserAssociations[] = $obj;
                        }
                    }
                }

                $this->collDiscussionUserAssociations = $collDiscussionUserAssociations;
                $this->collDiscussionUserAssociationsPartial = false;
            }
        }

        return $this->collDiscussionUserAssociations;
    }

    /**
     * Sets a collection of ChildDiscussionUserAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $discussionUserAssociations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setDiscussionUserAssociations(Collection $discussionUserAssociations, ConnectionInterface $con = null)
    {
        /** @var ChildDiscussionUserAssociation[] $discussionUserAssociationsToDelete */
        $discussionUserAssociationsToDelete = $this->getDiscussionUserAssociations(new Criteria(), $con)->diff($discussionUserAssociations);


        $this->discussionUserAssociationsScheduledForDeletion = $discussionUserAssociationsToDelete;

        foreach ($discussionUserAssociationsToDelete as $discussionUserAssociationRemoved) {
            $discussionUserAssociationRemoved->setUser(null);
        }

        $this->collDiscussionUserAssociations = null;
        foreach ($discussionUserAssociations as $discussionUserAssociation) {
            $this->addDiscussionUserAssociation($discussionUserAssociation);
        }

        $this->collDiscussionUserAssociations = $discussionUserAssociations;
        $this->collDiscussionUserAssociationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DiscussionUserAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DiscussionUserAssociation objects.
     * @throws PropelException
     */
    public function countDiscussionUserAssociations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDiscussionUserAssociationsPartial && !$this->isNew();
        if (null === $this->collDiscussionUserAssociations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDiscussionUserAssociations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDiscussionUserAssociations());
            }

            $query = ChildDiscussionUserAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collDiscussionUserAssociations);
    }

    /**
     * Method called to associate a ChildDiscussionUserAssociation object to this object
     * through the ChildDiscussionUserAssociation foreign key attribute.
     *
     * @param  ChildDiscussionUserAssociation $l ChildDiscussionUserAssociation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addDiscussionUserAssociation(ChildDiscussionUserAssociation $l)
    {
        if ($this->collDiscussionUserAssociations === null) {
            $this->initDiscussionUserAssociations();
            $this->collDiscussionUserAssociationsPartial = true;
        }

        if (!$this->collDiscussionUserAssociations->contains($l)) {
            $this->doAddDiscussionUserAssociation($l);
        }

        return $this;
    }

    /**
     * @param ChildDiscussionUserAssociation $discussionUserAssociation The ChildDiscussionUserAssociation object to add.
     */
    protected function doAddDiscussionUserAssociation(ChildDiscussionUserAssociation $discussionUserAssociation)
    {
        $this->collDiscussionUserAssociations[]= $discussionUserAssociation;
        $discussionUserAssociation->setUser($this);
    }

    /**
     * @param  ChildDiscussionUserAssociation $discussionUserAssociation The ChildDiscussionUserAssociation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeDiscussionUserAssociation(ChildDiscussionUserAssociation $discussionUserAssociation)
    {
        if ($this->getDiscussionUserAssociations()->contains($discussionUserAssociation)) {
            $pos = $this->collDiscussionUserAssociations->search($discussionUserAssociation);
            $this->collDiscussionUserAssociations->remove($pos);
            if (null === $this->discussionUserAssociationsScheduledForDeletion) {
                $this->discussionUserAssociationsScheduledForDeletion = clone $this->collDiscussionUserAssociations;
                $this->discussionUserAssociationsScheduledForDeletion->clear();
            }
            $this->discussionUserAssociationsScheduledForDeletion[]= clone $discussionUserAssociation;
            $discussionUserAssociation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related DiscussionUserAssociations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDiscussionUserAssociation[] List of ChildDiscussionUserAssociation objects
     */
    public function getDiscussionUserAssociationsJoinDiscussion(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDiscussionUserAssociationQuery::create(null, $criteria);
        $query->joinWith('Discussion', $joinBehavior);

        return $this->getDiscussionUserAssociations($query, $con);
    }

    /**
     * Clears out the collUserCommunityAssociationsRelatedByUserIdLeft collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserCommunityAssociationsRelatedByUserIdLeft()
     */
    public function clearUserCommunityAssociationsRelatedByUserIdLeft()
    {
        $this->collUserCommunityAssociationsRelatedByUserIdLeft = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserCommunityAssociationsRelatedByUserIdLeft collection loaded partially.
     */
    public function resetPartialUserCommunityAssociationsRelatedByUserIdLeft($v = true)
    {
        $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial = $v;
    }

    /**
     * Initializes the collUserCommunityAssociationsRelatedByUserIdLeft collection.
     *
     * By default this just sets the collUserCommunityAssociationsRelatedByUserIdLeft collection to an empty array (like clearcollUserCommunityAssociationsRelatedByUserIdLeft());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserCommunityAssociationsRelatedByUserIdLeft($overrideExisting = true)
    {
        if (null !== $this->collUserCommunityAssociationsRelatedByUserIdLeft && !$overrideExisting) {
            return;
        }
        $this->collUserCommunityAssociationsRelatedByUserIdLeft = new ObjectCollection();
        $this->collUserCommunityAssociationsRelatedByUserIdLeft->setModel('\UserCommunityAssociation');
    }

    /**
     * Gets an array of ChildUserCommunityAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserCommunityAssociation[] List of ChildUserCommunityAssociation objects
     * @throws PropelException
     */
    public function getUserCommunityAssociationsRelatedByUserIdLeft(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial && !$this->isNew();
        if (null === $this->collUserCommunityAssociationsRelatedByUserIdLeft || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserCommunityAssociationsRelatedByUserIdLeft) {
                // return empty collection
                $this->initUserCommunityAssociationsRelatedByUserIdLeft();
            } else {
                $collUserCommunityAssociationsRelatedByUserIdLeft = ChildUserCommunityAssociationQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdLeft($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial && count($collUserCommunityAssociationsRelatedByUserIdLeft)) {
                        $this->initUserCommunityAssociationsRelatedByUserIdLeft(false);

                        foreach ($collUserCommunityAssociationsRelatedByUserIdLeft as $obj) {
                            if (false == $this->collUserCommunityAssociationsRelatedByUserIdLeft->contains($obj)) {
                                $this->collUserCommunityAssociationsRelatedByUserIdLeft->append($obj);
                            }
                        }

                        $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial = true;
                    }

                    return $collUserCommunityAssociationsRelatedByUserIdLeft;
                }

                if ($partial && $this->collUserCommunityAssociationsRelatedByUserIdLeft) {
                    foreach ($this->collUserCommunityAssociationsRelatedByUserIdLeft as $obj) {
                        if ($obj->isNew()) {
                            $collUserCommunityAssociationsRelatedByUserIdLeft[] = $obj;
                        }
                    }
                }

                $this->collUserCommunityAssociationsRelatedByUserIdLeft = $collUserCommunityAssociationsRelatedByUserIdLeft;
                $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial = false;
            }
        }

        return $this->collUserCommunityAssociationsRelatedByUserIdLeft;
    }

    /**
     * Sets a collection of ChildUserCommunityAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userCommunityAssociationsRelatedByUserIdLeft A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserCommunityAssociationsRelatedByUserIdLeft(Collection $userCommunityAssociationsRelatedByUserIdLeft, ConnectionInterface $con = null)
    {
        /** @var ChildUserCommunityAssociation[] $userCommunityAssociationsRelatedByUserIdLeftToDelete */
        $userCommunityAssociationsRelatedByUserIdLeftToDelete = $this->getUserCommunityAssociationsRelatedByUserIdLeft(new Criteria(), $con)->diff($userCommunityAssociationsRelatedByUserIdLeft);


        $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion = $userCommunityAssociationsRelatedByUserIdLeftToDelete;

        foreach ($userCommunityAssociationsRelatedByUserIdLeftToDelete as $userCommunityAssociationRelatedByUserIdLeftRemoved) {
            $userCommunityAssociationRelatedByUserIdLeftRemoved->setUserRelatedByUserIdLeft(null);
        }

        $this->collUserCommunityAssociationsRelatedByUserIdLeft = null;
        foreach ($userCommunityAssociationsRelatedByUserIdLeft as $userCommunityAssociationRelatedByUserIdLeft) {
            $this->addUserCommunityAssociationRelatedByUserIdLeft($userCommunityAssociationRelatedByUserIdLeft);
        }

        $this->collUserCommunityAssociationsRelatedByUserIdLeft = $userCommunityAssociationsRelatedByUserIdLeft;
        $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserCommunityAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserCommunityAssociation objects.
     * @throws PropelException
     */
    public function countUserCommunityAssociationsRelatedByUserIdLeft(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial && !$this->isNew();
        if (null === $this->collUserCommunityAssociationsRelatedByUserIdLeft || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserCommunityAssociationsRelatedByUserIdLeft) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserCommunityAssociationsRelatedByUserIdLeft());
            }

            $query = ChildUserCommunityAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdLeft($this)
                ->count($con);
        }

        return count($this->collUserCommunityAssociationsRelatedByUserIdLeft);
    }

    /**
     * Method called to associate a ChildUserCommunityAssociation object to this object
     * through the ChildUserCommunityAssociation foreign key attribute.
     *
     * @param  ChildUserCommunityAssociation $l ChildUserCommunityAssociation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addUserCommunityAssociationRelatedByUserIdLeft(ChildUserCommunityAssociation $l)
    {
        if ($this->collUserCommunityAssociationsRelatedByUserIdLeft === null) {
            $this->initUserCommunityAssociationsRelatedByUserIdLeft();
            $this->collUserCommunityAssociationsRelatedByUserIdLeftPartial = true;
        }

        if (!$this->collUserCommunityAssociationsRelatedByUserIdLeft->contains($l)) {
            $this->doAddUserCommunityAssociationRelatedByUserIdLeft($l);
        }

        return $this;
    }

    /**
     * @param ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdLeft The ChildUserCommunityAssociation object to add.
     */
    protected function doAddUserCommunityAssociationRelatedByUserIdLeft(ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdLeft)
    {
        $this->collUserCommunityAssociationsRelatedByUserIdLeft[]= $userCommunityAssociationRelatedByUserIdLeft;
        $userCommunityAssociationRelatedByUserIdLeft->setUserRelatedByUserIdLeft($this);
    }

    /**
     * @param  ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdLeft The ChildUserCommunityAssociation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserCommunityAssociationRelatedByUserIdLeft(ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdLeft)
    {
        if ($this->getUserCommunityAssociationsRelatedByUserIdLeft()->contains($userCommunityAssociationRelatedByUserIdLeft)) {
            $pos = $this->collUserCommunityAssociationsRelatedByUserIdLeft->search($userCommunityAssociationRelatedByUserIdLeft);
            $this->collUserCommunityAssociationsRelatedByUserIdLeft->remove($pos);
            if (null === $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion) {
                $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion = clone $this->collUserCommunityAssociationsRelatedByUserIdLeft;
                $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion->clear();
            }
            $this->userCommunityAssociationsRelatedByUserIdLeftScheduledForDeletion[]= clone $userCommunityAssociationRelatedByUserIdLeft;
            $userCommunityAssociationRelatedByUserIdLeft->setUserRelatedByUserIdLeft(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserCommunityAssociationsRelatedByUserIdRight collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserCommunityAssociationsRelatedByUserIdRight()
     */
    public function clearUserCommunityAssociationsRelatedByUserIdRight()
    {
        $this->collUserCommunityAssociationsRelatedByUserIdRight = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserCommunityAssociationsRelatedByUserIdRight collection loaded partially.
     */
    public function resetPartialUserCommunityAssociationsRelatedByUserIdRight($v = true)
    {
        $this->collUserCommunityAssociationsRelatedByUserIdRightPartial = $v;
    }

    /**
     * Initializes the collUserCommunityAssociationsRelatedByUserIdRight collection.
     *
     * By default this just sets the collUserCommunityAssociationsRelatedByUserIdRight collection to an empty array (like clearcollUserCommunityAssociationsRelatedByUserIdRight());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserCommunityAssociationsRelatedByUserIdRight($overrideExisting = true)
    {
        if (null !== $this->collUserCommunityAssociationsRelatedByUserIdRight && !$overrideExisting) {
            return;
        }
        $this->collUserCommunityAssociationsRelatedByUserIdRight = new ObjectCollection();
        $this->collUserCommunityAssociationsRelatedByUserIdRight->setModel('\UserCommunityAssociation');
    }

    /**
     * Gets an array of ChildUserCommunityAssociation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserCommunityAssociation[] List of ChildUserCommunityAssociation objects
     * @throws PropelException
     */
    public function getUserCommunityAssociationsRelatedByUserIdRight(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserCommunityAssociationsRelatedByUserIdRightPartial && !$this->isNew();
        if (null === $this->collUserCommunityAssociationsRelatedByUserIdRight || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserCommunityAssociationsRelatedByUserIdRight) {
                // return empty collection
                $this->initUserCommunityAssociationsRelatedByUserIdRight();
            } else {
                $collUserCommunityAssociationsRelatedByUserIdRight = ChildUserCommunityAssociationQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserIdRight($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserCommunityAssociationsRelatedByUserIdRightPartial && count($collUserCommunityAssociationsRelatedByUserIdRight)) {
                        $this->initUserCommunityAssociationsRelatedByUserIdRight(false);

                        foreach ($collUserCommunityAssociationsRelatedByUserIdRight as $obj) {
                            if (false == $this->collUserCommunityAssociationsRelatedByUserIdRight->contains($obj)) {
                                $this->collUserCommunityAssociationsRelatedByUserIdRight->append($obj);
                            }
                        }

                        $this->collUserCommunityAssociationsRelatedByUserIdRightPartial = true;
                    }

                    return $collUserCommunityAssociationsRelatedByUserIdRight;
                }

                if ($partial && $this->collUserCommunityAssociationsRelatedByUserIdRight) {
                    foreach ($this->collUserCommunityAssociationsRelatedByUserIdRight as $obj) {
                        if ($obj->isNew()) {
                            $collUserCommunityAssociationsRelatedByUserIdRight[] = $obj;
                        }
                    }
                }

                $this->collUserCommunityAssociationsRelatedByUserIdRight = $collUserCommunityAssociationsRelatedByUserIdRight;
                $this->collUserCommunityAssociationsRelatedByUserIdRightPartial = false;
            }
        }

        return $this->collUserCommunityAssociationsRelatedByUserIdRight;
    }

    /**
     * Sets a collection of ChildUserCommunityAssociation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userCommunityAssociationsRelatedByUserIdRight A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setUserCommunityAssociationsRelatedByUserIdRight(Collection $userCommunityAssociationsRelatedByUserIdRight, ConnectionInterface $con = null)
    {
        /** @var ChildUserCommunityAssociation[] $userCommunityAssociationsRelatedByUserIdRightToDelete */
        $userCommunityAssociationsRelatedByUserIdRightToDelete = $this->getUserCommunityAssociationsRelatedByUserIdRight(new Criteria(), $con)->diff($userCommunityAssociationsRelatedByUserIdRight);


        $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion = $userCommunityAssociationsRelatedByUserIdRightToDelete;

        foreach ($userCommunityAssociationsRelatedByUserIdRightToDelete as $userCommunityAssociationRelatedByUserIdRightRemoved) {
            $userCommunityAssociationRelatedByUserIdRightRemoved->setUserRelatedByUserIdRight(null);
        }

        $this->collUserCommunityAssociationsRelatedByUserIdRight = null;
        foreach ($userCommunityAssociationsRelatedByUserIdRight as $userCommunityAssociationRelatedByUserIdRight) {
            $this->addUserCommunityAssociationRelatedByUserIdRight($userCommunityAssociationRelatedByUserIdRight);
        }

        $this->collUserCommunityAssociationsRelatedByUserIdRight = $userCommunityAssociationsRelatedByUserIdRight;
        $this->collUserCommunityAssociationsRelatedByUserIdRightPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserCommunityAssociation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserCommunityAssociation objects.
     * @throws PropelException
     */
    public function countUserCommunityAssociationsRelatedByUserIdRight(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserCommunityAssociationsRelatedByUserIdRightPartial && !$this->isNew();
        if (null === $this->collUserCommunityAssociationsRelatedByUserIdRight || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserCommunityAssociationsRelatedByUserIdRight) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserCommunityAssociationsRelatedByUserIdRight());
            }

            $query = ChildUserCommunityAssociationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserIdRight($this)
                ->count($con);
        }

        return count($this->collUserCommunityAssociationsRelatedByUserIdRight);
    }

    /**
     * Method called to associate a ChildUserCommunityAssociation object to this object
     * through the ChildUserCommunityAssociation foreign key attribute.
     *
     * @param  ChildUserCommunityAssociation $l ChildUserCommunityAssociation
     * @return $this|\User The current object (for fluent API support)
     */
    public function addUserCommunityAssociationRelatedByUserIdRight(ChildUserCommunityAssociation $l)
    {
        if ($this->collUserCommunityAssociationsRelatedByUserIdRight === null) {
            $this->initUserCommunityAssociationsRelatedByUserIdRight();
            $this->collUserCommunityAssociationsRelatedByUserIdRightPartial = true;
        }

        if (!$this->collUserCommunityAssociationsRelatedByUserIdRight->contains($l)) {
            $this->doAddUserCommunityAssociationRelatedByUserIdRight($l);
        }

        return $this;
    }

    /**
     * @param ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdRight The ChildUserCommunityAssociation object to add.
     */
    protected function doAddUserCommunityAssociationRelatedByUserIdRight(ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdRight)
    {
        $this->collUserCommunityAssociationsRelatedByUserIdRight[]= $userCommunityAssociationRelatedByUserIdRight;
        $userCommunityAssociationRelatedByUserIdRight->setUserRelatedByUserIdRight($this);
    }

    /**
     * @param  ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdRight The ChildUserCommunityAssociation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeUserCommunityAssociationRelatedByUserIdRight(ChildUserCommunityAssociation $userCommunityAssociationRelatedByUserIdRight)
    {
        if ($this->getUserCommunityAssociationsRelatedByUserIdRight()->contains($userCommunityAssociationRelatedByUserIdRight)) {
            $pos = $this->collUserCommunityAssociationsRelatedByUserIdRight->search($userCommunityAssociationRelatedByUserIdRight);
            $this->collUserCommunityAssociationsRelatedByUserIdRight->remove($pos);
            if (null === $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion) {
                $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion = clone $this->collUserCommunityAssociationsRelatedByUserIdRight;
                $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion->clear();
            }
            $this->userCommunityAssociationsRelatedByUserIdRightScheduledForDeletion[]= clone $userCommunityAssociationRelatedByUserIdRight;
            $userCommunityAssociationRelatedByUserIdRight->setUserRelatedByUserIdRight(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->fb_id = null;
        $this->google_id = null;
        $this->status = null;
        $this->date_joined = null;
        $this->display_name = null;
        $this->email = null;
        $this->phone = null;
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
            if ($this->collActivityLists) {
                foreach ($this->collActivityLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collActivityUserAssociations) {
                foreach ($this->collActivityUserAssociations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDiscussions) {
                foreach ($this->collDiscussions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDiscussionUserAssociations) {
                foreach ($this->collDiscussionUserAssociations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserCommunityAssociationsRelatedByUserIdLeft) {
                foreach ($this->collUserCommunityAssociationsRelatedByUserIdLeft as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserCommunityAssociationsRelatedByUserIdRight) {
                foreach ($this->collUserCommunityAssociationsRelatedByUserIdRight as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collActivityLists = null;
        $this->collActivityUserAssociations = null;
        $this->collDiscussions = null;
        $this->collDiscussionUserAssociations = null;
        $this->collUserCommunityAssociationsRelatedByUserIdLeft = null;
        $this->collUserCommunityAssociationsRelatedByUserIdRight = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
