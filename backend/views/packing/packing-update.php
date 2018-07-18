<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$order=\common\models\Orders::findOne(['id'=>$model->order_id]);
$order=Yii::$app->db->createCommand("SELECT c.customer_code,s.name as supplier,o.invoice_id,o.tracking_number,od.style  FROM orders o
LEFT join order_details od on o.id = od.order_id
LEFT join customers c on o.customers_id = c.id
left join supplier s on o.supplier_id = s.id WHERE od.id =:id")->bindValues(['id'=>$model->od_id])->queryOne();
?>

<div class="packing-details-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="grid simple">
        <div class="grid-body no-border invoice-body">
        <h3>Packing Update</h3>
            <div class="row">
                <div class="col-md-2">  <?= $form->field($model, 'ctn_no')->textInput() ?>
                </div>
                <div class="col-md-2">
                    <label>	ON / TN </label>
                    <input type="text" class="form-control" disabled value="<?=!empty($order['invoice_id'])?$order['invoice_id']:$order['tracking_number']?>">
                </div>
                <div class="col-md-2">
                    <label>customer code</label>
                    <input type="text" class="form-control" disabled value="<?=$order['customer_code']?>">
                </div>
                <div class="col-md-2">
                    <label>supplier</label>
                    <input type="text" class="form-control" disabled value="<?=$order['supplier']?>">
                </div>
                <div class="col-md-2">
                    <label>style</label>
                    <input type="text" class="form-control" disabled value="<?=$order['style']?>">
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'bags')->textInput() ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md-2">
                    <?= $form->field($model, 'quantity')->textInput() ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'unit_price')->textInput() ?>
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

            <div class="form-group text-right">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>