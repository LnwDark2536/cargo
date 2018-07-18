<?php

use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Customers;
use kartik\grid\GridView;
use common\models\Transactions;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'Top - up';
$dataCustomer = Yii::$app->db->createCommand("SELECT id,customer_code as name FROM customers")->queryAll();
$data = ArrayHelper::map(Customers::find()->all(), 'id', 'customer_code');
$data_account = Yii::$app->db->createCommand("SELECT  id,name_account FROM account")->queryAll();
$dataAccountJs = \yii\helpers\Json::encode($data_account);
$dataCustomerJs = \yii\helpers\Json::encode($dataCustomer);

?>
<style>
    [v-cloak] {
        display: none;
    }
</style>
<div class="row" id="top-up" v-cloak>
    <div class="col-md-8">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h3>เติมเงินสมาชิก <i class="fa fa-money"></i></span></h3>
            </div>
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4 text-right">
                        <label for="" class="label label-danger">อัตราแลกเปลี่ยนเงิน</label>
                        <input type="text" class="text-right form-controller" min="0" v-model="amount_jp" @input="inputCurrencylocal(amount_jp)">
                    </div>
                    <div class="col-md-4 text-right">
                        <label for="" class="label label-success">รวมจำนวนแลกได้</label>
                        <h1>{{Total | formatPrice}}</h1>
                    </div>
                </div>
                <form v-on:submit.prevent="SaveData()">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="label label-success">เลือกชื่อบัญชี</label>
                        <v-selcet v-model="account_id"
                                  label="name_account" track-by="id"
                                  :show-labels="false"
                                  :options="account" placeholder="Select Account...">
                        </v-selcet>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="label label-success">เลือกสมาขิก</label>
                        <v-selcet @select="checkCustomer"
                                  :show-labels="false"
                                  label="name" track-by="id"
                                  :options="customer" placeholder="Select Customer code...">
                        </v-selcet>
                    </div>

                    <div class="col-md-4 text-right">
                        <label for="" class="label label-success">จำนวนเงิน(Thai)</label>
                        <bs-input label="" type="text" placeholder="" name="amount_th" required icon v-model="amount_th"
                                  min="0" pattern="^\d+(\.\d{1,2})?$">
                            <span slot="before" class="input-group-addon"><span class="fa fa-money"></span></span>
                        </bs-input>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">รายละเอียด</label>
                            <textarea name="" id="" cols="5" rows="3" v-model="details" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button  type="submit" class="btn btn-success"  :disabled="!checkSend"> Save
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4 ">
        <div class="grid simple animated fadeIn" v-if="balance > 0">
            <div class="grid-title no-border">
                <h2 class="text-right">ยอดเงินคงเหลือ</h2>
            </div>
            <div class="grid-body no-border">
                <h2 style="font-weight: bold" class="text-right">{{balance | formatPrice}}</h2>

                <label for="" class="text-error bold">ประวัติการเติมล่าสุด</label>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>วันที่</th>
                            <th class="text-right">จำนวนเงิน(จีน)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(model,index) in listTopUp">
                            <td>{{index+1}}</td>
                            <td>{{model.created_at | formatDate}}</td>
                            <td class="muted bold text-success text-right">+{{model.amount_money  }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid simple">
    <div class="grid-title no-border">

    </div>
    <div class="grid-body no-border">
        <?php
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => Transactions::History(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        echo GridView::widget([
            'id' => 'kv-grid-demo',
            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
            'dataProvider' => $dataProvider,
//    'filterModel'=>$searchModel,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn',
                    'width' => '5%',
                ],

//                'created_at',
                [
                        'label'=>'วันที่เติม',
                    'width' => '15%',
                    'options' => ['style' => 'width:250px;'],

                    'pageSummary' => 'Total',
                    'pageSummaryOptions'=>['class'=>'text-right warning'],
                    'value'=>function($model){
                        return Yii::$app->formatter->asDate($model['created_at'],"dd/MM/yyyy");
                    },
                ],
                [
                    'label' => 'ชื่อบัญชี',
                    'attribute' => 'name_account',
                    'format' => 'raw',
                    'width' => '15%',
                ],
                [
                    'label' => 'customer_code',
                    'attribute' => 'customer_code',
                    'format' => 'raw',
                    'width' => '15%',
                ],

                [
                    'label' => 'จำนวนเงิน(จีน)',
                    'format' => ['decimal', 2],
                    'hAlign' => 'right',
                    'attribute' => 'amount_money',
                    'contentOptions' => ['class' => 'text-right bold text-success'],
                    'width' => '25%',
                    'headerOptions' => ['class' => 'text-center'],
                    'pageSummary' => true,
                    'pageSummaryOptions'=>['class'=>'text-error'],
                ],
                [
                    'format'=>'raw',
                    'width' => '10%',
                    //'options' => ['style' => 'width:50px;'],
                    'value' => function ($model) {
                        $date_created=substr($model['created_at'],0,10);
                        $dateNow = date("Y-m-d");
//                        return $date1;
                        if($date_created != $dateNow){
                            return '';
                        }else{
                            return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model['id']], [
                                'class' => '',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]);
                        }

                    }
                ],

            ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar' => [

            ],
            // set export properties
            'export' => [
                'fontAwesome' => true
            ],
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => false,
            'showPageSummary' => true,
            'panel' => [
                'type' => GridView::TYPE_SUCCESS,
                'heading' => 'ประวัติการทำรายการ',
            ],
            'persistResize' => false,
            'exportConfig' => false,
        ]);


        ?>
    </div>
</div>
<?php
$Url_getbalance = Url::to(['transactions/check-balance']);
$Url = Url::to(['transactions/top-up-save']);
$csrf = Yii::$app->request->getCsrfToken();
$JS = <<<JS
var app= new Vue({
    el:'#top-up',
  components: {
    'v-selcet': window.VueMultiselect.default,
      'bs-input': VueStrap.input,
   },
    data:{
     account_id:null,   
     balance:0,
     listTopUp:[],
     amount_jp:0,
     amount_th:0,
     customer_id:null,
     'details':null,
     account:$dataAccountJs,
     customer:$dataCustomerJs,
    },
      filters:{
        formatDate:function(date) {
            return moment(date).format('DD/MM/YYYY')
        },
        removeNull:function(value) {
            if(value === null){
                return  '-';
            }else {
                return value;
            }
            
        },
         formatPrice:function (num) {
            if(num ===null){
               return 0; 
            }else {
                 return parseFloat(num).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

            }
        }
    },
    computed:{
        formatPriceChe:function(value) {
          
        },
        checkSend:function() {
            if(this.customer_id !==null && this.amount_th !==0 && this.account_id !==null){
                return true;
            }else {
                  return false;
            }
        },
      Total:function() {
            var value = parseFloat(this.amount_th) / parseFloat(this.amount_jp);
       return value;
      }  
    },
    created:function() {
     //check ว่ามีค่าเก็บยัง
        if(localStorage.getItem("Currency") != null){
             var localData =localStorage.getItem("Currency");
            this.amount_jp=localData;
	     }
     },
    methods:{
        inputCurrencylocal:function(event){
             localStorage.setItem("Currency", event);
             this.amount_jp=event;
        },
        checkCustomer:function(item){
            var that = this;
            if(item != null){
                 this.customer_id = item.id;
                 $.post("$Url_getbalance",{data: item.id},function(res,status){
                     if(res.length > 0){
                          that.listTopUp=res[0].data
                          that.balance=res[1].balance
                            console.log(res[1]);
                     }
                });
            }
           
        },
        SaveData:function(){
            var totalPrice = parseFloat(this.amount_th) / parseFloat(this.amount_jp)
          
            var data = [{
                    'customer_id':this.customer_id,
                    'account_id':this.account_id,
                    'money_thai':this.amount_th,
                     'money_total':this.Total,
                     'details':this.details
                }
            ];
        var dataSend=  JSON.stringify(data);
        $.post("$Url",{data: dataSend},function(data,status){
            if(data==="success"){
                toastr.success('Loading...', 'บันทึกเติมเงินสำเร็จ !',{
                      "progressBar": true, "closeButton": true
               });
               location.reload();
            }
        });
       }
    }

});
JS;

$this->registerJs($JS);

?>
