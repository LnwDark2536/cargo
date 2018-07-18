<?php
$this->title = "new order";
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
$dataCustomer = Yii::$app->db->createCommand("SELECT id,customer_code as name FROM customers")->queryAll();
$supplier = ArrayHelper::getColumn(Yii::$app->db->createCommand("SELECT id,name FROM supplier")->queryAll(), 'name');
$product_type = Yii::$app->db->createCommand("SELECT id,CONCAT(type_code, ' - ', description) AS product_code FROM product_type")->queryAll();
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
    <div id="app" v-cloak>

        <div class="grid simple vertical green">
            <div class="grid-title no-border">
                <h4><i class="fa  fa-ambulance"> </i> Add <span class="semi-bold">Order</span></h4>
                <form v-on:submit.prevent="sendData()">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Date Order</label>
                                <input v-model="date_order" class="form-control" type="date" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">No order (订单单号) </label>
                                <input v-model="no_order" v-validate="'required'" class="form-control" type="text" name="no_order" placeholder="no_order">
                                <span v-show="errors.has('no_order')" class="help is-danger "><code>{{ errors.first('no_order') }}</code></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Customer(客户)</label>
                                <v-selcet  required v-model="selectedObjects"
                                          label="name" track-by="id"
                                          :options="options" placeholder="">
                                </v-selcet>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Supplier(档口) <code>(สามารถเพิ่มร้านค้าใหม่ได้)</code></label>
                                <typeahead-v1 v-bind:data="supplier" v-model="supplier_name" placeholder="">
                                </typeahead-v1>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Phone (手机)</label>
                                <input type="text" class="form-control" v-model="phone" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Deposit (定金)</label>
                                <input type="text" class="form-control" v-model="deposit" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Payment (付款方式)</label>
                                <input type="radio" v-model="payment" name="gender" value="0"> CASH
                                <input type="radio" v-model="payment" name="gender" value="1"> CREDIT
                            </div>
                        </div>
                    </div>
                    <h4><i class="fa fa-th-list"></i> List Order</h4>
                    <div class="row ">
                        <table class="tables " width="100%">
                            <thead>
                            <tr >
                                <th width="2%">#</th>
                                <th width="30%">PRODUCT_TYPE (类别)</th>
                                <th width="15%">STYLE (款号)</th>
                                <th width="15%" class="text-right">Quantity (数量)</th>
                                <th width="15%" class="text-right">UnitPrice (单价)</th>
                                <th width="10%" class="text-right">Total</th>
                                <th width="10%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(model,index) in dataList">
                                <td CLASS="text-center">{{index+1}}</td>
                                <td>
                                    <v-selcet required v-model="model.product_id"
                                              label="product_code" track-by="id"
                                              :options="dataProduct" placeholder="">
                                    </v-selcet>
                                </td>
                                <td>
                                    <input type="text" v-model="model.style" class="form-control " placeholder="">
                                </td>
                                <td>
                                    <input type="text" v-model="model.quantity" required class="form-control text-right" placeholder="">
                                </td>
                                <td>
                                    <input type="text"   v-model="model.unit_price" required class="form-control text-right" placeholder="">
                                </td>
                                <td>
                                    <input  disabled type="text"  class="form-control text-right" placeholder=""
                                           :value="Total(model.quantity ,model.unit_price)">
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-xs" @click="removeRow(index)">
                                        <i class="fa fa-remove"></i></a>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">
                                    <a @click="addItem()" class="btn btn-success " style="margin-left: 10px;"><i
                                                class="fa fa-plus-circle"> </i> เพิ่มรายการ</a>
                                </td>
                                <th colspan="1" class="text-right"> TOTAL QUANTITY(总数)</th>
                                <th class="text-right"> {{formatPrice(totalQty)}}</th>
                                <th class="text-right" id="total-font">Total Price (包括的价格)</th>
                                <th class="text-right" id="total-font">{{formatPrice(totalPrice)}}</th>
                            </tr>

                            <tr>
                                <th colspan="5" class="text-right text-danger">Deposit (定金)</th>
                                <th class="text-right text-danger" >{{deposit}}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right" id="total-font">total (所有包括) </th>
                                <th class="text-right" id="total-font">{{formatPrice(totalPrice-deposit)}}</th>
                            </tr>
                            </tfoot>
                        </table>


                    </div>
                    <div class="clearfix"></div>
                    <div class="text-right">
                        <button type="submit" :disabled="!no_order || !supplier_name || !customer_id" class="btn btn-primary "><i class="fa fa-check"> </i> Save Order
                        </button>
                        <a href="<?=Url::to(['order/index'])?>" class="btn btn-danger "><i class="fa fa-ban"> </i> ยกเลิก</a>
                    </div>
                </form>
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
                    <table class="table table-hover table-bordered " id="table" width="100%">
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
                                <td >
                                    <?= $num + 1 ?>
                                </td>
                                <td >
                                    <?= @$order['invoice_id'] ?>
                                </td>
                                <td >
                                    <?= @$order['code_fullname'] ?>
                                </td>
                                <td >
                                    <?= @$order['supplier'] ?>
                                </td>
                                <td>
                                    <?= @number_format($order['count_order'], 0) ?>
                                </td>
                                <td >
                                    <?= @number_format($order['deposit'], 2) ?>
                                </td>
                                <td >
                                    <?= @number_format($order['total_price'] - $order['deposit'], 2) ?>
                                </td>
                                <td class=" text-center">
                                    <p>
                                        <a href="<?= Url::to(['view', 'id' => $order['id']]) ?>"
                                           class="btn btn-warning btn-mini" ><i class="fa fa-eye"></i></a>
                                        <a href="<?= Url::to(['update', 'id' => $order['id']]) ?>"
                                           class=" btn btn-success btn-mini"><i class="fa fa-pencil"></i></a>
                                        <?= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $order['id']], [
                                            'class' => 'btn btn-danger btn-mini  ',
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

<?php
$Url = Url::to(['save-order']);
$csrf = Yii::$app->request->getCsrfToken();
$customerJson = Json::encode($dataCustomer, true);
$supplierJson = Json::encode($supplier, true);
$product_typeJson = Json::encode($product_type, true);
$Js = <<<JS
const dataCustomer =$customerJson;
const dataSupplier =$supplierJson;
const dataProduct_type =$product_typeJson;
//$('body').condensMenu();
Vue.use(VeeValidate);
new Vue({
    el: '#app',
	data: {
     date_order:null,
     customer_id:null,
     no_order:null,
     supplier_name:null,
     phone:null,
     deposit:0,
      payment:null,
     dataProduct:dataProduct_type,
     dataOrder:[],
  	dataList:[
  	    {
  	        product_id:2,
  	        style:null,
  	        quantity:null,
  	        unit_price:null,
  	    }
  	],
  	selectedObjects: [],
  	options: dataCustomer,
  	supplier:dataSupplier,
	},
	components: {
  	'v-selcet': window.VueMultiselect.default,
  	 TypeaheadV1:VueStrap.typeahead,
  	 
	},
 watch: {
    selectedObjects:function(newValues) {
        console.log(newValues);
        return this.customer_id=newValues.id
        
    }
  },
  mounted:function() {
    this.getDate();
   },
 computed: {
    totalQty:function() {
        return this.dataList.reduce((total, item) => {
          return total + Number(item.quantity);
        }, 0).toFixed(2);
      },
    totalPrice:function() {
        return this.dataList.reduce((total, item) => {
          return total + Number(item.quantity)*Number(item.unit_price);
        }, 0).toFixed(2);
      },
	},
  methods: {
       getDate:function(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; 
        var yyyy = today.getFullYear();
    if(dd<10) {
        dd='0'+dd;
    } if(mm<10) {
        mm='0'+mm;
    } 
   //today = mm+'-'+dd+'-'+yyyy;
    today=yyyy+'-'+mm+'-'+dd;
    this.date_order=today
 },
     formatPrice:function(value) {
        let val = (value/1).toFixed(2).replace('.', '.')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    },
       Total:function(quantity,price){
          var sum =quantity*price
          return this.formatPrice(sum);
       },
     removeRow:function (index) {
            this.dataList.splice(index, 1);
        },
   addItem:function() {
        this.dataList.push({
            product_id:null,
  	        style:null,
  	        quantity:null,
  	        unit_price:null,
        });
   },
   
 addTag :function(newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
      }
      this.options.push(tag)
      this.value.push(tag)
    },
  	customLabel:function (option) {
    return option.customer_code
    },
    sendData:function () {
   this.dataOrder=[{
       'date':this.date_order,'no_order':this.no_order,
       'customers_id':this.customer_id,'supplier':this.supplier_name,
        'phone':this.phone,'deposit':this.deposit,'payment':this.payment,
           }
      ];
   var dataOrder =JSON.stringify(this.dataOrder);
  var dataList =JSON.stringify(this.dataList);
     console.log('ทำงาน= '+dataList ) ; 
     console.log('dataOrder= '+dataOrder ) ;
      $.ajax({
          url: "$Url",
                 type:'post',
                    data: {
                     dataOrder:dataOrder,
                     dataList: dataList , 
                    _csrf : "$csrf"
          },
          success: function (data) {
              if(data==='fail'){
                   toastr.info('มีใช้งานอยู่แล้ว', 'No order (订单单号)',{
                     "progressBar": true, "closeButton": true
                });
              }else {
                   toastr.success('Loading...', 'Add Order save Success !',{
                     "progressBar": true, "closeButton": true
                });
                 setTimeout(function(){
                 window.location.reload();
                 
                 }, 1500);
              }
//             
          }
       });
   },
  
  },
})
JS;
$this->registerJs($Js);
?>
<?php
\backend\assets\DatatableAsset::register($this);

?>
