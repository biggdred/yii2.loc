<?php

use  yii\widgets\ActiveForm;
?>
<?php $f = ActiveForm::begin();?>
    <?= $f->field($form, 'name')?>
    <?= $f->field($form, 'email')?>
<?php ActiveForm::end() ?>
