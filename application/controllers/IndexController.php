<?php
/**
 *
 */
class IndexController extends Zend_Controller_Action
{
    /**
     * @todo Ajax version ???
     * @return void
     */
    public function updateAction()
    {
        $this->_helper->layout->disableLayout();
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
                $ft = new Application_Model_FriendsTweets();
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
                    $ft->insert(array('friend_id'=>$id, 'tweet_id'=> $f->id));
                }
            }
        }
        if (!$this->_request->isXmlHttpRequest()) {
            $this->_redirect('index/index');
        }
    }
    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->headTitle('Homepage');
        $this->view->title = 'FaveWatch.com';
        $fc = $this->getFrontController();

        // Get the model instance from the action helper
        $twitter = $this->_helper->twitter(); /* @var $twitter Application_Model_Twitter */
        if ($twitter->isLoggedIn()) {
            // User update
            $friends = $twitter->getService()->user->friends();
            foreach ($friends as $f) {
                $friend = new Application_Model_Friends();
                $fData['friend_id'] = (string) $f->id;
                $fData['user_id'] = $twitter->getUserId();
                $friend->insert($fData);
            }
            $u = new Application_Model_Users();
            $userData['user_id'] =  $twitter->getUserId();
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
