<?php

class Application_Model_FriendsTweets extends Zend_Db_Table_Abstract
{
    protected $_name = 'friends_tweets';
    protected $_primary = array('friend_id', 'tweet_id');

    public function insert($data)
    {
        if (!empty($data)) {
            $rows = $this->find($data['friend_id'], $data['tweet_id']);
            $rows = $rows->toArray();
            if (!isset($rows[0])) {
                $data['last_update'] = new Zend_Db_Expr('NOW()');
                parent::insert($data);
            }
        }
    }
}