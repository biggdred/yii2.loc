<?php

namespace app\models;

use yii\base\Model;

class EntryForm extends Model
{
    public $name;
    public $email;

    public function rules()
    {
        return [
            // name and email required
            [['name', 'email'], 'required'],
            // email has to be a valid email addres
            ['email', 'email'],
        ];
    }
}