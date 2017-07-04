<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\web\IndentityInterface

/**
 * User modelr
 * @property string $username
 * @property string $surname
 * @property string $name
 * @property string $password write only password
 * @property string $salt
 * @property string $access_token
 * @property string $create_date
 *
 */
class User extends ActiveRecord implements IndentityInterface{
    /*
     *
     *
     */
    public static function tableName(){
        return 'prm_user';
    }
    /*
     *
     */
    public function rules(){
        return [
            [['username', 'name', 'surname', 'password'], 'required'],
            ['username', 'email'],
            ['username', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => _('ID'),
            'name' => _('Имя'),
            'surname' => _("Фамилия"),
            'password'=> _('Пароль'),
            'salt' => -('Соль'),
            'access_token' => _('Ключь авторизации'),
        ]
    }

    /*
     * Before save event handier Перед сохранением события
     * @param bool $insert
     * @return bool
     * в методе beforesave при добавлении новой записи будем генерировать соль нашего пароля и на шпароль соединять с солью
     */
    public function beforeSave ($insert){
        if(parent::beforeDelete($insert)){
            if($this->getIsNewRecord() && !empty($this->password)){
                $this->salt = $this->saltGenerator();
            }
            if(!empty($this->password)){
                $this->password = $this->passWithSalt($this->password, $this->salt);
            }
            else{
                //если не новая запись пароь уничтожаем (чтоб ы не щаписывался при добавлении в db
                unset($this->password);
            }
            return true;
        }
        else{
            return false;
        }
    }



    /*
     * Generator the salt
     * @return string
     * uniqid - Генерирует уникальный ID
     */
    public function saltGenerator()
    {
        return hash("sha512", uniqid('salt_', true));
    }

    /*
      * Return pass with the salt
      * @param $password
      * @param $salt
      * @return string
      */
    public function passWithSalt ($password, $salt){
        return hash("sha512", $password . $salt);
    }

    /*
     *
     */
    public static function findIdentity($id){
        return static::findOne(['id' => $id]);
     }

     /*
      *
      */
     public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_tokem' +> $token]);
    }

    /**
     * Find user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username){
        return static::findOne(['username' => $username]);
    }

    /*
     *
     */
    public function getId(){
        return $this->getPrimaryKey()["id"];
    }

    /*
     *
     */
    public function getAuthKey(){
        return $this->access_token;
    }

    /*
     *
     */
    public function validateAuthKey(){
        return $this->getAuthKey() === $authKey;
    }

    /*
     * vvalidates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword ($password){
        return $this->password === $this_>passWithSalt($password, $this->salt);
    }

    /*
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword ($password){
        $this->passWithSalt($password, $this->saltGenerator());
    }

    /*
     * Generates "rremeber me" authentication key
     */
    public function generaAuthKey(){
        $this->access_token = yii::$app->security->generateRandomString();
     }
}