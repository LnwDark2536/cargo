<?php


$set1 = substr($number, 0, 3);
$set2 = substr($number, 3, 3);
$sum = 'JJNP' . $set1 . '-' . $set2;
$sumKG=0;
$sumbags=0;
$sumM =0;
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body >
<div><h2>PACKING LIST NO. <?=$sum?></h2></div>
            <table border="1"  style="font-size: 16px;" width="100%">
                <thead>
                <tr>
                    <th >ctn_no</th>
                    <th >ออร์เดอร์</th>
                    <th >customer_code</th>
                    <th >description</th>
                    <th >bags</th>
                    <th >quantity</th>
                    <th >unit/p</th>
                    <th  class="text-center">kg</th>
                    <th class="text-right">ลูกบาศก์(เมตร)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataDetails as $k => $model): ?>
                    <?php $sumbags += $model['bags']?>
                    <?php $sumKG += $model['kg']?>
                    <?php $sumM += $model['m']?>
                    <tr>
                        <?php if ($model['rowspan'] > 0): ?>
                            <td rowspan="<?=$model['rowspan']?>" style="text-align: center" class="<?=$model['rowspan'] >1 ?'warning text-center':'text-center'?>" ><?= $model['ctn_no'] ?></td>
                        <?php endif; ?>
                        <td style="text-align: center"><?= $model['invoice_id'] ?></td>
                        <td ><?= $model['customer_code'] ?></td>
                        <td><?= $model['description'] ?></td>
                        <td  style="text-align: center"><?= $model['bags'] ?></td>
                        <td  style="text-align: center"><?= $model['quantity'] ?></td>
                        <td  style="text-align: center"><?= $model['unit_price'] ?></td>
                        <?php if ($model['rowspan'] > 0): ?>
                            <td  style="text-align: center" rowspan="<?=$model['rowspan']?>" class="<?=$model['rowspan'] >1 ?'warning text-center':'text-center'?>"><?= @number_format($model['kg_group'],2) ?></td>
                        <?php endif; ?>
                        <td class="text-right" style="text-align: right"><?= $model['m'] ?></td>
                    </tr>

                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="warning">
                    <td colspan="2" class="text-center "  style="text-align: center"><strong >Total</strong></td>
                    <td colspan="2"></td>
                    <td   style="text-align: right" class="text-right"><strong ><?php echo $sumbags?></strong></td>
                    <td colspan="2"></td>
                    <td style="text-align: right"  class="semi-bold text-right"><strong ><?php echo $sumKG?></strong></td>
                    <td  style="text-align: right"  class="semi-bold text-right"><strong ><?php echo $sumM?></strong></td>
                </tr>
                </tfoot>
            </table>

</body>
</html>
