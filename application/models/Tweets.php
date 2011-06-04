<?php

class Application_Model_Tweets extends Zend_Db_Table_Abstract
{
    protected $_name = 'tweets';

    public function insert($data)
    {
        $rows = $this->find($data['tweet_id']);
        $rows = $rows->toArray();
        if (!$rows[0]['tweet_id']==$data['tweet_id']) {
            parent::insert($data);
        }
    }
}