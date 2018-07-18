<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;


$workingID = null;
$workingNumber = null;
foreach ($models as$k=> $item) {
    if (!$workingNumber || $workingNumber != $item['ctn_no']) {
        $workingID = $k;
        $workingNumber = $item['ctn_no'];
        $models[$k]['rowspan'] = 1;
        $models[$k]['kg_group'] = floatval($item['kg']);
    } else {
        $models[$workingID]['kg_group']+=floatval($item['kg']);
        $models[$workingID]['rowspan']++;
        $models[$k]['rowspan'] = 0;
    }
    $item[$k]['invoice_id'] =  $item['invoice_id']===null?$item['tracking_number']:$item['invoice_id'];
}
?>
<?php $form = ActiveForm::begin([
//                    'action' => ['view'],
    'method' => 'post',
//                    'options' => ['data-pjax' => true]
]); ?>
<div class="grid simple">
    <div class="grid-body no-border">
            <div class="col-md-8">
                <h4>รายการ Packing ที่เลือก <?= count($models) ?> รายการ</h4>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <?= $form->field($model, 'transport_number')->label(false)->hiddenInput()->widget(\yii\widgets\MaskedInput::classname(), [
                        'mask' => 'JJNP-999-99',
                        'clientOptions'=>[
                            'removeMaskOnSubmit'=>true //กรณีไม่ต้องการให้มันบันทึก format ลงไปด้วยเช่น 9-9999-99999-999 ก็จะเป็น 9999999999999
                        ],
                        'options'=>[

                            'class'=>'form-control input-lg',
                            'placeholder' => 'เลขรอบขนส่ง...',
                        ]
                    ]) ?>
                    <span class="input-group-btn" style="padding-top: 10px">
                              <?= Html::submitButton( '<i class="fa fa-check"></i> บันทึก', ['class' => 'btn btn-primary btn-large']) ?>
                 </span>
                </div>

            </div>

        <br>
        <?php Pjax::begin(); ?>
        <table class="table table-bordered table-condensed" width="100%">
            <thead>
            <tr>
                <td width="1%" rowspan="2" class="text-center" id="co1">No</td>
                <td width="2%" class="header-table">交货日期</td>
                <td width="7%" class="header-table">单号</td>
                <td width="7%" class="header-table">档口</td>
                <td width="7%" class="header-table">款号</td>
                <td width="3%" class="header-table">数量</td>
                <td width="3%" class="header-table">单价</td>
                <td width="3%" class="header-table">金额</td>
                <td width="4%" class="header-table">重量</td>
                <td width="5%" class="header-table">พื้นที่</td>
            </tr>
            <tr>
                <th class=" ">วันรับของ</th>
                <th class=" ">ออร์เดอร์</th>
                <th class=" ">ร้านค้า</th>
                <th class=" ">รหัสสินค้า</th>
                <th class=" ">จำนวน</th>
                <th class=" ">ราคา</th>
                <th class=" ">รวมเงิน</th>
                <th class=" ">น้ำหนัก <br>(KG.)</th>
                <th class=" ">ลูกบาศก์<br>เมตร</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($models as $k => $item): ?>
                <tr>
                <?php if ($item['rowspan'] > 0): ?>
                    <td  rowspan="<?=$item['rowspan']?>" class="<?=@$item['rowspan'] >1 ?'warning text-center ':' text-center'?>" ><?=  $item['ctn_no'] ?></td>
                    <?php endif;?>
                    <td class=""><?= Yii::$app->formatter->asDate($item['created_at'], 'dd/MM/yyyy') ?></td>
                    <td class=""><?=@!empty($item[$k]['invoice_id'])?$item[$k]['invoice_id'] :'-'   ?></td>
                    <td class=""><?=@!empty($item['supplier'] )?$item['supplier'] :'-'?></td>
                    <td class=""><?= @!empty($item['type_code'] )?$item['type_code'] :'-' ?></td>
                    <td class=""><?= $item['quantity'] ?></td>
                    <td class=""><?= $item['unit_price'] ?></td>
                    <td><?= number_format($item['quantity'] *$item['unit_price'],0) ?></td>
                    <?php if ($item['rowspan'] > 0): ?>
                    <td  rowspan="<?=$item['rowspan']?>"  class="<?=@$item['rowspan'] > 1 ?'warning text-center ':' text-center'?>" ><?=number_format($item['kg_group'] ,2) ?></td>
                    <?php endif;?>
                    <td class="text-right" ><?= $item['m'] ?></td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

        <?php Pjax::end(); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

