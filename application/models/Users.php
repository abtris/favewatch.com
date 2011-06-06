<?php

class Application_Model_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    public function insert($data)
    {
        $rows = $this->find($data['user_id']);
        $rows = $rows->toArray();
        if (isset($rows[0]) && !$rows[0]['user_id']==$data['user_id']) {
            $data['first_login_at'] = new Zend_Db_Expr('NOW()');
            if ($data['user_id']>0) {
            parent::insert($data);
            }
        } else {
            if ($data['user_id']>0) {
            parent::update($data, array('user_id'=> $data['user_id']));
            }
        }
    }
}