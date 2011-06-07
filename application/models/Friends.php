<?php

class Application_Model_Friends extends Zend_Db_Table_Abstract
{
    protected $_name = 'friends';
    /**
     * Update Friends
     * @param object $friends
     * @param int $user_id
     * @return array
     */
    public function updateFriends($friends, $user_id)
    {
        // clear return array
        $ids = array();
        // select last updated friends
        $rows = $this->fetchAll(
                    $this->select()
                    ->where('user_id = :user_id')
                    ->bind(array(':user_id'=>$user_id))
                    ->order('last_update ASC')
                    ->limit(20, 0)
                );
        if (count($rows)>0) {
            foreach ($rows as $row) {
                $ids[] = $row['friend_id'];
                // update using id
                $where = array('user_id' => $user_id , 'friend_id' => $row['friend_id']);
                $this->update(array('last_update'=> new Zend_Db_Expr('NOW()')), $where);
            }

        } else {
            // first time update into database all friends
            $row = 0;
            foreach ($friends as $f) {
                $ids[] = (string) $f->id;
                $fData['friend_id'] = (string) $f->id;
                $fData['screen_name'] = (string) $f->screen_name;
                $fData['profile_image_url'] = (string) $f->profile_image_url;
                $fData['url'] = (string) $f->url;
                $fData['user_id'] = $user_id;
                $this->insert($fData);
                $row++;
            }
            $ids = array_slice($ids, 0, 10);
        }
        // output
        return $ids;
    }
    /**
     * Insert
     * @param array $data
     * @return void
     */
    public function insert($data)
    {
        $rows = $this->find($data['friend_id']);
        $rows = $rows->toArray();
        if (!$rows[0]['friend_id']==$data['friend_id'] && !$rows[0]['user_id']==$data['user_id']) {
            $data['last_update'] = new Zend_Db_Expr('NOW()');
            parent::insert($data);
        }
    }
}