<?php
namespace MongoPinba;

class MongoCursor extends \MongoCursor
{
    /**
     * @var string
     */
    private $ns;

    public function __construct(\MongoClient $MongoClient, $ns, array $query, array $field)
    {
        $this->ns = $ns;
        parent::__construct($MongoClient, $ns, $query, $field);
    }

    public function count($foundOnly = false)
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::count', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::count($foundOnly);
        pinba_timer_stop($timer);
        return $result;
    }

    public function current()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::current', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::current();
        pinba_timer_stop($timer);
        return $result;
    }

    public function getNext()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::getNext', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::getNext();
        pinba_timer_stop($timer);
        return $result;
    }

    public function hasNext()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::hasNext', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::hasNext();
        pinba_timer_stop($timer);
        return $result;
    }

    public function info()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::info', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::info();
        pinba_timer_stop($timer);
        return $result;
    }

    public function key()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::key', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::key();
        pinba_timer_stop($timer);
        return $result;
    }

    public function next()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::next', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::next();
        pinba_timer_stop($timer);
        return $result;
    }

    public function reset()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::reset', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::reset();
        pinba_timer_stop($timer);
        return $result;
    }

    public function rewind()
    {
        $tags = array('group' => 'mongo', 'op' => 'cursor::rewind', 'ns' => $this->ns);
        $timer = pinba_timer_start($tags);
        $result = parent::rewind();
        pinba_timer_stop($timer);
        return $result;
    }
}

