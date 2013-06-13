<?php
namespace MongoPinba;

class MongoDB
{
    /**
     * @var \MongoClient
     */
    private $MongoClient;

    /**
     * @var \MongoDB
     */
    private $MongoDB;

    private $db;

    /**
     * @var MongoCollection[]
     */
    private $collections;

    private $methods_pinba = array(
        'authenticate',
        'command',
        'drop',
        'dropCollection',
        'execute',
        'getCollectionNames',
        'repair',
    );

    public function __construct(\MongoClient $MongoClient, \MongoDB $MongoDB, $db)
    {
        $this->MongoClient = $MongoClient;
        $this->MongoDB = $MongoDB;
        $this->db = $db;
    }

    public function createCollection($name, array $options = array())
    {
        $tags = array('group' => 'mongo', 'op' => 'db::createCollection', 'ns' => $this->db . '.' . $name);
        $timer = pinba_timer_start($tags);

        $MongoCollection = $this->MongoDB->createCollection($name, $options);

        pinba_timer_stop($timer);

        $hash = spl_object_hash($MongoCollection);
        if (!isset($this->collections[$hash])) {
            $this->collections[$hash] = new MongoCollection($this->MongoClient, $this, $MongoCollection, $this->db, $name);
        }
        return $this->collections[$hash];
    }

    public function selectCollection($name)
    {
        $tags = array('group' => 'mongo', 'op' => 'db::selectCollection', 'ns' => $this->db . '.' . $name);
        $timer = pinba_timer_start($tags);

        $MongoCollection = $this->MongoDB->selectCollection($name);

        pinba_timer_stop($timer);

        $hash = spl_object_hash($MongoCollection);
        if (!isset($this->collections[$hash])) {
            $this->collections[$hash] = new MongoCollection($this->MongoClient, $this, $MongoCollection, $this->db, $name);
        }
        return $this->collections[$hash];
    }

    public function __call($method, $args)
    {
        $use_timer = in_array($method, $this->methods_pinba, true);
        if ($use_timer) {
            $tags = array('group' => 'mongo', 'op' => 'db::' . $method, 'ns' => $this->db);
            $timer = pinba_timer_start($tags);
        }

        $result = call_user_func_array(array($this->MongoDB, $method), $args);

        if ($use_timer) {
            pinba_timer_stop($timer);
        }
        return $result;
    }

    public function __get($name)
    {
        return $this->selectCollection($name);
    }
}


