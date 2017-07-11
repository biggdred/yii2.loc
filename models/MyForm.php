<?php

namespace app\models;

class MyForm extends \yii\base\Model {
    public $name;
    public $email;

    //правила rules
    /**
     *
     */
    public function rules(){
        // name, email are required
        return[
            [['name','email'],'required']//name and email -заполнены
            ,// email has to be a valid email address
            ['email','email']
            ];
    }
}