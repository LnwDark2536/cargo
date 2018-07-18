<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
$this->title='รายการ Packing';


$workingID = null;
$workingNumber = null;
foreach ($data_list as$k=> $item) {
    if (!$workingNumber || $workingNumber != $item['ctn_no']) {
        $workingID = $k;
        $workingNumber = $item['ctn_no'];
        $data_list[$k]['rowspan'] = 1;
        $data_list[$k]['kg_group'] = floatval($item['kg']);
    } else {
        $data_list[$workingID]['kg_group']+=floatval($item['kg']);
        $data_list[$workingID]['rowspan']++;
        $data_list[$k]['rowspan'] = 0;
    }
    $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
//    var_dump($data_list);exit();
}



?>
<style>
    .btn-none {
        display: none;
    }
</style>
    <div class="grid simple">
        <div class="grid-title no-border">
            <h2>รายการ Packing List </h2>
        </div>
        <div class="grid-body no-border">
            <div class="text-right">
                <?php echo Html::button('ส่งรายการที่เลือก', ['class' => 'btn btn-danger  btn-del', 'id' => 'id_send'])?>
            </div>
            <div class="row-fluid table-responsive">

                <table class="table  table-bordered" id="packing" width="100%" >
                    <thead>
                    <tr class="">
                        <th width="2%" >
                            <label class="checkbox-inline"><input name="CheckAll" type="checkbox" id="CheckAll" class="">All</label>
                        </th>
                        <th>ctn</th>
                        <th class="">ชื่อลูกค้า</th>
                        <th class="">ออร์เดอร์</th>
                        <th class="">ร้านค้า</th>
                        <th class="">รหัสสินค้า</th>
                        <th class="">bags</th>
                        <th class="">น้ำหนัก <br>(KG.)</th>
                        <th class="">ลูกบาศก์<br>เมตร</th>
                        <th width="1%">จัดการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data_list as $num => $model): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="select[]" id="check_id-<?php echo $num + 1; ?>"
                                       value="<?= $model['id'] ?>">
                            </td>
                        <?php if ($model['rowspan'] > 0): ?>
                            <td rowspan="<?=$model['rowspan']?>" style="text-align: center" class="<?=$model['rowspan'] >1 ?'warning ':''?>"><?=$model['ctn_no']?></td>
                        <?php endif; ?>
                            <td >
                                <?=@$model['customer_code']  ?>
                            </td>
                            <td> <?= $model['invoice_id'] ?></td>
                            <td><?= $model['supplier'] ?></td>
                            <td><?= $model['style'] ?></td>
                            <?php if ($model['rowspan'] > 0): ?>
                            <td  rowspan="<?=$model['rowspan']?>" ><?=  number_format($model['bags'], 2) ?></td>
                            <?php endif; ?>
                            <?php if ($model['rowspan'] > 0): ?>
                            <td rowspan="<?=$model['rowspan']?>" style="text-align: center" class="<?=$model['rowspan'] >1 ?'warning ':''?>"><?= number_format($model['kg_group'], 2) ?></td>
                            <?php endif; ?>
                            <td><?= $model['m'] ?></td>
                            <td>
                                <span>
                           <a href="<?=Url::to(['packing/packing-update','id'=>$model['id']])?>" > <i class="fa fa-pencil"></i> </a>
                                </span>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div id="panking_list">

</div>

<?php
$sen = count($data_list);
$url =Url::to(['send-packing']);
$js = <<<JS
const  dataClount=$sen;
$('#CheckAll').on('click', function(event){
   
    for(var i=1;i<=dataClount;i++){
        if(this.checked){
            $('#check_id-'+i).prop('checked', true);
        }else {
             $('#check_id-'+i).prop('checked', false);
        }
    }
});
$('#id_send').on('click',function() {
    var packing=[];
  for(var i=1;i<=dataClount;i++){
      if($('#check_id-'+i).prop('checked') === true){
          packing.push($('#check_id-'+i).val());
      }
    }
    if(packing.length > 0){
      console.log('ok'+packing);
         jQuery.post('$url',{id:packing},function(){
      });
    }else {
        alert("กรุณาเลือกรายการ...")
           console.log('no'); 
    }
     // console.log(packing)
});

$('#packing').on( "click",function() {
		$("#quick-access").css("bottom","0px");
    });

JS;
$this->registerJs($js);

?>
<?php
$data_listJson =\yii\helpers\Json::encode($data_list);
$Vuejs=<<<JS
new Vue({
 el: '#panking_list',
 data:{
     selectedAll: [],
     selected: [],
     search:'',
     dataPacking:$data_listJson,
 },
 template:`
 <div class="grid simple">
    <div class="grid-title no-border">
        <h2>รายการ Packing List </h2>
    </div>
    <div class="grid-body no-border">
                {{selected}}
    <table class="table table-condensed table-hover">
    <thead>
    <tr class="">
         <th width="2%" >
            <label class="checkbox-inline"><input name="CheckAll" type="checkbox" @click="selectAll">All</label>
         </th>
         <th>ctn</th>
         <th class="">ชื่อลูกค้า</th>
         <th class="">ออร์เดอร์</th>
         <th class="">ร้านค้า</th>
         <th class="">รหัสสินค้า</th>
         <th class="">bags</th>
         <th class="">น้ำหนัก <br>(KG.)</th>
         <th class="">ลูกบาศก์<br>เมตร</th>
         <th width="1%">จัดการ</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="model in filterPacking"> 
      <td><input type="checkbox" :value="model.id" id="checkbox" v-model="selected"></td>
        <td v-if="model.rowspan > 0 " :rowspan="model.rowspan" :class="model.rowspan > 1 ? 'warning':' ' ">{{model.ctn_no}}</td>
        <td>{{model.customer_code}}</td>
         <td>{{model.invoice_id}}</td>
         <td>{{model.supplier}}</td>
    </tr>
    </tbody>
</table>
  </div>
 </div>
        `,
  computed: {
    filterPacking(){  
       var search_data = this.dataPacking;
         var self=this;
            return this.dataPacking.filter(function(item){
                return item.customer_code.toLowerCase().indexOf(self.search.toLowerCase())>=0;
            });
        }
  },
    methods: {
    selectAll: function(e) {
         var self = this;
        if (e.target.checked) {
          this.dataPacking.forEach(function(item) {
          self.selected.push(item.id);
           });
      }
      else {
        this.selected = [];
      }
      
        }
 }
    
});
JS;
$this->registerJs($Vuejs);
?>


