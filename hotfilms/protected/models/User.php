<?php

class User extends CActiveRecord
{
    public $username;
    public $password;
    public $rememberMe;
    private $_identity;

    protected static $storePath = '/stories/';

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return array(
            array('login, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
            array('login', 'length', 'max'=>32),
            array('password', 'length', 'max'=>32, 'min' => 5,),
        );
    }

    public function attributeLabels()
    {
        return array(
            'rememberMe'=>'Remember me next time',
        );
    }

    public function getUser($login, $password)
    {
        return false;
    }

    public static function getUserStorePath( $userId = false)
    {
        if (!$userId) {
            $userId = Yii::app()->user->getId();
        }

        return 'http://' . $_SERVER['HTTP_HOST'] . self::$storePath . $userId . '/';
    }

    public function authenticate($attribute,$params)
    {
        if (isset($_POST['User'])) {
            if (isset($_POST['User']['login'])) {
                $this->username = $_POST['User']['login'];
            }
            else {
                return false;
            }

            if (isset($_POST['User']['password'])) {
                $this->password = $_POST['User']['password'];
            }
            else {
                return false;
            }
        }

        if(!$this->hasErrors())
        {
            $this->_identity=new UserIdentity($this->username,$this->password);

            if(!$this->_identity->authenticate())
                $this->addError('password','Incorrect username or password.');
        }
    }

    public function login()
    {
        if( $this->_identity===null )
        {
            $this->_identity=new UserIdentity($this->username,$this->password);
            $this->_identity->authenticate();
        }
        if( $this->_identity->errorCode===UserIdentity::ERROR_NONE )
        {
            $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }
        else
            return false;
    }

}
