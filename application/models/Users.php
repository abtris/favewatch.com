<?php

class Application_Model_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';

    public function insert($data)
    {
        if ($data['user_id']>0) {
            $rows = $this->find($data['user_id']);
            $rows = $rows->toArray();
            if (isset($rows[0])) {
                if (!$rows[0]['user_id']==$data['user_id']) {
                $data['first_login_at'] = new Zend_Db_Expr('NOW()');
                parent::insert($data);
                }
            }
        }
    }
}