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
    /**
     * Get Tweets
     * @param bigint $id user_id
     * @param int $limit limit
     * @return array
     */
    public function getTweets($id, $limit = 30)
    {
        $q = "select friends.friend_id, friends.screen_name as fscn, tweets.*
              from friends join friends_tweets join tweets
              where friends.user_id=$id and friends.friend_id = friends_tweets.friend_id and friends_tweets.tweet_id = tweets.tweet_id
              order by tweets.tweet_id DESC
              limit $limit";
        $stmt = $this->_db->query($q);
        $rows = $stmt->fetchAll();
        return $rows;
    }

}