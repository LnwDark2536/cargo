<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Orders;
$dataBank= ArrayHelper::getColumn(Yii::$app->db->createCommand("SELECT DISTINCT(bank)  FROM orders 
WHERE bank is not null ")->queryAll(),'bank');
$dataAccount_name= ArrayHelper::getColumn(Yii::$app->db->createCommand("SELECT DISTINCT(account_name)  FROM orders 
WHERE account_name is not null ")->queryAll(),'account_name');
$dataAccount_number= ArrayHelper::getColumn(Yii::$app->db->createCommand("SELECT DISTINCT(account_number)  FROM orders 
WHERE account_number is not null ")->queryAll(),'account_number');
$bankJson = Json::encode($dataBank,true);
$account_nameJson = Json::encode($dataAccount_name,true);
$account_numberJson = Json::encode($dataAccount_number,true);
$this->title = "รายละเอียดการรับสินค้า";
$models = new \common\models\ReceiveDetails();
$totalAll = 0;
$deposit = 0;
?>
<style>
    [v-cloak] {
        display: none;
    }

</style>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple horizontal green">
            <div class="grid-title">
                <h3 class="text-left"><i class="fa fa-file text-success"></i> รายละเอียดการรับสินค้าแบบมีใบสั่งซื้อ (收货明细)</h3>
            </div>
            <div class="grid-body ">
                <div id="app-receive" v-cloak>
                    <div class="text-right" >
                        <button class="btn btn-primary"  v-on:click="SaveDelivery()"><i class="fa fa-check"></i>
                            save receipts
                        </button>
                        <?= Html::a('<i class="fa fa-ban"></i>  Cancel', ['shipment/received'], ['class' => 'btn btn-danger  ']) ?>
                    </div>
                <div class="row">
                    <div class="col-md-4">
                                <table class="table ">
                                    <tbody>
                                    <tr>
                                        <th>Customer(客户)</th>
                                        <td>
                                            {{dataOrder.customer_code}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Supplier(档口)</th>
                                        <td>
                                            {{dataOrder.supplier}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>PAYMENT (付款方式)</th>
                                        <td>
                                            {{dataOrder.payment}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone (手机)</th>
                                        <td>
                                            {{dataOrder.phone}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                    </div>
                    <div class="col-md-4">
                            <table class="table ">
                                <tbody>
                                <tr>
                                    <th>No order (订单单号)</th>
                                    <td><input type="text" min="0" v-model="dataOrder.invoice_id" class="form-control "></td>
                                </tr>
                                <tr>
                                    <th>Deposit (定金)</th>
                                    <td><input type="text" min="0" v-model="dataOrder.deposit" class="form-control " disabled></td>
                                </tr>
                                <tr>
                                    <th>created date</th>
                                    <td>{{frontEndDateFormat(dataOrder.date_order)}}</td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table ">
                            <tbody>
                            <tr>
                                <th>BANK (银行)</th>
                            <td>
                                <typeahead-v1 v-model="dataOrder.bank" v-bind:data="bank" >
                                </typeahead-v1>
                            </td>
                            </tr>
                            <tr>
                                <th>ACCOUNT NAME (开户人)</th>
                                <td>
                                    <typeahead-v2 v-model="dataOrder.account_name" v-bind:data="account_name" >
                                    </typeahead-v2>
                                </td>
                            </tr>
                            <tr>
                                <th>ACCOUNT NUMBER (帐户/卡号)</th>
                                <td>
                                    <typeahead-v3 v-model="dataOrder.account_number" v-bind:data="account_number" >
                                    </typeahead-v3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                    <div class="table-responsive">
                        <table class="table  table-bordered table-responsive" width="100%">
                            <thead>
                            <tr class="success">
                                <th width="3%">#</th>
                                <th width="25%">Product Type (类别)</th>
                                <th width="10%">Style (款号)</th>
                                <th width="5%" class="text-right">Unit</th>
                                <th width="15%" class="text-right">UnitPrice (单价)</th>
                                <th width="5%" class="text-center">RECEIVE</th>
                                <th width="5%" class="text-center">weight</th>
                                <th width="10%" class="text-right">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(model,index) in dataDetails">
                                <td>{{index+1}}</td>
                                <td>{{model.product_type}}</td>
                                <td>{{model.style}}</td>
                                <td class="text-right">{{model.quantity}}</td>
                                <td class="text-right">{{model.unit_price}}</td>

                                <td><input type="text" min="0" v-model="model.amount" class="input-sm text-right"></td>
                                <td><input type="text" min="0" v-model="model.weight" class="input-sm text-right"></td>
                                <td class="text-right">{{model.amount*model.unit_price}}</td>
                            </tr>
                            <tr class="warning">
                                <td colspan="3" class="text-center footer-total" >ยอดรวม</td>
                                <td  class="text-right footer-total">{{SumUnit |formatNumber}}</td>

                                <td  colspan="2"   class="text-right footer-total">{{SumAmount |formatNumber}}</td>
                                <td class="text-right footer-total">{{SumWeight | formatNumber}}</td>
                                <td  class="text-right footer-total">{{SumPrice |formatNumber}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <h4><i class="fa fa-list-ul"></i> ประวัติ receive & weight</h4>
                <div class="row">
                    <div class="col-md-6">
                        <h4>จำนวนรับ</h4>
                        <?= GridView::widget([
                            'dataProvider' => $dataLogReceive,
                            'pjax' => false,
                            'bordered' => true,
                            'striped' => false,
                            'condensed' => false,
                            'responsive' => true,
                            'hover' => false,
                            'showPageSummary' => true,
                            'pageSummaryRowOptions' => ['class' => 'text-right kv-page-summary warning'],
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                'product_type',
                                [
                                    'label' => 'receive',
                                    'format' => ['decimal', 0],
                                    'headerOptions' => ['class' => 'text-right'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'attribute' => 'receive_amount',
                                    'pageSummary' => true,

                                ],
                                [
                                    'label' => 'Unit Price',
                                    'format' => ['decimal', 0],
                                    'headerOptions' => ['class' => 'text-right'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'attribute' => 'unit_price',
                                ],
                                [
                                    'label' => 'total Price',
                                    'format' => ['decimal', 0],
                                    'pageSummary' => true,
                                    'headerOptions' => ['class' => 'text-right'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'value' => function ($model) {
                                        return $model['receive_amount'] * $model['unit_price'];
                                    },
                                ],
                                [
                                    'label' => 'Created',
                                    'attribute' => 'created_at',
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'format' => ['date', 'php:d/m/Y'],
                                    //'contentOptions' => ['style' => 'width:100px;']
                                ],
                                [
                                    'label' => '#',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'value' => function ($model) {
                                        return Html::a('<i class="fa fa-trash-o"></i>', ['delete-receive', 'id' => $model['id'], 'p' => $model['order_id']], [
                                            'class' => 'text-danger',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                ],
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-6">
                        <h4>จำนวนน้ำหนัก</h4>
                        <?= GridView::widget([
                            'dataProvider' => $dataLogWeight,
                            'pjax' => true,
                            'bordered' => true,
                            'striped' => false,
                            'condensed' => false,
                            'responsive' => true,
                            'responsiveWrap' => false,
                            'hover' => false,
                            'pageSummaryRowOptions' => ['class' => 'text-right kv-page-summary warning'],
                            'showPageSummary' => true,
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                'product_type',
                                [
                                    'label' => 'weight',
                                    'headerOptions' => ['class' => 'text-right'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'attribute' => 'weight_amount',
                                    'pageSummary' => true
                                ],
                                [
                                    'label' => 'Created',
                                    'attribute' => 'created_at',
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'format' => ['date', 'php:d/m/Y'],
                                    //'contentOptions' => ['style' => 'width:100px;']
                                ],
                                [
                                    'label' => 'จัดการข้อมูล',
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'text-center'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'value' => function ($model) {
                                        return Html::a('<i class="fa fa-trash-o"></i>', ['delete-weight', 'id' => $model['id'], 'p' => $model['order_id']], [
                                            'class' => 'text-danger',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                ],
                            ]
                        ]); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
$order = json_encode($modelOrder, true);
$order_details = json_encode($RawData, true);
$Url = Url::to(['save-weightamount']);
$csrf = Yii::$app->request->getCsrfToken();
$order_id = Yii::$app->request->get('id');
$UrlIndex = Url::to(['received']);
$UrlPrint = Url::to(['delivery-print', 'id' => $order_id]);
$depositCheck = $model_order->deposit === null ? 0 : $model_order->deposit;

$Js = <<<JS
$('body').condensMenu();
const dataBank =$bankJson;
const dataAccount_name =$account_nameJson;
const dataAccount_number =$account_numberJson;
const orderDetails =$order_details;
const depositJson = $depositCheck;
const orderJson =$order;

var receive = new Vue({
  el: '#app-receive',
  data: {
      bank:dataBank,
      account_name:dataAccount_name,
       account_number:dataAccount_number,
     dataOrder:orderJson,
    dataDetails:orderDetails, 
    deposit:depositJson,
  },
  components: {
    TypeaheadV1:VueStrap.typeahead,
    TypeaheadV2:VueStrap.typeahead,
    TypeaheadV3:VueStrap.typeahead,
  },
filters:{
      formatNumber:function(value) {
        let val = (value/1).toFixed(2).replace('.', '.')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    },
    },
   computed:{
      SumUnit:function(){
      var unit_price = 0 ;
      var Total=0;
      var order_details=this.dataDetails;
        for (var i = 0; i < order_details.length; i++) { 
            unit_price +=parseFloat(order_details[i].quantity);
         }
          return unit_price
      },
       SumPrice:function(){
          var order_details=this.dataDetails;
        return order_details.reduce((sumAmount,item)=>{
            return  sumAmount + Number(item.amount)*Number(item.unit_price);
        },0)
     
      },
      SumAmount:function(){
      var order_details=this.dataDetails;
        return order_details.reduce((sumAmount,item)=>{
            return  sumAmount +Number(item.amount);
        },0)
      },
      SumWeight:function(){
      var order_details=this.dataDetails;
      return order_details.reduce((sumwd,item)=>{
            return sumwd+Number(item.weight);
      },0)
      }
   },
    methods:{
      	frontEndDateFormat: function(date) {
        		return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
        	},
      checkWeight:function(data){
             if(data === null){
                this.Checkweight=true;
             }else {
                this.Checkweight=false;
             }
      },
    
      SaveDelivery:function() {
        var order_details=JSON.stringify(this.dataDetails);
        var order_send=JSON.stringify(this.dataOrder);
        console.log(order_send);
         $.ajax({
          url: "$Url",
                 type: 'post',
                    data: {
                     order_id:$order_id,
                     dataOrder: order_send ,
                    dataList: order_details , 
                    _csrf : "$csrf"
          },
          success: function (data) {
              if(data==='fails'){
                  new PNotify({
                    title: 'Error!',
                    text:'Receipts and weight should not be blank !',
                    type: 'error',
                    delay: 3000,
                 });
              }else {
                  toastr.success('กำลังบันทึกโปรดรอสักครู่...', 'บันทึกรับสินค้ามีใบสั่งของ !',{
                     "progressBar": true, "closeButton": true
                });
                
                 setTimeout(function(){
                     location.href="$UrlPrint";
                  // window.open("$UrlPrint","_blank");
                  // window.location.assign("$UrlIndex");
                  
                  }, 3010);
              }
          }
       });
    }  
  },
})
JS;
$this->registerJs($Js);
?>

