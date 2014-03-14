<?php
namespace MongoPinba;

class MongoCollection
{
    /**
     * @var \MongoClient
     */
    private $MongoClient;

    /**
     * @var \MongoDB
     */
    private $MongoDB;

    /**
     * @var \MongoCollection
     */
    private $MongoCollection;

    /**
     * @var string
     */
    private $db, $collection;

    private $methods_pinba = array(
        'aggregate',
        'batchInsert',
        'count',
        'deleteIndex',
        'deleteIndexes',
        'distinct',
        'drop',
        'ensureIndex',
        'findAndModify',
        'findOne',
        'group',
        'insert',
        'remove',
        'save',
        'update',
    );

    public function __construct(\MongoClient $MongoClient, MongoDB $MongoDB, \MongoCollection $MongoCollection, $db, $collection)
    {
        $this->MongoClient = $MongoClient;
        $this->MongoDB = $MongoDB;
        $this->MongoCollection = $MongoCollection;
        $this->db = $db;
        $this->collection = $collection;
    }

    public function find(array $query = array(), array $fields = array())
    {
        $tags = array('group' => 'mongo', 'op' => 'collection::find', 'ns' => $this->db . '.' . $this->collection);
        $timer = pinba_timer_start($tags);
        $result = new MongoCursor($this->MongoClient, "{$this->db}.{$this->collection}", $query, $fields);
        pinba_timer_stop($timer);
        return $result;
    }

    public function __call($method, $args)
    {
        $use_timer = in_array($method, $this->methods_pinba, true);
        if ($use_timer) {
            $tags = array('group' => 'mongo', 'op' => 'collection::' . $method, 'ns' => $this->db . '.' . $this->collection);
            $timer = pinba_timer_start($tags);
        }
        $result = call_user_func_array(array($this->MongoCollection, $method), $args);
        if ($use_timer) {
            pinba_timer_stop($timer);
        }
        return $result;
    }

    public function __get($name)
    {
        return $this->MongoDB->selectCollection($name);
    }
}
