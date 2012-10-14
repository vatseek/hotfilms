<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    public $username;
    public $password;

    public function __construct($user, $password)
    {
        $this->username = $user;
        $this->password = $password;

        parent::__construct($user, $password);
    }

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $record=User::model()->findByAttributes(array('login'=>$this->username));

        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password!==md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->user_id;
            $this->setState('title', $record->login);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
	}
}