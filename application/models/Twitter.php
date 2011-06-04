<?php

class Application_Model_Twitter
{
    /**
     * @var array
     */
    protected $_options;
    
    /**
     * @var App_Service_Twitter
     */
    protected $_service;
    
    
    public function __construct($options)
    {
        $this->_options = $options;
    }

    public function isLoggedIn()
    {
        return $this->getService()->isAuthorised();
    }

    public function getRequestToken()
    {
        $service = $this->getService();
        return $service->getRequestToken();
    }

    public function loginViaTwitterSite()
    {
        $service = $this->getService();
        $service->redirect(); // redirect to Twitter
    }

    /**
     * Retrieve an Access Token in exchange for a previously received/authorized
     * Request Token.
     *
     * @param  array $queryData GET data returned in user's redirect from Provider
     * @param  Zend_Oauth_Token_Request Request Token information
     * 
     * @return Zend_Oauth_Token_Access
     * @throws Zend_Oauth_Exception on invalid authorization token, non-matching response authorization token, or unprovided authorization token
     */
    public function getAccessToken($queryData, Zend_Oauth_Token_Request $token)
    {
        $service = $this->getService();
        $token = $service->getAccessToken($queryData, $token);
        
        return $token;
    }

    /**
     * Retreive the user's name
     *
     * @return string
     */
    public function getName()
    {
        try {
            $response = $this->getService()->account->verifyCredentials();
            $name = $response->name;
        } catch (Zend_Exception $e) {
            return '';
        }
        return $name;
    }
    /**
     * Retrive user's id
     * @return string
     */
    public function getUserId()
    {
        try {
            $response = $this->getService()->account->verifyCredentials();
            $id = (string) $response->id;
        } catch (Zend_Exception $e) {
            return '';
        }
        return $id;
    }
    /**
     * Send a message with position
     *
     * @param string $message
     * @param string $latitude
     * @param string $longitude
     * @return Zend_Rest_Client_Result
     */
    public function send($message, $latitude = null, $longitude = null)
    {
        $response = $this->getService()->status->updateWithPosition($message, $latitude, $longitude);
        return $response;
    }
    
    public function getMessages(array $options = null)
    {
         $response = $this->getService()->directMessage->messages($options);
         return $response;
    }
    
    /**
     * Connect to Twitter
     * @return Zend_Service_Twitter
     */
    public function getService()
    {
        if(!$this->_service) {
            $this->_service = new App_Service_Twitter($this->_options);
        }

        return $this->_service;
    }


	public static function clickable($s)
	{
		return preg_replace_callback(
			'~(?<!\w)(https?://\S+\w|www\.\S+\w|@\w+|#\w+|<>&)~u',
			array(__CLASS__, 'clickableCallback'),
			html_entity_decode($s, ENT_QUOTES, 'UTF-8')
		);
	}



	private static function clickableCallback($m)
	{
		$m = htmlspecialchars($m[1]);
		if ($m[0] === '#') {
			$m = substr($m, 1);
			return "<a href='http://twitter.com/search?q=%23$m'>#$m</a>";
		} elseif ($m[0] === '@') {
			$m = substr($m, 1);
			return "@<a href='http://www.twitter.com/$m'>$m</a>";
		} elseif ($m[0] === 'w') {
			return "<a href='http://$m'>$m</a>";
		} elseif ($m[0] === 'h') {
			return "<a href='$m'>$m</a>";
		} else {
			return $m;
		}
	}
}

