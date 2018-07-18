<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Url;
$data = \yii\helpers\ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,customer_code AS code_fullname FROM customers")->queryAll(), 'id', 'code_fullname');
$product_type = \yii\helpers\ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,CONCAT(type_code, ' - ', description) AS product_code FROM product_type")->queryAll(), 'id', 'product_code');
$supplier = \yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name');
$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html((index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html((index + 1))
    });
});
';

$this->registerJs($js);
?>
<style>
    [v-cloak] {
        display: none;
    }

    .tables {
        display: table;
        border-collapse: separate;
        border-spacing: 2px;
        border-color: grey;

    }
    .tables {
        border-collapse: collapse !important;
    }
    .tables td,
    .tables th {
        background-color: #fff !important;
    }
    .tables th {
        padding: 0.45rem;
        vertical-align: top;
        font-size: 13px;

        /*border-top: 1px solid #e9ecef;*/
    }
    .tables td {
        padding: 0.40rem;
        vertical-align: top;
        /*border-top: 1px solid #e9ecef;*/
    }
</style>
<div class="grid simple vertical green">
    <div class="grid-title no-border">
        <div class="customer-form">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
            <div class="row">
                <div class="text-right">
                    <h3 ><strong>No.</strong> <span class="text-error"><?=@sprintf("%07d", $model->bill_number);?></span></h3>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Bill Number </label>
                        <?php echo $form->field($model, 'invoice_id')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($model, 'customers_id')->widget(Select2::classname(), [
                            'data' => $data,
                            'options' => ['placeholder' => 'Select customer ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                // 'tags' => true,
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($model, 'order_bags')->textInput( )?>
                    </div>
                </div>
            </div>


            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelsDetails[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'product_id',
                    'style',
                    'quantity',
                    'unit_price'
                ],
            ]); ?>
            <table class="tables " width="100%">
                <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">Code product</th>
                    <th width="25%">Product type(类别)</th>
                    <th width="15%">Quantity (数量)</th>
                    <th width="5%"></th>
                </tr>
                </thead>
                <tbody class="container-items">
                <?php foreach ($modelsDetails as $index => $modelDetails): ?>
                    <tr class="item">
                        <td>
                            <span class="panel-title-address"> <?= ($index + 1) ?></span></td>
                        <?php
                        if (!$modelDetails->isNewRecord) {
                            echo Html::activeHiddenInput($modelDetails, "[{$index}]id");
                        }
                        ?>

                        <td>
                            <?= $form->field($modelDetails, "[{$index}]style")->textInput()->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($modelDetails, "[{$index}]product_id")->widget(\kartik\select2\Select2::classname(), [
                                'data' => $product_type,
                                'language' => 'th',
                                'options' => [
                                    'placeholder' => 'select type...'
                                ],
                                'pluginOptions' => [
                                    "change" => "function() { log('change'); }",
                                    'allowClear' => true,
                                    //'tags' => true,
//                                    'minimumInputLength' => 2,
                                ],
                            ])->label(false); ?>

                        </td>

                        <td >
                            <?= $form->field($modelDetails, "[{$index}]quantity")->textInput(['class' => 'form-control quantity'])->label(false) ?>
                        </td>

                        <td>
                            <button type="button" class=" remove-item btn btn-danger "><i
                                        class="fa fa-remove"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button type="button" class="add-item btn btn-success"><i class="fa fa-plus"></i> เพิ่มรายการ
            </button>
            <?php DynamicFormWidget::end(); ?>
            <div class="form-group text-right">
                <?= Html::submitButton( '<i class="fa fa-save"> </i>  Update', ['class' => 'btn btn-primary']) ?>
                <a href="<?=Url::to(['shipment/with-out-order'])?>" class="btn btn-danger "><i class="fa fa-ban"> </i> ยกเลิก</a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
