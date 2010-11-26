<?php

namespace Doctrine\ODM\MongoDB\Tests\Events;

use Doctrine\ODM\MongoDB\CollectionEvents,
    Doctrine\ODM\MongoDB\Event\CollectionEventArgs;

class CollectionEventsTEst extends \Doctrine\ODM\MongoDB\Tests\BaseTest
{
    private $_called = array();

    public function testTest()
    {
        $events = array(
            'preBatchInsert',
            'postBatchInsert',
            'preUpdate',
            'postUpdate',
            'preFind',
            'postFind',
            'preFindOne',
            'postFindOne'
        );
        foreach ($events as $key => $event) {
            $events[$key] = constant("\Doctrine\ODM\MongoDB\CollectionEvents::$event");
        }
        $this->dm->getEventManager()->addEventListener($events, $this);

        $insert = array(array(
            'username' => 'jwage'
        ));
        $collection = $this->dm->getDocumentCollection('Documents\User');
        $collection->batchInsert($insert);
        $collection->update(array(), array('username' => 'test'));
        $cmd = $this->dm->getConfiguration()->getMongoCmd();
        $document = array('_id' => 'test', 'username' => 'jwage');
        $collection->find();
        $collection->findOne(array('username' => 'jwage'));
        $this->assertEquals($events, $this->_called);
    }

    public function __call($method, $args)
    {
        $this->_called[] = $method;
    }
}