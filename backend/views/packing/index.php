<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


use yii\helpers\Url;
$customer_id = \yii\helpers\ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,customer_code AS code_fullname FROM customers")->queryAll(), 'id', 'code_fullname');
$this->title = 'Packings';
//$this->params['breadcrumbs'][] = $this->title;
Yii::$app->user->id
?>
    <style xmlns="http://www.w3.org/1999/html">
        [v-cloak] {
            display: none;
        }

        .order-move {
            /*left: 440px;*/
            bottom: 142px;
            z-index: 99;
        }

        .show-ctn {
            z-index: 102;
            top: 60px;
            right: 5px;
            position: fixed;
        }
        .show-cal {
            z-index: 100;
            top: 241px;
            width: 451px;
            right: 5px;
            position: fixed;
        }
    </style>
    <div class="packing-index">
        <h4> Packing</h4>

        <div id="panking" v-cloak>
            <div class="row">
                <div class="col-md-7" v-if="!cancelBox">
                    <div class="grid simple " >
                        <div class="grid-title no-border">

                        </div>
                        <div class="grid-body no-border ">

                            <h3  ><strong>1.เลือกรายการสินค้า  </strong>
                            </h3>
                            <div class="text-right"><span class="label label-important">มี {{filterOrder.length}} รายการ</span></div>
                            <div class="row form-row">
                                <div class="col-md-7">
                                    <input type="text" class="form-control" v-model="search"
                                           placeholder="Search Code customer No Order,supplier">
                                </div>
                                <div class="col-md-5">
                                    <select name="form3Gender" class="select2 form-control" v-model="search_type">
                                        <option value="0" selected>แสดงทั้งหมด</option>
                                        <option value="1">รับมีใบเสร็จ</option>
                                        <option value="2">รับไม่มีใบเสร็จ</option>
                                        <option value="3">รับทางไปรษณีย์</option>
                                    </select>
                                </div>
                            </div>

                            <table class="table table-hover table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">ON / TN</th>
                                    <th width="20%" >code custromer</th>
                                    <th width="20%">Supplier(档口)</th>
                                    <th width="5%"> วันที่รับ</th>
                                    <th width="5%" class="text-center">
                                        <a class="btn btn-mini btn-primary" @click="selcetAll(filterOrder)"   v-if="!checkOne" v-show="!checkAll"><i class="fa fa-check " ></i> All</a>
                                    <i class="fa fa-check fa-2x"  v-else="checkOne"></i>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(model,index) in filterOrder.slice(0, 10)"    >
                                    <td>{{index+1}}</td>
                                    <td>{{model.invoice_id}}</td>
                                    <td>{{model.customer_code}}</td>
                                    <td>{{model.name}}</td>
                                    <td>{{model.updated_at |formatDate}}</td>
                                    <td :class="CheckType(model)">
                                        <a class="btn btn-mini btn-primary" disabled v-if="CheckAdd(model.id) || checkAll"><i class=" fa fa-check-circle"></i></a>
                                        <a  class="btn btn-mini btn-success" @click="selcetOrder(model),CheckAdd(model.id)"
                                                v-else><i class=" fa fa-plus-circle"> </i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-5" v-if="addOrder.length > 0 " v-show="!cancelBox">
                    <div class="grid simple " >
                        <div class="grid-title no-border">

                        </div>
                        <div class="grid-body no-border ">
                            <h3 ><strong>2.แสดงรายการที่เลือก {{addOrder.length}} </strong></h3>
                            <div class="text-right">
                                <button  class="btn  btn-success " @click="send()"><i class="fa fa-send"></i> ยืนยันการเลือก ({{addOrder.length}})</button>
                            </div>
                            <table class="table table-bordered " width="100%">
                                <thead>
                                <tr>
                                    <th  width="5%">#</th>
                                    <th width="20%">customer_code</th>
                                    <th width="25%">Supplier(档口)</th>
                                    <th width="10%">
                                    <button class="btn btn-small btn-danger" @click="removeAll()"><i class="fa fa-tag" ></i> </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody v-for="(data,index) in addOrder">
                                <tr>
                                    <td>{{index+1}}</td>
                                    <td>{{data.customer_code}}</td>
                                    <td>{{data.name}}</td>
                                    <td>
                                        <button class="btn btn-mini btn-danger" @click="removeAdd(data)"><i class="fa fa-remove"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-12" >
                    <div class="alert  alert-danger  show-ctn" v-show="!hideLift">
                        <p class="text-success">CTN / NO</p>
                        <p style="font-size:90px;" class="text-center">{{showCtn}}</p>
                    </div>
                    <div class="grid simple " v-show="!hideLift" >
                        <div class="grid-title no-border">
                            <div class="row">

                                <div class="col-md-6 text-right">
                                <button class="btn btn-danger" v-on:click="full = !full" v-if="full"><i class="fa fa-compress" aria-hidden="true"> </i> ย่อตาราง</button>
                                </div>
                            </div>
                        </div>
                        <div class="grid-body no-border">
                            <div class="col-md-12">
                                <h3><strong >3.กรอกรายการข้อมูลสินค้า</strong>
                                </h3>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="alert alert-warning alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="text-error"><strong>   <i class="fa fa-bell-slash" aria-hidden="true"></i> กรณีต้องการรวม Style </strong></h4>
                                            <p class="text-error">  - กำหนดเลข CTN ให้เหมือนกัน </p>
                                            <p class="text-error">  - ใส่เลข Kg แค่ช่องเดียว</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <button type="reset" class="btn btn-success btn-small" @click="cancelAll()"> <i class="fa fa-remove fa-4x"></i>
                                            <br>ยกเลิกการทำรายการนี้</button>

                                    </div>
                                </div>

                            </div>
                                    <table class="table  table-bordered" >
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center text-danger">Ctn/No</th>
                                            <th class="text-center" >Order No.</th>
                                            <th class="text-center"  style="display: none">Shop</th>
                                            <th class="text-center" >Style NO.</th>
                                            <th class="text-center" >Cdt /TN</th>
                                            <th class="text-center text-danger" >bags</th>
                                            <th class="text-center"  >Quantity</th>
                                            <th class="text-center" >U/price</th>
                                            <th class="text-center"  v-if="!hide">รวมเงิน</th>
                                            <th  class="text-center text-danger" v-if="!hide">KG.</th>
                                            <th v-if="hide" class="text-center warning text-danger">Width</th>
                                            <th v-if="hide" class="text-center warning text-danger">Length</th>
                                            <th v-if="hide"class="text-center warning text-danger">Height</th>
                                            <th class="text-center text-danger" >ลูกบาศก์เมตร</th>
                                            <th width="50px"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(data,index) in listData" style="cursor:pointer"
                                            @change="sendCtn(data,index)"
                                            @click="sendCtn(data,index)" :class="check(data.check_row)">
                                            <td>{{index+1}}</td>

                                            <td class="text-center col-md-1" style="color: red;" >
                                                <bs-input v-model="data.ctn" type="text" min="0" required icon></bs-input>
                                            </td>
                                            <td>
                                                <strong>{{data.invoice_id | removeNull}}</strong>
                                            </td>
                                            <td  ><strong>{{data.style}}</strong></td>
                                            <td  ><strong>{{data.type_code}}</strong></td>
                                            <td class="col-md-1"  >
                                                <bs-input v-model="data.bags"   type="text" min="0"  required icon></bs-input>
                                            </td>
                                            <td class="col-md-1">
                                                <input type="text" class="form-control text-right" v-model="data.quantity">
                                            </td>
                                            <td class="col-md-1">
                                                <input type="text" class="form-control text-right" :disabled="data.checkAdd===1" v-model="data.unit_price">
                                            </td>

                                            <td class="text-right col-md-1"  v-if="!hide">

                                                <input type="text" class="form-control text-right" :value="data.quantity*data.unit_price" disabled>
                                            </td>
                                            <td class="col-md-1" v-if="!hide">
                                                <bs-input v-model="data.kg"   type="text" min="0"  required icon></bs-input>
                                            </td>
                                            <td class="col-md-1" v-if="hide">
                                                <input type="text" class="form-control text-right"  v-model="data.width"   >
                                            </td>
                                            <td class="col-md-1" v-if="hide">
                                                <input type="text" class="form-control text-right"  v-model="data.length"    >
                                            </td>
                                            <td class="col-md-1" v-if="hide">
                                                <input type="text" class="form-control text-right"  v-model="data.height"    >
                                            </td>
                                            <td  width="150px">
                                                <div class="input-group" v-if="!hide">
                                                    <input type="text" class="form-control" :value="sumTotalCal(data.width,data.length,data.height)" disabled>
                                                    <span class="input-group-addon info "  v-on:click="hide = !hide">
                                                      <span class="arrow"></span >
                                                  <i class="fa fa-calculator"></i>
                                                  </span>
                                                </div>
                                                <div class="input-group" v-if="hide">
                                                    <input type="text" class="form-control" :value="sumTotalCal(data.width,data.length,data.height)" disabled>
                                                    <span class="input-group-addon danger"  v-on:click="hide = !hide">
                                                      <span class="arrow"></span >
                                                  <i class="fa fa-remove"></i>
                                                  </span>
                                                </div>
                                            </td>
                                        <td class="text-right" >
                                            <a class="btn btn-primary btn-mini"  v-on:click="addRow(index,data)">
                                                <i class="fa fa-plus "></i>
                                            </a>
                                            <a class="btn btn-danger btn-mini"  v-if="data.checkAdd===1" @click="removeRow(index)">
                                                <i class="fa fa-remove "></i>
                                            </a>
                                        </td>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-center" colspan="6"><strong>Total</strong></td>
                                            <td class="text-right" ><strong>{{sumBags}}</strong></td>
                                            <td class="text-right" colspan="2"><strong>{{Numberformat(totalPrice)}}</strong>
                                            </td>
                                            <td class="text-right"><strong>{{Numberformat(totalKG)}}</strong></td>
                                            <td class="text-right" ><strong>{{Numberformat(totalCM)}}</strong></td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <div class="text-right">
                                        <button  @click="savePacking()"  class="btn btn-success btn-lg btn-large" :disabled="disableSend"><i class="fa fa-save" ></i> Save Packing
                                        </button>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$Url=Url::to(['save-packing']);
$OrderList=Url::to(['order-list']);
$UrlPackinglist=Url::to(['packing-lists']);
$csrf = Yii::$app->request->getCsrfToken();

$js = <<<JS
const  dataOrderJson =$order;
$('body').condensMenu();
 $('[data-toggle="popover"]').popover(); 
//Vue js 
Vue.filter('json', value => { return JSON.stringify(value, null, 2) } )
new Vue({
 el: '#panking',
  data: {
    dataOrder:dataOrderJson,
    addOrder:[],
    mai:'',
    search:'',
    search_type:0,
    ctn:null,
    checkAll:false,
    checkOne:false,
    checkRow:false,
    showCtn:0,
    hideLift:true,
    AllTotal:0,
    listData:[],
    dataRow:[],
    show:false,
    hide:false,
    auto:true,
    cancelBox:false,
    full:false
  },
    components: {
      tooltip: VueStrap.tooltip,
      'bs-input': VueStrap.input,
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
            
        }
    },
   created:function() {
     //check ว่ามีค่าเก็บยัง
      
     },
  
  computed: {
    disableSend:function() {
        var data = this.listData;
        var check =false;
        for(var key in data){
            if(data[key].ctn === null || data[key].ctn === "" || data[key].bags === null || data[key].bags === ""){
              check = true;
            }
        }
      return check;
    } ,
    sumBags:function() {
      return this.listData.reduce((sumB,item)=>{
            return sumB + Number(item.bags);
      },0).toFixed(2);
    },
   totalKG:function() {
          var  listData = this.listData;
          return listData.reduce((SumKG, item) => {
          return SumKG + Number(item.kg);
        }, 0).toFixed(2);
    },
    totalCM:function() {
        var  listData = this.listData;
        var total =0;
         for (var i =0 ; i < listData.length; i++){
             var width = parseFloat(listData[i].width);
              var length = parseFloat(listData[i].length);
              var height = parseFloat(listData[i].height);
             total+=this.sumTotalCal(width,length,height);
         }
          return this.Numberformat(total);
    },
     totalPrice() {
        var  listData = this.listData;
          return listData.reduce((Sumtotal, item) => {
          return Sumtotal + Number(item.quantity)*Number(item.unit_price);
        }, 0).toFixed(2);
    },
    filterOrder(){  
       var search_data = this.dataOrder;
        searchString = this.search;
         searchTypes = parseInt(this.search_type);
            if(!searchString && !searchTypes){
                return search_data;
            } 
            searchString = searchString.trim().toLowerCase();
             search_data = search_data.filter(item => {
                 if ((!searchString || item.customer_code.toLowerCase().indexOf(searchString) !== -1 || item.invoice_id.toLowerCase().indexOf(searchString) !== -1 
                 || item.name.toLowerCase().indexOf(searchString) !== -1)
                    && (!searchTypes || item.type_order === searchTypes)
                 ) return item;
            });
            return search_data;
        }
  },
  methods:{
        //save dataPacking
     savePacking:function() {
        var Datasend=JSON.stringify(this.listData);
         $.ajax({url: "$Url",type: 'post',
           data: {
             dataPacking: Datasend , 
            _csrf : "$csrf"
          },
          success: function (data) {
               toastr.success('กำลังบันทึกโปรดรอสักครู่...', 'บันทึกเรียบร้อย !',{
                     "progressBar": true, "closeButton": true
                });
                 setTimeout(function(){
                     location.href="$UrlPackinglist";
                  }, 2000);
              }
        });
      },
      //send Data list
     send:function () {
       var that = this;
 var SenddataList=JSON.stringify(this.addOrder);
         $.ajax({url: "$OrderList", type: 'post',
           data: {
             data: SenddataList , 
            _csrf : "$csrf"
          },
          success: function (data) {
           that.listData=data;
          }
        });
         this.hideLift=false;
          this.cancelBox=true;
      },
  CheckAdd:function(id){
          var show = this.show;
           for(var key in this.addOrder) {
                if (this.addOrder[key].id == id) show =true
            }
          return  show;
    }, 
    //เลือกรายการทั้งหมด
   selcetAll(item){
      for(var key in item) {
        this.addOrder.push(item[key]); 
      }
     this.checkAll =true;
      
    },
    selcetOrder:function(item){
     this.addOrder.push(item);
     this.checkOne=true;
    },
    CheckType: function (item) {
       if(item.type_order===1){
           return 'success text-center';
       }else if(item.type_order===2){
             return 'danger text-center';
       }else {
           return 'info text-center';
      }
     },
        removeAll(){
       console.log('remove All');  
       this.addOrder=[];
       if(this.addOrder.length <= 0){
               this.checkAll =false;
               this.checkOne =false;
          }
     },
     //ยกเลิกรายการ
        cancelAll(){
       console.log('Cancel All');  
       this.listData=[];
         if(this.listData.length <= 0){
               this.hideLift =true;
               this.cancelBox =false;
          }
     },
        check:function(data){
         if(data===0){
             return '';
         }else {
             return 'success';
         }
     },
        removeAdd(index){
          var items = this.addOrder.indexOf(index);
          this.addOrder.splice(items, 1);
          if(this.addOrder.length <= 0){
              console.log('ok');
               this.checkAll =false;
               this.checkOne =false;
          }
     },
        removeRow:function(index) {
          this.listData.splice(index, 1);
     },
        Numberformat:function(value) {
        let val = (value/1).toFixed(2).replace('.', '.')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    },
        sumTotalCal:function(L,W,H) {
         var totol = 0; 
        totol=parseFloat(L)/100*parseFloat(W)/100*parseFloat(H)/100;
        //  totol=0.0522;
        var sumCal = Math.ceil(totol*100)/100;
        if(isNaN(sumCal)){
           return '';
        }else {
           return sumCal;
        }
     },
         CheckAdd:function(id){
          var show = this.show;
           for(var key in this.addOrder) {
                if (this.addOrder[key].id == id) show =true
            }
          return  show;
    }, 
    //เลือกรายการทั้งหมด
     addRow:function(index,data) {
        console.log('ok' +index+1);
        this.listData.splice(index+1, 0, {
                id: data.id,
                invoice_id: data.invoice_id,
                type_code: data.type_code,
                product_code: data.product_code,
                updated_at: data.updated_at,
                status: data.status,
                style:data.style,
                customers_id:data.customers_id,
                product_id: data.style,
                bags: null,
                quantity: null,
                unit_price: data.unit_price,
                od_id: data.od_id,
                ctn: null,
                kg: null,
                width: null,
                length: null,
                height: null,
                checkAdd:1,
        });
     },

     CalCtn:function(key) {
            var ctn =this.ctn;
            if(this.ctn ===null){
                //return 0;
            }else {
                   return ctn+key;
            }
    },
    sendCtn:function(data,index){
             console.log(data.ctn)
       if(data.ctn ===null){
           return 0 ;
       }else {
           this.showCtn = data.ctn; 
       }
                
      
    },
    ClickAuto:function() {
       this.auto = false;
        this.ctn=null;
    },
     
    
  }
});
JS;
$this->registerJs($js);
?>