<?php
$this->title = "ชำระสินค้า";
use common\models\Orders;
use common\models\Transactions;
use yii\helpers\Url;

$order = Orders::findOne(['id' => $id]);
$bc = Transactions::BalanceCustomer($orderModel['customers_id']);
$totalAll = 0;
$deposit = floatval($order->deposit);
?>
<style>
    [v-cloak] {
        display: none;
    }
</style>
<div class="row" id="app-pay">
    <div class="col-md-8">
        <div class="grid simple horizontal grey">
            <div class="grid-title ">
                <div class="text-right">
                    <?= @Orders::getTypeNameLabel($orderModel['type_order']) ?>
                </div>
            </div>
            <div class="grid-body">
                <h4><i class="fa fa-list"></i> List Order</h4>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>วันที่สร้าง</th>
                                <th><?= @Yii::$app->formatter->asDate($orderModel['date_order'], 'd/MM/Y') ?></th>
                            </tr>
                            <tr>
                                <th>No order (订单单号)</th>
                                <th><?= $orderModel['invoice_id'] ?></th>
                            </tr>
                            <tr>
                                <th>Customer(客户)</th>
                                <th><?= @$orderModel['customer_code'] ?></th>
                            </tr>
                            <tr>
                                <th>Supplier(档口)</th>
                                <th><?= @!empty($orderModel['supplier']) ? $orderModel['supplier'] : '-' ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-hover ">
                            <thead>
                            <tr>
                                <th>Phone (手机)</th>
                                <td><?= @$orderModel['phone'] ?></td>
                            </tr>
                            <tr>
                                <th>Deposit (定金)</th>
                                <td><?= @$orderModel['deposit'] ?></td>
                            </tr>
                            <tr>
                                <th>Payment (付款方式)</th>
                                <th><?=
                                    @Orders::PaymentCheck($orderModel['payment']);
                                    ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <h4>รายการสินค้า</h4>
                <table class="table  table-hover table-responsive table-bordered" width="100%">
                    <thead>
                    <tr>
                        <th width="5%"></th>
                        <th width="25%">(类别)</th>
                        <th width="12%">(款号)</th>
                        <th width="15%" class="text-right">(数量)</th>
                        <th width="15%" class="text-right">(单价)</th>
                        <th width="10%" class="text-right"></th>
                    </tr>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Product Type</th>
                        <th width="12%">Style</th>
                        <th width="15%" class="text-right">Quantity</th>
                        <th width="15%" class="text-right">UnitPrice</th>
                        <th width="10%" class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dataProvider as $index => $data): ?>
                        <?php $totalAll += intval($data['quantity'] * $data['unit_price']) ?>
                        <tr>
                            <td><?= @$index + 1 ?></td>
                            <td><?= @$data['product_code'] ?></td>
                            <td><?= @$data['style'] ?></td>
                            <td class="text-right"><?= @number_format($data['quantity'], 0) ?></td>
                            <td class="text-right"><?= @number_format($data['unit_price'], 0) ?></td>
                            <td class="text-right"><?= @number_format($data['quantity'] * $data['unit_price'], 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="grid simple horizontal grey">
            <div class="grid-body">
                <h3><i class="glyphicon glyphicon-piggy-bank fa-2x"></i> เงินคงเหลือ <span
                            class="text-success semi-bold" style="font-weight: bold">{{balance}}</span></h3>
                <hr>
                <div class="text-right">
                    <h3>ยอดเงินต้องชำระ</h3>
                    <h1 v-if="inputPay > 0"><span>{{SumPrice}} </span>-<span class="text-danger">{{inputPay}}</span> =
                        <span class="text-success">{{checkSumInput}}</span></h1>
                    <h1 style="font-weight: bold" v-else>{{SumPrice}} </h1>

                </div>

                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-primary " aria-pressed="true" @click="SavePayALl()"
                            :disabled="balance <= 0"><i class="fa fa-cart-arrow-down"> </i> ชำระทั้งหมด
                    </button>
                    <button type="button" :class="classPaylist" aria-pressed="false" @click="payList = !payList"
                            :disabled="balance <= 0">
                        <span v-if="!payList"><i class="fa fa-list"> </i> ชำระแบบย่อย </span>
                        <span v-if="payList"><i class="fa fa-remove"> </i> ปิดชำระแบบย่อย </span>
                    </button>
                </div>
                <hr>
                <div class="" v-if="payList">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">&#165;</div>
                                <input type="text" class="form-control text-right" autofocus v-model="inputPay"
                                       placeholder="จำนวนเงิน...">
                            </div>
                        </div>
                        <button class="btn btn-primary" :disabled="!CheckInput" @click="SavePayList()">บันทึกจ่ายย่อย
                        </button>
                    </div>

                    <div class="" v-if="dataPayList.length > 0">
                        <h5><i class="fa fa-list-alt"></i> ประวัติการชำระ</h5>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่ชำระ</th>
                                <th>จำนวนเงิน</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(model,index) in dataPayList">
                                <td>{{index+1}}</td>
                                <td>{{model.created_at | formatDate}}</td>
                                <td class="text-error ">
                                    <span class="semi-bold"> {{model.amount_money}} </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$csrf = Yii::$app->request->getCsrfToken();
$UrlPayAll = Url::to(['order/save-pay-all']);
$UrlPayLIst = Url::to(['order/save-pay-list']);
$Paid_Amount = Transactions::PaidAmount($order->id, $order->customers_id);
$PayList = \yii\helpers\Json::encode(Transactions::PayList($order->id, $order->customers_id));

$SumPrice = $totalAll - $deposit;
$JS = <<<JS
const dataBalance = $bc;
const order_id = $id;
const Pay_Order=$SumPrice -$Paid_Amount; 
var app = new Vue({
  el: '#app-pay',
  data: {
    payAll:true,
    payList:false,
    balance:dataBalance,
    message: 'Hello Vue!',
    inputPay:0,
    SumPrice:Pay_Order,
    Paylist_Order:$Paid_Amount,
    dataPayList :$PayList
  },
  filters:{
         formatDate:function(date) {
            return moment(date).format('DD/MM/YYYY')
        },
         formatPrice:function (num) {
                return parseFloat(num).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
  },
  computed:{
     checkSumInput:function() {
         var Total =this.SumPrice - this.inputPay
       if( Total>=0){
           return Total;
       }else {
           return 0;
       }
     },
     CheckInput:function() {
       if(this.inputPay != 0 && this.inputPay >=0 ) {
           if(this.inputPay <= this.SumPrice){
                   return true;
           }
       }else {
           return false;
       }
     },
    classPaylist:function() {
        if(!this.payList){
            return 'btn btn-success'
        }else{
            return 'btn btn-danger'
        } 
    }  
  },
  methods: {
    SavePayList:function() {
       var SendDataList = {
             order_id:order_id,
             amount:this.inputPay,
         };
       var data =JSON.stringify(SendDataList);
    $.ajax({
          url: "$UrlPayLIst",
                 type:'post',
                    data: {
                    data:data,
                    _csrf : "$csrf"
          },
          success: function (data) {
              if(data=="success"){
               toastr.success('Loading...', 'บันทึกจ่ายเงินสำเร็จ !',{
                      "progressBar": true, "closeButton": true
               });
                setTimeout(function(){
                 window.location.reload();
               }, 1200);
              }else {
                   toastr.error('ยอดเงินบัญชีไม่เพียงพอ...', 'ไม่สามารถบันทึกจ่ายได้ !',{
                      "progressBar": true, "closeButton": true
               });
              }
               
          }
         });
    },
    SavePayALl:function() {
         var SendData = {
             order_id:order_id,
             amount:this.SumPrice,
         };
         var data =JSON.stringify(SendData);
         console.log(data);
         
         $.ajax({
          url: "$UrlPayAll",
                 type:'post',
                    data: {
                    data:data,
                    _csrf : "$csrf"
          },
          success: function (data) {
              if(data=="success"){
               toastr.success('Loading...', 'บันทึกจ่ายเงินสำเร็จ !',{
                      "progressBar": true, "closeButton": true
               });
                setTimeout(function(){
                 window.location.reload();
               }, 1200);
              }else {
                   toastr.error('ยอดเงินบัญชีไม่เพียงพอ...', 'ไม่สามารถบันทึกจ่ายได้ !',{
                      "progressBar": true, "closeButton": true
               });
              }
               
          }
         });
       
    }
  }
  
})
JS;

$this->registerJS($JS);

?>
