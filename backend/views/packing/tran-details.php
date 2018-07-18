<?php
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Html;

$set1 = substr($number, 0, 3);
$set2 = substr($number, 3, 3);
$sum = 'JJNP' . $set1 . '-' . $set2;
$sumKG=0;
$sumbags=0;
$sumM =0;
?>
<style>

</style>
<div class="grid simple">
    <div class="grid-title no-border">
        <h2>View <strong><?php echo $sum ?></strong></h2>
        <div class="tools">

        </div>
    </div>
    <div class="grid-body no-border">
        <div class="text-right">
            <?php echo Html::a('<i class="fa fa-backward"></i> กลับหน้าหลัก', ['packing/transportation'], ['class' => 'btn btn-info btn-cons']) ?>
            <?php echo Html::a('<i class="fa fa-file-pdf-o"></i> ออกรายงาน Packing ลูกค้า', ['packing/packing-customers'], ['class' => 'btn btn-success  btn-cons']) ?>
            <?php echo Html::a('<i class="fa fa-file-excel-o"></i>  Excel', ['packing/excel-packing-list','id'=>$number], ['class' => 'btn btn-warning btn-cons']) ?>
        </div>
        <div class="table-responsive">
            <table class="table  table-bordered" width="100%">
                <thead>
                <tr>
                    <th width="5%">ctn_no</th>
                    <th width="10%">ออร์เดอร์</th>
                    <th width="10%">customer_code</th>
                    <th width="15%">description</th>
                    <th width="5%">bags</th>
                    <th width="5%">quantity</th>
                    <th width="5%">unit/p</th>
                    <th width="5%" class="text-center">kg</th>
                    <th width="10%" class="text-right">ลูกบาศก์(เมตร)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataDetails as $k => $model): ?>
                    <?php $sumbags += $model['bags']?>
                    <?php $sumKG += $model['kg']?>
                    <?php $sumM += $model['m']?>
                    <tr>
                        <?php if ($model['rowspan'] > 0): ?>
                            <td rowspan="<?=$model['rowspan']?>" class="<?=$model['rowspan'] >1 ?'warning text-center':'text-center'?>" ><?= $model['ctn_no'] ?></td>
                        <?php endif; ?>
                        <td><?= $model['invoice_id'] ?></td>
                            <td ><?= $model['customer_code'] ?></td>
                        <td><?= $model['description'] ?></td>
                        <td><?= $model['bags'] ?></td>
                        <td><?= $model['quantity'] ?></td>
                        <td><?= $model['unit_price'] ?></td>
                        <?php if ($model['rowspan'] > 0): ?>
                            <td rowspan="<?=$model['rowspan']?>" class="<?=$model['rowspan'] >1 ?'warning text-center':'text-center'?>"><?= @number_format($model['kg_group'],2) ?></td>
                        <?php endif; ?>
                        <td class="text-right"><?= $model['m'] ?></td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="warning">
                    <td colspan="2" class="text-center "><strong >Total</strong></td>
                    <td colspan="4"></td>
                    <td  class="text-right"><strong ><?php echo $sumbags?></strong></td>
                    <td class="semi-bold text-right"><strong ><?php echo $sumKG?></strong></td>
                    <td class="semi-bold text-right"><strong ><?php echo $sumM?></strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>





