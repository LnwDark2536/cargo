<?php
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\Pjax;

$this->title = "รับสินค้าไม่มีใบสั่งของ";
$customerJson = Json::encode(Yii::$app->db->createCommand("SELECT id,customer_code as name FROM customers")->queryAll(), true);
$productJson = Json::encode(Yii::$app->db->createCommand("SELECT id,CONCAT(type_code, ' - ', description) AS product_code FROM product_type")->queryAll(), true);
$Url = Url::to(['save-with-order']);
$csrf = Yii::$app->request->getCsrfToken();
$checkMax =Yii::$app->db->createCommand("SELECT max(bill_number) as bill FROM orders WHERE type_order = 2")->queryOne();
$bill_number=intval($checkMax['bill'])+1;

?>
<style>
    .btn-none {
        display: none;
    }

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
        font-size: 16px;

        /*border-top: 1px solid #e9ecef;*/
    }

    .tables td {
        padding: 0.40rem;
        vertical-align: top;
        /*border-top: 1px solid #e9ecef;*/
    }
</style>


<div class="row-fluid">
    <div class="span12">
        <div class="grid simple grid simple vertical red ">
            <div class="grid-title">
                <h3 class="text-left"><i class="fa fa-file text-error"></i> <?= Html::encode($this->title) ?> </h3>
            </div>
            <div class="grid-body" id="app">
                <div class="text-right">
                    <h3 ><strong>No.</strong> <span class="text-error"><?=@sprintf("%07d", $bill_number);?></span></h3>
                </div>
                <form @submit.prevent="sendData()">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Bill Number</label>
                                <input v-model="bill_number" class="form-control" type="text" name="no_order"
                                       placeholder="bill number.....">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Customer(客户)</label>
                                <v-selcet required v-model="selectedObjects"
                                          label="name" track-by="id"
                                          :options="dataCustomer" placeholder="code customer ...">
                                </v-selcet>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">bags (订单单号) </label>
                            <input v-model="bags" class="form-control" type="text" name="bags" placeholder="bags...">
                        </div>
                    </div>
                    <table class="tables" width="100%">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Code product</th>
                            <th width="25%">Product type(类别)</th>
                            <th width="15%">Quantity (数量)</th>
                            <th width="5%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(model,index) in dataList">
                            <td>{{index+1}}</td>

                            <td>
                                <input type="text" class="form-control text-right" v-model="model.code_product">
                            </td>
                            <td>
                                <v-selcet required v-model="model.product_id"
                                          label="product_code" track-by="id"
                                          :options="dataProduct" placeholder="select product type...">
                                </v-selcet>
                            </td>
                            <td>
                                <input type="text" class="form-control text-right" v-model="model.quantity">
                            </td>
                            <td>
                                <div @click="removeRow(index)" class="btn btn-danger btn-small"><i
                                            class="fa fa-remove"></i></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div @click="addRow()" class="btn btn-success "><i class="fa fa-plus-circle"> </i> Add Item</div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" :disabled="selectedObjects.length >= 0"><i
                                    class="fa fa-check"></i> Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php Pjax::begin(); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="grid simple vertical green">
            <div class="grid-title">
                <h4><i class="fa fa-th-list"></i> List </h4>
            </div>
            <div class="grid-body table-responsive">
                <table class="table table-hover table-bordered " id="table" width="100%">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="12%">Bill Number</th>
                        <th width="15%">ชื่อลูกค้า</th>
                        <th width="10%" class="text-right">จำนวนชิ้น</th>
                        <th width="15%" class="text-right">ชื่อผู้รับ</th>
                        <th width="10%">วันที่รับ</th>
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
                                <?= @$order['invoice_id'] ?>
                            </td>
                            <td class="v-align-middle">
                                <?= @$order['customer_code'] ?>
                            </td>
                            <td class="v-align-middle text-right">
                                <?= @$order['order_bags'] ?>
                            </td>
                            <td class="v-align-middle text-right">
                                <?= @$order['username'] ?>
                            </td>
                            <td class="v-align-middle">
                                <?php
                                echo Yii::$app->formatter->asDate($order['created_at'], 'dd/MM/yyyy');
                                ?>
                            </td>

                            <td class="v-align-middle text-center">
                                <?= Html::a('<i class="fa fa-print text-primary"></i>', ['shipment/print-with-out', 'id' => $order['id']]) ?>
                                <?= Html::a('<i class="fa fa-eye text-success"></i>', ['shipment/view-with-out', 'id' => $order['id']]) ?>
                                <?php if($order['status']==0):?>
                                <?= Html::a('<i class="fa fa-pencil text-info"></i>', ['shipment/update-with-out', 'id' => $order['id']]) ?>
                                <?= Html::a('<i class="fa fa-trash-o text-danger"></i>', ['shipment/delete-with-out', 'id' => $order['id']], [
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
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
$JS = <<<JS
const  dataCustomerJs=$customerJson;
const  dataProductJs=$productJson;
var app = new Vue({
    el:'#app',
    data:{
        model: '',
        bags:null,
        bill_number:null,
        customer_id:null,
        dataProduct:dataProductJs,
        dataCustomer:dataCustomerJs,
        selectedObjects: [],
        dataList:[
  	    {
  	        code_product:null,
  	        product_id:null,
  	        quantity:null,
  	    }
  	],
    },
    created() {
       
    },
     watch: {
    selectedObjects:function(newValues) {
        console.log(newValues);
        return this.customer_id=newValues.id
    }
  },
    methods: {
        checkNull:function() {
           if(this.bags!=''){
               return false;
           }
        },
        addRow:function() {
          return this.dataList.push({
  	        code_product:null,
  	        product_id:null,
  	        quantity:null,
  	    })
        },
         removeRow:function (index) {
            this.dataList.splice(index, 1);
        },
        sendData:function() {
            var dataOrder=JSON.stringify([{
               'bill_number':this.bill_number,
                'customer_id':this.customer_id,
                'bags':this.bags
            }]);
             var dataList =JSON.stringify(this.dataList);
          $.ajax({
          url: "$Url",
                 type:'post',
                    data: {
                     dataOrder:dataOrder,
                     dataList: dataList , 
                    _csrf : "$csrf"
          },
          success: function (data) {
              console.log(data);
              if(data.substring (0,7)==='success'){
                toastr.info('Loading...', 'รับสินค้าแบบไม่มีใบสั่งซื้อ Success !',{"progressBar": true,"closeButton": true});
                 setTimeout(function(){
                     console.log(data)
                     var order_id =0;
                     location.href='print-with-out?id='+data.substring(7);
                 //window.location.reload();
                 }, 1500);
              }else {
                   toastr.error("Error !", "ไม่สามารถบันทึกได้",{
                           "closeButton": true,
                           "progressBar": true,
                             "timeOut": "5000",
                               "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                   })
              }
          }
       });
           // console.log('send'+dataOrder);
           // console.log('datalist'+dataList);
        }
    },
    components: {
  	'v-selcet': window.VueMultiselect.default,
  	 TypeaheadV1:VueStrap.typeahead,
	},
})
JS;

$this->registerJS($JS);
\backend\assets\DatatableAsset::register($this);
?>




