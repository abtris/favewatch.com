<?php

class Application_Model_Friends extends Zend_Db_Table_Abstract
{
    protected $_name = 'friends';

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