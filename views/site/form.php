<?php

use yii\helpers\Html;//include buttons
use yii\widgets\ActiveForm;
?>
<?php $f = ActiveForm::begin(); ?>
    <?= $f->field($form, 'name') ?>
    <?= $f->field($form, 'email') ?>
    <?= Html::submitButton('Отправить') ?>
<?php ActiveForm::end() ?>
