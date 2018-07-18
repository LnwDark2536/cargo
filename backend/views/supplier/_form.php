<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'phone')->textInput() ?>

    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

    </div>
</div>
<div class="row ">
    <div class="col-md-4 ">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary btn-block' : 'btn btn-success btn-block']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>
</div>
