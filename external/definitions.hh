<?hh //decl

final class MongoDB\BSON\Timestamp implements MongoDB\BSON\Type, Serializable, Stringish {
    use MongoDB\BSON\DenySerialization;
    public function __construct($increment, $timestamp);
    public function __debugInfo();
    public function __toString();
    public function serialize();
    public function unserialize($data);
}

class MongoDB\Driver\Exception\LogicException extends LogicException implements MongoDB\Driver\Exception\Exception {}

final class MongoDB\Driver\ReadPreference {
    const int RP_PRIMARY = 1;
    const int RP_PRIMARY_PREFERRED = 5;
    const int RP_SECONDARY = 2;
    const int RP_SECONDARY_PREFERRED = 6;
    const int RP_NEAREST = 10;
    public function __construct($readPreference, ?array $tagSets = NULL);
    public function __debugInfo();
    private function _setReadPreference($readPreference);
    private function _setReadPreferenceTags(array $tagSets);
    public function getMode();
    public function getTagSets();
}

abstract class MongoDB\Driver\Exception\WriteException extends MongoDB\Driver\Exception\RuntimeException {
    final public function getWriteResult();
}

final class MongoDB\Driver\CursorId implements Stringish {
    private function __construct($id);
    public function __debugInfo();
    public function __toString();
}

class MongoDB\Driver\Exception\BulkWriteException extends MongoDB\Driver\Exception\WriteException {}

class MongoDB\Driver\Exception\SSLConnectionException extends MongoDB\Driver\Exception\ConnectionException {}

final class MongoDB\BSON\Regex implements MongoDB\BSON\Type, Serializable, Stringish {
    use MongoDB\BSON\DenySerialization;
    public function __construct($pattern, $flags);
    public function __debugInfo();
    public function __toString();
    public function getFlags();
    public function getPattern();
    public function serialize();
    public function unserialize($data);
}

class MongoDB\Driver\Manager {
    public function __construct($dsn = "mongodb://localhost", array $options = array (), array $driverOptions = array ());
    public function __debugInfo();
    public function __wakeUp();
    public function executeBulkWrite($namespace, MongoDB\Driver\BulkWrite $bulk, ?MongoDB\Driver\WriteConcern $writeConcern = NULL);
    public function executeCommand($db, MongoDB\Driver\Command $command, ?MongoDB\Driver\ReadPreference $readPreference = NULL);
    public function executeQuery($namespace, MongoDB\Driver\Query $query, ?MongoDB\Driver\ReadPreference $readPreference = NULL);
    public function getReadConcern();
    public function getReadPreference();
    public function getServers();
    public function getWriteConcern();
    public function selectServer(MongoDB\Driver\ReadPreference $readPreference);
}

final class MongoDB\Driver\BulkWrite implements Countable {
    public function __construct($bulkWriteOptions = array ());
    public function __debugInfo();
    public function count();
    public function delete($query, $deleteOptions = array ());
    public function insert($document);
    public function update($query, $newObj, $updateOptions = array ());
}

class MongoDB\Driver\Exception\AuthenticationException extends MongoDB\Driver\Exception\ConnectionException {}

final class MongoDB\BSON\MaxKey implements MongoDB\BSON\Type, Serializable {
    use MongoDB\BSON\DenySerialization;
    public function serialize();
    public function unserialize($data);
}

class MongoDB\Driver\Exception\InvalidArgumentException extends InvalidArgumentException implements MongoDB\Driver\Exception\Exception {}

class MongoDB\Driver\Exception\ConnectionException extends MongoDB\Driver\Exception\RuntimeException {}

final class MongoDB\Driver\WriteConcernError {
    private function __construct();
    public function __debugInfo();
    public function getCode();
    public function getInfo();
    public function getMessage();
}

class MongoDB\Driver\Exception\UnexpectedValueException extends UnexpectedValueException implements MongoDB\Driver\Exception\Exception {}

final class MongoDB\Driver\Cursor<T> implements HH\Traversable, HH\Iterator, Iterator<T>, Traversable<T> {
    private function __construct(MongoDB\Driver\Server $server, MongoDB\Driver\CursorId $cursorId, array $firstBatch);
    public function __debugInfo();
    public function current();
    public function getId();
    public function getServer();
    public function isDead();
    public function key();
    public function next();
    public function rewind();
    public function setTypeMap(array $typemap);
    public function toArray();
    public function valid();
}

final class MongoDB\Driver\WriteConcern {
    const string MAJORITY = 'majority';
    public function __construct($w, $wtimeout = 0, $journal = NULL);
    public function __debugInfo();
    public function getJournal();
    public function getW();
    public function getWtimeout();
}

final class MongoDB\Driver\Server {
    const int TYPE_UNKNOWN = 0;
    const int TYPE_STANDALONE = 1;
    const int TYPE_MONGOS = 2;
    const int TYPE_POSSIBLE_PRIMARY = 3;
    const int TYPE_RS_PRIMARY = 4;
    const int TYPE_RS_SECONDARY = 5;
    const int TYPE_RS_ARBITER = 6;
    const int TYPE_RS_OTHER = 7;
    const int TYPE_RS_GHOST = 8;
    private function __construct();
    public function __debugInfo();
    public function executeBulkWrite($namespace, MongoDB\Driver\BulkWrite $bulk, ?MongoDB\Driver\WriteConcern $writeConcern = NULL);
    public function executeCommand($db, MongoDB\Driver\Command $command, ?MongoDB\Driver\ReadPreference $readPreference = NULL);
    public function executeQuery($namespace, MongoDB\Driver\Query $query, ?MongoDB\Driver\ReadPreference $readPreference = NULL);
    public function getHost();
    final public function getInfo();
    public function getLatency();
    public function getPort();
    public function getTags();
    public function getType();
    public function isArbiter();
    public function isHidden();
    public function isPassive();
    public function isPrimary();
    public function isSecondary();
}

final class MongoDB\Driver\Query {
    const int FLAG_NONE = 0;
    const int FLAG_TAILABLE_CURSOR = 2;
    const int FLAG_SLAVE_OK = 4;
    const int FLAG_OPLOG_REPLAY = 8;
    const int FLAG_NO_CURSOR_TIMEOUT = 16;
    const int FLAG_AWAIT_DATA = 32;
    const int FLAG_EXHAUST = 64;
    const int FLAG_PARTIAL = 128;
    public function __construct($filter, array $options = array ());
    public function __debugInfo();
}

final class MongoDB\Driver\WriteError {
    private function __construct();
    public function __debugInfo();
    public function getCode();
    public function getIndex();
    public function getInfo();
    public function getMessage();
}

final class MongoDB\Driver\Command {
    public function __construct($command);
    public function __debugInfo();
}

final class MongoDB\BSON\Javascript implements MongoDB\BSON\Type, Serializable {
    use MongoDB\BSON\DenySerialization;
    public function __construct($code, $scope = NULL);
    public function __debugInfo();
    public function serialize();
    public function unserialize($data);
}

class MongoDB\Driver\Exception\ExecutionTimeoutException extends MongoDB\Driver\Exception\RuntimeException {}

final class MongoDB\BSON\ObjectID implements MongoDB\BSON\Type, Serializable, Stringish {
    use MongoDB\BSON\DenySerialization;
    public function __construct($objectId = NULL);
    public function __debugInfo();
    public function __toString();
    public function serialize();
    public function unserialize($data);
}

final class MongoDB\Driver\ReadConcern {
    const string LOCAL = 'local';
    const string MAJORITY = 'majority';
    public function __construct($level = NULL);
    public function __debugInfo();
    public function getLevel();
}

class MongoDB\Driver\Exception\RuntimeException extends RuntimeException implements MongoDB\Driver\Exception\Exception {}

final class MongoDB\BSON\Binary implements MongoDB\BSON\Type, Serializable {
    use MongoDB\BSON\DenySerialization;
    const int TYPE_GENERIC = 0;
    const int TYPE_FUNCTION = 1;
    const int TYPE_OLD_BINARY = 2;
    const int TYPE_OLD_UUID = 3;
    const int TYPE_UUID = 4;
    const int TYPE_MD5 = 5;
    const int TYPE_USER_DEFINED = 128;
    public function __construct($data, $type);
    public function __debugInfo();
    public function getData();
    public function getType();
    public function serialize();
    public function unserialize($data);
}

final class MongoDB\BSON\UTCDateTime implements MongoDB\BSON\Type, Serializable, Stringish {
    use MongoDB\BSON\DenySerialization;
    public function __construct($milliseconds);
    public function __debugInfo();
    public function __toString();
    public function serialize();
    public function toDateTime();
    public function unserialize($data);
}

class MongoDB\Driver\Exception\ConnectionTimeoutException extends MongoDB\Driver\Exception\ConnectionException {}

final class MongoDB\Driver\WriteResult {
    private function __construct();
    public function __debugInfo();
    public function __wakeup();
    public function getDeletedCount();
    public function getInsertedCount();
    public function getMatchedCount();
    public function getModifiedCount();
    public function getServer();
    public function getUpsertedCount();
    public function getUpsertedIds();
    public function getWriteConcernError();
    public function getWriteErrors();
    public function isAcknowledged();
}

class MongoDB\Driver\Utils {
    const int ERROR_INVALID_ARGUMENT = 1;
    const int ERROR_RUNTIME = 2;
    const int ERROR_MONGOC_FAILED = 3;
    const int ERROR_WRITE_FAILED = 4;
    const int ERROR_CONNECTION_FAILED = 5;
    public static function mustBeArrayOrObject($name, $value, $context = "");
    public static function throwHippoException($domain, $message);
}

final class MongoDB\BSON\MinKey implements MongoDB\BSON\Type, Serializable {
    use MongoDB\BSON\DenySerialization;
    public function serialize();
    public function unserialize($data);
}

class MongoDB\Model\DatabaseInfo {
    public function __construct(array $info);
    public function __debugInfo();
    public function getName();
    public function getSizeOnDisk();
    public function isEmpty();
}

class MongoDB\InsertManyResult {
    public function __construct(MongoDB\Driver\WriteResult $writeResult, array $insertedIds);
    public function getInsertedCount();
    public function getInsertedIds();
    public function isAcknowledged();
}

class MongoDB\Operation\FindOneAndDelete implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\ListIndexes implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
    private function executeCommand(MongoDB\Driver\Server $server);
    private function executeLegacy(MongoDB\Driver\Server $server);
}

class MongoDB\Model\CollectionInfoCommandIterator<T> extends IteratorIterator<T> implements MongoDB\Model\CollectionInfoIterator {}

class MongoDB\Operation\Aggregate implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $pipeline, array $options = array ());
    private function createCommand(MongoDB\Driver\Server $server, $isCursorSupported);
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\UpdateMany implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, $update, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\CreateCollection implements MongoDB\Operation\Executable {
    const int USE_POWER_OF_2_SIZES = 1;
    const int NO_PADDING = 2;
    public function __construct($databaseName, $collectionName, array $options = array ());
    private function createCommand();
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\UpdateResult {
    public function __construct(MongoDB\Driver\WriteResult $writeResult);
    public function getMatchedCount();
    public function getModifiedCount();
    public function getUpsertedCount();
    public function getUpsertedId();
    public function isAcknowledged();
}

class MongoDB\Client implements Stringish {
    public function __construct($uri = "mongodb://localhost:27017", array $uriOptions = array (), array $driverOptions = array ());
    public function __debugInfo();
    public function __get($databaseName);
    public function __toString();
    public function dropDatabase($databaseName, array $options = array ());
    public function listDatabases(array $options = array ());
    public function selectCollection($databaseName, $collectionName, array $options = array ());
    public function selectDatabase($databaseName, array $options = array ());
}

class MongoDB\Operation\Find implements MongoDB\Operation\Executable {
    const int NON_TAILABLE = 1;
    const int TAILABLE = 2;
    const int TAILABLE_AWAIT = 3;
    public function __construct($databaseName, $collectionName, $filter, array $options = array ());
    private function createQuery();
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Collection implements Stringish {
    public function __construct(MongoDB\Driver\Manager $manager, $databaseName, $collectionName, array $options = array ());
    public function __debugInfo();
    public function __toString();
    public function aggregate(array $pipeline, array $options = array ());
    public function bulkWrite(array $operations, array $options = array ());
    public function count($filter = array (), array $options = array ());
    public function createIndex($key, array $options = array ());
    public function createIndexes(array $indexes);
    public function deleteMany($filter, array $options = array ());
    public function deleteOne($filter, array $options = array ());
    public function distinct($fieldName, $filter = array (), array $options = array ());
    public function drop(array $options = array ());
    public function dropIndex($indexName, array $options = array ());
    public function dropIndexes(array $options = array ());
    public function find($filter = array (), array $options = array ());
    public function findOne($filter = array (), array $options = array ());
    public function findOneAndDelete($filter, array $options = array ());
    public function findOneAndReplace($filter, $replacement, array $options = array ());
    public function findOneAndUpdate($filter, $update, array $options = array ());
    public function getCollectionName();
    public function getDatabaseName();
    public function getNamespace();
    public function insertMany(array $documents, array $options = array ());
    public function insertOne($document, array $options = array ());
    public function listIndexes(array $options = array ());
    public function replaceOne($filter, $replacement, array $options = array ());
    public function updateMany($filter, $update, array $options = array ());
    public function updateOne($filter, $update, array $options = array ());
    public function withOptions(array $options = array ());
}

class MongoDB\Operation\ReplaceOne implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, $replacement, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\Update implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, $update, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\DropIndexes implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $indexName, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\DeleteOne implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Model\DatabaseInfoLegacyIterator<T> implements MongoDB\Model\DatabaseInfoIterator, Iterator<T>, Traversable<T> {
    public function __construct(array $databases);
    public function current();
    public function key();
    public function next();
    public function rewind();
    public function valid();
}

class MongoDB\Operation\FindOneAndReplace implements MongoDB\Operation\Executable {
    const int RETURN_DOCUMENT_BEFORE = 1;
    const int RETURN_DOCUMENT_AFTER = 2;
    public function __construct($databaseName, $collectionName, $filter, $replacement, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\UpdateOne implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, $update, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\Delete implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, $limit, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\ListDatabases implements MongoDB\Operation\Executable {
    public function __construct(array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\DeleteMany implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\InsertOneResult {
    public function __construct(MongoDB\Driver\WriteResult $writeResult, $insertedId);
    public function getInsertedCount();
    public function getInsertedId();
    public function isAcknowledged();
}

class MongoDB\BulkWriteResult {
    public function __construct(MongoDB\Driver\WriteResult $writeResult, array $insertedIds);
    public function getDeletedCount();
    public function getInsertedCount();
    public function getInsertedIds();
    public function getMatchedCount();
    public function getModifiedCount();
    public function getUpsertedCount();
    public function getUpsertedIds();
    public function isAcknowledged();
}

class MongoDB\Model\CollectionInfo {
    public function __construct(array $info);
    public function __debugInfo();
    public function getCappedMax();
    public function getCappedSize();
    public function getName();
    public function getOptions();
    public function isCapped();
}

class MongoDB\Operation\FindAndModify implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $options);
    private function createCommand(MongoDB\Driver\Server $server);
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\InsertOne implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $document, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\Count implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter = array (), array $options = array ());
    private function createCommand(MongoDB\Driver\Server $server);
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Model\BSONDocument extends ArrayObject implements MongoDB\BSON\Serializable, MongoDB\BSON\Unserializable, MongoDB\BSON\Type {
    public static function __set_state(array $properties);
    public function bsonSerialize();
    public function bsonUnserialize(array $data);
}

class MongoDB\Operation\ListCollections implements MongoDB\Operation\Executable {
    public function __construct($databaseName, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
    private function executeCommand(MongoDB\Driver\Server $server);
    private function executeLegacy(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\Distinct implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $fieldName, $filter = array (), array $options = array ());
    private function createCommand(MongoDB\Driver\Server $server);
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Model\BSONArray extends ArrayObject implements MongoDB\BSON\Serializable, MongoDB\BSON\Unserializable, MongoDB\BSON\Type {
    public static function __set_state(array $properties);
    public function bsonSerialize();
    public function bsonUnserialize(array $data);
}

class MongoDB\Operation\CreateIndexes implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $indexes);
    public function execute(MongoDB\Driver\Server $server);
    private function executeCommand(MongoDB\Driver\Server $server);
    private function executeLegacy(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\DropCollection implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\BulkWrite implements MongoDB\Operation\Executable {
    const string DELETE_MANY = 'deleteMany';
    const string DELETE_ONE = 'deleteOne';
    const string INSERT_ONE = 'insertOne';
    const string REPLACE_ONE = 'replaceOne';
    const string UPDATE_MANY = 'updateMany';
    const string UPDATE_ONE = 'updateOne';
    public function __construct($databaseName, $collectionName, array $operations, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Model\IndexInfo<Tk,Tv> implements ArrayAccess<Tk,Tv> {
    public function __construct(array $info);
    public function __debugInfo();
    public function getKey();
    public function getName();
    public function getNamespace();
    public function getVersion();
    public function isSparse();
    public function isTtl();
    public function isUnique();
    public function offsetExists($key);
    public function offsetGet($key);
    public function offsetSet($key, $value);
    public function offsetUnset($key);
}

class MongoDB\Operation\FindOneAndUpdate implements MongoDB\Operation\Executable {
    const int RETURN_DOCUMENT_BEFORE = 1;
    const int RETURN_DOCUMENT_AFTER = 2;
    public function __construct($databaseName, $collectionName, $filter, $update, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Operation\InsertMany implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, array $documents, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Database implements Stringish {
    public function __construct(MongoDB\Driver\Manager $manager, $databaseName, array $options = array ());
    public function __debugInfo();
    public function __get($collectionName);
    public function __toString();
    public function command($command, array $options = array ());
    public function createCollection($collectionName, array $options = array ());
    public function drop(array $options = array ());
    public function dropCollection($collectionName, array $options = array ());
    public function getDatabaseName();
    public function listCollections(array $options = array ());
    public function selectCollection($collectionName, array $options = array ()):MongoDB\Collection;
    public function withOptions(array $options = array ());
}

class MongoDB\Operation\DropDatabase implements MongoDB\Operation\Executable {
    public function __construct($databaseName, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Exception\UnexpectedValueException extends MongoDB\Driver\Exception\UnexpectedValueException implements MongoDB\Exception\Exception {}

class MongoDB\Exception\InvalidArgumentException extends MongoDB\Driver\Exception\InvalidArgumentException implements MongoDB\Exception\Exception {
    public static function invalidType($name, $value, $expectedType);
}

class MongoDB\Model\IndexInfoIteratorIterator<T> extends IteratorIterator<T> implements MongoDB\Model\IndexInfoIterator {}

class MongoDB\Exception\RuntimeException extends MongoDB\Driver\Exception\RuntimeException implements MongoDB\Exception\Exception {}

class MongoDB\Operation\FindOne implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $collectionName, $filter, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

abstract class MongoDB\Model\CollectionInfoLegacyIterator<T> extends FilterIterator<T> implements MongoDB\Model\CollectionInfoIterator {}

class MongoDB\Model\IndexInput implements MongoDB\BSON\Serializable, MongoDB\BSON\Type, Stringish {
    public function __construct(array $index);
    public function __toString();
    public function bsonSerialize();
}

class MongoDB\DeleteResult {
    public function __construct(MongoDB\Driver\WriteResult $writeResult);
    public function getDeletedCount();
    public function isAcknowledged();
}

class MongoDB\Operation\DatabaseCommand implements MongoDB\Operation\Executable {
    public function __construct($databaseName, $command, array $options = array ());
    public function execute(MongoDB\Driver\Server $server);
}

class MongoDB\Exception\BadMethodCallException extends BadMethodCallException implements MongoDB\Exception\Exception, MongoDB\Driver\Exception\Exception {
    public static function classIsImmutable($class);
    public static function unacknowledgedWriteResultAccess($method);
}

namespace MongoDB\Model {
  interface DatabaseInfoIterator{}
}

const int RAND_MAX = 1;
const bool APP_IN_CLI = false;
const bool APP_DEBUG = false;
const string APP_ENV = '';
