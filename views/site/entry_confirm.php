<?php use yii\helpers\Html;?>

<p>Вы ввели следующую инфу</p>

<ul>
    <li><label>: <?= Html::encode($model->name) ?> </label></li>
    <li><label>: <?= Html::encode($model->email) ?></label></li>
</ul>