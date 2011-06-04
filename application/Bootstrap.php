<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initSession()
    {
        Zend_Session::start();
    }
    /**
     * init config for admin
     * @return void
     */
    protected function _initConfig ()
    {
        $this->_config = new Zend_Config_Ini('../application/configs/'.
        'application.ini', APPLICATION_ENV);
        Zend_Registry::getInstance()->set('config', $this->_config);
    }
    /**
     * moved to preDispatch in each module controller
     * @return void
     */
    protected function _initModuleDatabase ()
    {
       $this->_db = Zend_Db::factory($this->_config->database);
       Zend_Db_Table_Abstract::setDefaultAdapter($this->_db);
       Zend_Registry::getInstance()->set('db', $this->_db);
    }
}

