<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
$this->title='Update';
$data = \yii\helpers\ArrayHelper::map(\common\models\Customers::find()->all(), 'id', 'customer_code');
$supplier = \yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name');

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="grid simple">
        <div class="grid-title no-border">
            <h3>รับสินค้า <span class="semi-bold">จากไปรษณีย์</span></h3>
        </div>
        <div class="grid-body no-border" >
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($model, 'tracking_number')->textInput(['maxlength' => true, 'autofocus' => true])->label('Tacking Number') ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($modelOD, 'transport_name')->textInput(['maxlength' => true]) ?>
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
                        <?php echo $form->field($modelOD, 'bags')->textInput(['maxlength' => true])->label('Bag') ?>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $form->field($modelOD, 'details')->textarea([
                            'maxlength' => true,
                            'rows' => 3
                        ])->label('อื่นๆ') ?>

                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> Save' : 'Update', ['class' => 'btn btn-primary ']) ?>
                <?= Html::a('<i class="fa fa-ban"></i> cancel', ['shipment/receipt-postage'], ['class' => 'btn btn-danger ']) ?>
            </div>

        </div>
    </div>
<?php ActiveForm::end(); ?>