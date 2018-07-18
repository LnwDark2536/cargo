<?php
$reciver='';
$sender='';
?>
<div class="fontFam fontSizeHead">ลูกค้า <?=!empty($customer)?$customer->customer_code:'..................................'?></div>
<div id="topic1" class="fontFam fontSizeHead">วันที่ ....../....../...........</div>
<div id="topic2" class="fontFam fontSizeHead">รอบขนส่ง <?=!empty($jj)?$jj:'..................................'?></div>
<br>
<table id="table" border="1">
    <thead>
    <tr>
        <td rowspan="2" class="firstCol fontFam fontSize" id="co1">No</td>
        <td id="co2" class="fontFam fontSize">交货日期</td>
        <td id="co3" class="fontFam fontSize">单号</td>
        <td id="co4" class="fontFam fontSize">档口</td>
        <td id="co5" class="fontFam fontSize">款号</td>
        <td id="co6" class="fontFam fontSize">数量</td>
        <td id="co7" class="fontFam fontSize">单价</td>
        <td id="co8" class="fontFam fontSize">金额</td>
        <td id="co9" class="fontFam fontSize">重量</td>
        <td id="co10" class="fontFam fontSize">พื้นที่</td>
    </tr>
    <tr>
        <td class="fontFam fontSize">วันรับของ</td>
        <td class="fontFam fontSize">ออร์เดอร์</td>
        <td class="fontFam fontSize">ร้านค้า</td>
        <td class="fontFam fontSize">รหัสสินค้า</td>
        <td class="fontFam fontSize">จำนวน</td>
        <td class="fontFam fontSize">ราคา</td>
        <td class="fontFam fontSize">รวมเงิน</td>
        <td class="fontFam ">น้ำหนัก <br>(KG.)</td>
        <td class="fontFam fontSize">ลูกบาศก์<br>เมตร</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $SumKg=0;
    $SumM_3=0;
    $SumQuantity =0;
    $SumPrice = 0;

    foreach ($dataProvider as$k=> $model):?>
        <?php

        $SumQuantity +=$model['quantity'];
        $SumPrice +=$model['quantity']*$model['unit_price'];
        $SumKg +=$model['kg'];
        $SumM_3 +=$model['m'];
        ?>
    <tr>
        <td><?=$k+1?></td>
        <?php if ($model['rowspan'] > 0): ?>
        <td rowspan="<?=$model['rowspan']?>"><?=@Yii::$app->formatter->asDate($model['created_at'] ,'dd/MM/yyyy');?></td>
            <?php endif; ?>
        <td style="text-align: center"><?=$model['invoice_id']?></td>
        <td style="text-align: center"><?=@!empty($model['supplier'])?$model['supplier']:'-'?></td>
        <td style="text-align: center"><?=@!empty($model['type_code'])?$model['type_code']:'-'?></td>
        <td style="text-align: center"><?=number_format($model['quantity'],2)?></td>
        <td style="text-align: center"><?=$model['unit_price'];?></td>
        <td style="text-align: center"><?=@number_format($model['quantity']*$model['unit_price'],2);?></td>
        <?php if ($model['rowspan'] > 0): ?>
        <td rowspan="<?=$model['rowspan']?>" style="text-align: center"><?=number_format($model['kg_group'],2);?></td>
        <?php endif; ?>
        <td style="text-align: center"><?=@!empty($model['m'])?$model['m']:' ';?></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td></td>
        <td class="fontFam fontSize">TOTAL</td>
        <td colspan="3"></td>
        <td class="fontFam fontSize" ><?=@$SumQuantity?> </td>
        <td class="fontFam fontSize"></td>
        <td class="fontFam fontSize"><?=@number_format($SumPrice,0) ?></td>
        <td class="fontFam fontSize" ><?=@number_format($SumKg,2)?></td>
        <td class="fontFam fontSize" ><?=@number_format($SumM_3,2)?></td>
    </tr>
    </tbody>

</table>

<br><br>
<p class="fontFam textCen" style="font-size: 12px">***กรุณาตรวจสอบรายการสินค้าและจำนวนเงินโดยละเอียด หากมีข้อผิดพลาดหรือสงสัย กรุณาติดต่อบริษัทภายใน 3 วัน***</p>
<br><br>
<div class="width50per floatL textCen fontFam" style="font-size: 12pt">
    ผู้รับ <?=($reciver === "" ? "____________" : $reciver)?>
</div>

<div class="width50per floatL textCen fontFam " style="font-size: 12pt">
    ผู้ส่ง <?=($sender  === "" ? "____________" : $sender)?>
</div>
