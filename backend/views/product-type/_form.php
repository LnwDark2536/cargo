<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-product-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'type_code')->textInput() ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
            <?= $form->field($model, 'chinese_description')->textarea(['rows' => 2]) ?>
            <?= $form->field($model, 'unit')->textInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
