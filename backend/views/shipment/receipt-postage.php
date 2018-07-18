<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
$this->title='รับสินค้า จากไปรษณีย์';
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
            <?= Html::a('<i class="fa fa-ban"></i> cancel', ['index'], ['class' => 'btn btn-danger ']) ?>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::begin(); ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4><i class="fa fa-th-list"></i> List Data</h4>
                </div>
                <div class="grid-body table-responsive">
                    <table class="table table-hover table-bordered " id="table" width="100%">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">เลขแทรคกิ้ง</th>
                            <th width="10%">transport</th>
                            <th width="10%" class="text-right">จำนวนชิ้น</th>
                            <th width="10%">ชื่อลูกค้า</th>
                            <th width="7%">ชื่อผู้รับ</th>
                            <th width="5%">วันที่</th>
                            <th width="10%">อื่นๆ</th>
                            <th width="7%" class="text-center">จัดการ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataOrder as $num => $order): ?>
                            <tr>
                                <td class="v-align-middle">
                                    <?= $num + 1 ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['tracking_number'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['transport_name'] ?>
                                </td>
                                <td class="v-align-middle text-right">
                                    <?= @$order['bags'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['customer_code'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['username'] ?>
                                </td>

                                <td class="v-align-middle">
                                    <?php
                                    echo Yii::$app->formatter->asDate($order['created_at'], 'dd/MM/yyyy');
                                    ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['details'] ?>
                                </td>
                                <td class="v-align-middle text-center">
                                    <?php if($order['status']==0):?>
                                    <?= Html::a('<i class="fa fa-pencil text-info"></i>', ['shipment/update-postage', 'id' => $order['id']]) ?>
                                    <?= Html::a('<i class="fa fa-trash-o text-danger"></i>', ['shipment/delete-postage', 'id' => $order['id']], [
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                     <?php else:?>
                                        <i class="fa fa-check"></i>
                                <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>
<?php
$JS=<<<JS

$("#orders-tracking_number").on('change', function(event) {
     event.preventDefault();
   if(this.value.length >0 ){
      console.log('ok');
      $('#orderdetails-transport_name').focus();
     //   $(this).next('#orderdetails-transport_name').focus();
   }
           // return false;
});

JS;
$this->registerJS($JS);
\backend\assets\DatatableAsset::register($this);

?>

