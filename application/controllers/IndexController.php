<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function updateAction()
    {
        $twitter = $this->_helper->twitter(); /* @var $twitter Application_Model_Twitter */
        if ($twitter->isLoggedIn()) {
            $friends = $twitter->getService()->user->friends();
            foreach ($friends as $f) {
                $ids[] = (string) $f->id;
            }
            $this->view->friends = $friends;

            foreach ($ids as $id) {
                $favorites = $twitter->getService()->favorite->favorites(array('id'=>$id));
                $data = array();
                $t = new Application_Model_Tweets();
                foreach ($favorites as $f) {
                    $data['tweet_id'] = $f->id;
                    $data['user_id'] = $f->user->id;
                    $data['profile_image_url'] = $f->user->profile_image_url;
                    $data['text'] = $f->text;
                    $data['created_at'] =  strtotime($f->created_at);
                    $data['inserted_at'] =  new Zend_Db_Expr('NOW()');
                    $data['name'] = $f->user->name;
                    $data['screen_name'] = $f->user->screen_name;
                    $t->insert($data);
                }
            }
        }
        $this->_redirect('index');
    }


    public function indexAction()
    {
        $this->view->headTitle('Favorites');
        $this->view->title = 'Favorites';
        $fc = $this->getFrontController();

        // Get the model instance from the action helper
        $twitter = $this->_helper->twitter(); /* @var $twitter Application_Model_Twitter */
        if ($twitter->isLoggedIn()) {
            // User update
            $friends = $twitter->getService()->user->friends();
            foreach ($friends as $f) {
                $ids[] = (string) $f->id;
            }
            $u = new Application_Model_Users();
            $userData['user_id'] =  $twitter->getUserId();
            $userData['friends'] =  json_encode($ids);
            $userData['last_login_at'] =  new Zend_Db_Expr('NOW()');
            $u->insert($userData);
            // We only care abotu setting up the home page for posting a tweet
            // if we are logged in.
            $t = new Application_Model_Tweets();
            $select  = $t->select()->order('created_at DESC')->limit(50);
            $this->view->favorites = $t->fetchAll($select);
            $this->view->name = $twitter->getName();
        }
        
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function logoutAction()
    {
        $session = new Zend_Session_Namespace();
        $session->unsetAll();
        $this->_helper->redirector('index', 'index');
    }

    public function loginAction()
    {
        $twitter = $this->_helper->twitter(); /* @var $twitter Application_Model_Twitter */

        // We need the request token for use in the callback when the user is
        // redirected back here from Twitter after authenticating
        $session = new Zend_Session_Namespace();
        $session->requestToken = $twitter->getRequestToken();

        // redirect to the Twitter website
        $twitter->loginViaTwitterSite();
    }
}
