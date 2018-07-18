<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;


$data = \yii\helpers\ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,CONCAT(customer_code, '(',name,lastname,')') AS code_fullname FROM customers")->queryAll(), 'id', 'code_fullname');
$product_type = \yii\helpers\ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,CONCAT(type_code, ' - ', description) AS product_code FROM product_type")->queryAll(), 'id', 'product_code');
$supplier = \yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name');

$key = 0;
?>
<style>
    #total-font{
        font-size: 14px;
        font-weight: 600;
    }
</style>
<div class="order-index">
    <div class="row">
        <div class="col-md-12">
            <div class="grid simple vertical green">
                <div class="grid-title no-border">
                    <h4><i class="fa  fa-ambulance"> </i> Add <span class="semi-bold">Order</span></h4>
                    <div class="tools">
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                    </div>
                </div>
                <div class="grid-body no-border">
                    <div class="row-fluid ">
                        <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <h4><i class="fa fa-file"></i> Order</h4>
                                <?= $form->field($model, 'date_order')->widget(DatePicker::classname(), [
                                    'options' => ['placeholder' => 'select date...'],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy'
                                    ]
                                ]);
                                ?>
                                <?php echo $form->field($model, 'invoice_id')->textInput(['maxlength' => true])->hint(' <code>(สร้าง No. Order )</code>') ?>
                                <?php $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
                                <?php echo $form->field($model, 'customers_id')->widget(Select2::classname(), [
                                    'data' => $data,
                                    'options' => ['placeholder' => 'Select customer ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        // 'tags' => true,
                                    ],
                                ]);
                                ?>
                                <?php echo $form->field($model, 'supplier_id')->widget(Select2::classname(), [
                                    'data' => $supplier,
                                    'options' => ['placeholder' => 'Select Supplier ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tags' => true,
                                    ],
                                ])->hint(' <code>(สามารถเพิ่มร้านค้าใหม่ได้)</code>');
                                ?>
                                <?= $form->field($model, 'phone')->textInput() ?>
                                <?= $form->field($model, 'deposit')->textInput() ?>
                                <?= $form->field($model, 'payment')->inline()->radioList($model->getPayment()) ?>
                            </div>
                            <div class="col-md-9">
                                <h4><i class="fa fa-list-ol"></i> List Product</h4>
                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.container-items', // required: css class selector
                                    'widgetItem' => '.item', // required: css class
                                    'limit' => 100, // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-item', // css class
                                    'deleteButton' => '.remove-item', // css class
                                    'model' => $modelDetails[0],
                                    'formId' => 'dynamic-form',
                                    'formFields' => [
                                        'product_id',
                                        'style',
                                        'quantity',
                                        'unit_price'
                                    ],
                                ]); ?>
                                <div class="container-items">
                                    <div class="text-right">
                                        <a class="btn btn-small btn-primary add-item "><i
                                                    class="fa fa-plus-circle "></i> Add item (提高)</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 text-center">
                                            <h5>#</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5>PRODUCT_TYPE (类别)</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h5>STYLE (款号)</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h5>Quantity (数量)</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h5>UnitPrice (单价)</h5>
                                        </div>
                                        <div class="col-md-1 text-right">
                                            <h5>Total </h5>
                                        </div>

                                    </div>
                                    <?php foreach ($modelDetails as $index => $modelOrderDS): ?>
                                        <?php
                                        if (!$modelOrderDS->isNewRecord) {
                                            echo Html::activeHiddenInput($modelOrderDS, "[{$index}]id");
                                        }
                                        ?>
                                        <div class="item">

                                            <div class="row ">
                                                <div class="col-md-1 text-center"><span
                                                            class="panel-title-address"><?= ($index + 1) ?></span>
                                                </div>
                                                <div class="col-md-3"><?= $form->field($modelOrderDS, "[{$index}]product_id")->widget(\kartik\select2\Select2::classname(), [
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
                                                    ])->label(false); ?></div>
                                                <div class="col-md-2">
                                                    <?= $form->field($modelOrderDS, "[{$index}]style")->textInput()->label(false) ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?= $form->field($modelOrderDS, "[{$index}]quantity")->textInput(['class' => 'form-control quantity'])->label(false) ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?= $form->field($modelOrderDS, "[{$index}]unit_price")->textInput(['class' => ' form-control price'])->label(false) ?>
                                                </div>

                                                <div class="col-md-1 text-right">
                                                    <h5 id=<?="total[{$index}]"?>>0</h5>
                                                </div>
                                                <div class="col-md-1">

                                                <a class="remove-item btn btn-small btn-danger" >
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-md-5 col-md-offset-2" >
                                        <div class="text-right" ><h5 style="font-weight: 600">TOTAL QUANTITY(总数) &nbsp; &nbsp; <span id="sumQuantity">0</span></h5></div>
                                </div>
                                <div class="row" >
                                    <div class="col-md-6 col-md-offset-6">
                                    <table class="table " style="color: #121b24;" >
                                        <thead>
                                        <tr>
                                            <th class="text-right" id="total-font">Total Price (包括的价格)</th>
                                            <th class="text-right" id="total-font"><span id="sumAll">0</span></th>
                                        </tr>
                                        <tr>
                                            <th class="text-right text-danger" id="total-font">Deposit (定金)</th>
                                            <th class="text-right text-danger" id="total-font"><span id="deposit">0</span></th>
                                        </tr>
                                        <tr>
                                            <th class="text-right" id="total-font">total (所有包括) </th>
                                            <th class="text-right" id="total-font"><span id="totalAll">0</span></th>
                                        </tr>
                                        </thead>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <?php DynamicFormWidget::end(); ?>

                        </div>
                        <div class="form-group text-right">
                            <?= Html::submitButton($modelOrderDS->isNewRecord ? '<i class="fa fa-check"></i> Save' : 'Update', ['class' => 'btn btn-primary btn-large']) ?>
                            <?= Html::a('<i class="fa fa-refresh"></i> Reset', ['index'], ['class' => 'btn btn-danger btn-large']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4><i class="fa fa-th-list"></i> List Order</h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body table-responsive">
                    <table class="table table-hover table-condensed " id="table" width="100%">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">No Order</th>
                            <th width="25%">Customer Name</th>
                            <th width="10%">Supplier</th>
                            <th width="15%">จำนวนรายการ</th>
                            <th width="5%">deposit</th>
                            <th width="15%">total price</th>
                            <th width="15%" class="text-center">จัดการข้อมูล</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataProvider as $num => $order): ?>
                            <tr>
                                <td class="v-align-middle">
                                    <?= $num + 1 ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['invoice_id'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['code_fullname'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['supplier'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @number_format($order['count_order'], 0) ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @number_format($order['deposit'], 2) ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @number_format($order['total_price'] - $order['deposit'], 2) ?>
                                </td>
                                <td class="v-align-middle text-center">
                                    <p>
                                        <a href="<?= Url::to(['view', 'id' => $order['id']]) ?>"
                                           class="btn btn-mini btn-success"><i class="fa fa-eye"></i></a>
                                        <a href="<?= Url::to(['update', 'id' => $order['id']]) ?>"
                                           class="btn btn-mini btn-warning"><i class="fa fa-pencil"></i></a>
                                        <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $order['id']], [
                                            'class' => 'btn btn-danger btn-small',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </p>
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
</div>
<?php
$js = <<<JS
$('body').condensMenu();
var quantity=0;
var total_quantity=0;
var price=0;
var totalAll=0;
$('.quantity').on("input", function() {
        Cal();
});
$('.price').on("change", function() {
        Cal();
});
$('#orders-deposit').on("change", function() {
        Cal();
});
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html((index + 1));
        var id="#orderdetails-"+index+"-unit_price";
             var price = $(id).val();
            console.log(price);
          
     });
    $('.quantity').on("change", function() {
        Cal();
    });
    $('.price').on("change", function() {
        Cal();
    });
});
jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html((index + 1));
      
    });
     Cal();
        
});
  
function Cal() {
      var sumQuantity = 0;
     var sumPrice = 0;
     var deposit=0;
     var price=0;
     var quantityAll=0;
     var sumTotal=0;
     var ShowTotal=0;
     var total_id=0;
               var check =0;
       $('#orders-deposit').each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
            deposit=parseFloat(this.value);
            console.log(deposit);
        }
    });
      for (var i = 0; i < $('.quantity').length; i++) {
          var quantity = document.getElementById('orderdetails-'+i+'-quantity').value;
          quantityAll+=parseFloat(quantity);
          for (var j = 0; j < $('.price').length; j++) {
                var price = document.getElementById('orderdetails-'+j+'-unit_price').value;
                sumQuantity = parseFloat(quantity)*parseFloat(price);
                   total_id='total[-'+i+'-]';
            }
                       check+=sumQuantity

              // sumTotal+=sumQuantity;
               ShowTotal=parseFloat(quantity)*parseFloat(price);
            
            console.log(check);
       }
           if(total_id=="total[-0-]"){
               document.getElementById("total[0]").innerHTML=ShowTotal;
             }else {
               document.getElementById(total_id).innerHTML=ShowTotal;
           }
      document.getElementById("sumQuantity").innerHTML=quantityAll.toFixed(2);
      //มัดจำ
       document.getElementById("deposit").innerHTML=deposit.toFixed(2);
       //รวมทั้งหมด
       document.getElementById("sumAll").innerHTML=sumTotal.toFixed(2);
       //รวม ลบ กับค่ามัดจำ
        sumPrice=(deposit!=null) ? sumTotal-deposit:sumTotal;
       document.getElementById("totalAll").innerHTML= sumPrice.toFixed(2);
}
   


JS;

$this->registerJs($js);
?>
<?php
\backend\assets\DatatableAsset::register($this);

?>
