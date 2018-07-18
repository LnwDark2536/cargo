<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="packing-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'ctn_no')->textInput() ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'order_id')->textInput(['disabled'=>true]) ?>

        </div>
        <div class="col-md-2">
            <label>Shop</label>
            <input type="text" class="form-control" value="<?= $order['supplier'] ?>" disabled="true">
        </div>
        <div class="col-md-2">
            <label>Style No.</label>
            <input type="text" class="form-control" value="<?= $order['style'] ?>" disabled="true">
        </div>
        <div class="col-md-2">
            <label>Commodity</label>
            <input type="text" class="form-control" value="<?= $order['product_code'] ?>" disabled="true">

        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'quantity')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'unit_price')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'bags')->textInput() ?>

        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'kg')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'width')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'length')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'height')->textInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
