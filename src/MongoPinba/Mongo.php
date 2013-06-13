<?php
namespace MongoPinba;

class Mongo extends \Mongo
{
    /**
     * @var MongoDB[]
     */
    private $dbs;

    public function dropDB($db)
    {
        $tags = array('group' => 'mongo', 'op' => 'client::dropDB', 'ns' => $db);
        $timer = pinba_timer_start($tags);

        $result = parent::dropDB($db);

        pinba_timer_stop($timer);

        return $result;
    }

    public function selectDB($name)
    {
        $tags = array('group' => 'mongo', 'op' => 'client::selectDB', 'ns' => $name);
        $timer = pinba_timer_start($tags);

        $MongoDB = parent::selectDB($name);

        pinba_timer_stop($timer);

        $hash = spl_object_hash($MongoDB);
        if (!isset($this->dbs[$hash])) {
            $this->dbs[$hash] = new MongoDB($this, $MongoDB, $name);
        }
        return $this->dbs[$hash];
    }

    public function selectCollection($database_name, $collection_name = null)
    {
        $MongoDB = $this->selectDB($database_name);
        if ($collection_name) {
            return $MongoDB->selectCollection($collection_name);
        } else {
            return $MongoDB;
        }
    }

    public function __get($dbname)
    {
        return $this->selectDB($dbname);
    }
}

