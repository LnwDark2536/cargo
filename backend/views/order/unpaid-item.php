<?php
$this->title ="สินค้าที่ยังไม่ชำระ";
use yii\helpers\Url;
?>
<div class="col-md-12">
    <div class="grid simple vertical green">
        <div class="grid-title no-border">
            <h3><i class="glyphicon glyphicon-bitcoin"></i> รายการ Order ที่ยังไม่ชำระ </h3>
        </div>
        <div class="grid-body no-border">
            <table class="table table-hover" width="100%" id="table">
                <thead>
                <tr class="warning">
                    <th width="5%" >#</th>
                    <th width="15%" >no order/ bill/ tacking</th>
                    <th width="10%">customer code</th>
                    <th width="10%" >supplier  </th>
                    <th width="7%" >ประเภท</th>
                    <th width="7%">จำนวนรายการ</th>
                    <th  width="10%" class="text-right">จัดการข้อมูล</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataModel as$k=> $model):?>
                <tr >
                    <td><?=$k+1?></td>
                    <td><?=$model['invoice_id']?></td>
                    <td><?=$model['customer_code']?></td>
                    <td><?=!empty($model['supplier'])?$model['supplier'] : '-'?></td>
                    <td><?=\common\models\Orders::getTypeNameLabel($model['type_order'])?></td>
                    <td><?=$model['count_order']?></td>
                    <th class="text-right">
                        <?php if($model['type_order']==2 ||$model['type_order']==3):?>
                            <a href="<?=Url::to(['order/pay-postage','id'=>$model['id']])?>" class="btn btn-small btn-primary"> <span class="fa fa-check-circle"></span> ชำระออเดอร์</a>
                        <?php else:?>
                        <a href="<?=Url::to(['order/pay-order','id'=>$model['id']])?>" class="btn btn-small btn-primary"> <span class="fa fa-check-circle"></span> ชำระออเดอร์</a>
                        <?php endif;?>
                    </th>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
\backend\assets\DatatableAsset::register($this);

?>
