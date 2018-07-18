<?php
\backend\assets\PrintAsset::register($this);
$this->title = "พิมพ์ใบสั่งของไม่มีบิล";
$amountTotal = 0;
$sumTotal = 0;
$Rawdata =Yii::$app->db->createCommand("SELECT
	os.style,
CONCAT(pt.type_code, ' - ', pt.description) AS product_code,
	os.quantity
FROM
	order_details os
	LEFT JOIN product_type pt ON os.product_id = pt.ID 
WHERE os.order_id =:id ")->bindValues(['id'=>$model->id])->queryAll();
?>
<?php if (isset($Rawdata)): ?>
    <page size="A5" layout="portrait">

        <div class="">
            <a class="btn btn-success btn-back" href="<?= \yii\helpers\Url::to(['shipment/with-out-order']) ?>"><i
                        class="fa fa-home"></i> กลับหน้ารับสินค้า</a>
        </div>
        <div class="">
            <button class="btn btn-info btn-print" onClick="window.print()"><i class="fa fa-print"></i> Print</button>
        </div>
        <div class="conten">
            <img src="<?= Yii::getAlias('@web/images/header1.jpg') ?>" class="logo_header1"> <br>
            <p class="font-heder2">收 据 单 </p>
            <div class="row">
                <div class="col-lg-6 col-md-6 header-content1">
                    <h5 class="">Bill Number : <?= @$model->invoice_id ?></h5>
                </div>
                <div class="col-lg-6 col-md-6 text-right header-content2">
                    <h5>No.<?= @sprintf("%07d", $model->bill_number); ?></h5>
                </div>
            </div>
            <div class="row" style="margin-top: -10px">
                <div class="col-lg-6 col-md-6" style="">
                    <h5 class="">Customer<span class="tableJP">(客户)</span> : <?= @$model->getFullName() ?></h5>
                    <h1><strong>Bags</strong> : <?= @$model->order_bags?></h1>
                </div>
                <div class="col-lg-6 col-md-6 text-right header-content4"  >
                    <h5>Date<span class="tableJP">(日期) </span>:<?php
                        $receiveDate = date("d/m/Y", strtotime($model->updated_at));
                        echo $receiveDate;
                        date('d/m/Y') ?></h5>
                </div>
            </div>
            <table class="table  table-bordered  " width="100%">
                <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th width="15%">Code product</th>
                    <th width="30%">Product type(类别)</th>
                    <th width="10%" class="text-right">Quantity (数量)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($Rawdata as$k=>$od):?>
                    <tr>
                        <td class="text-center"> <?=$k+1?></td>
                        <td ><?=@$od['style']?></td>
                        <td ><?=@$od['product_code']?></td>
                        <td  class="text-right"><?=@$od['quantity']?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>

            </table>

            </div>
            <div class="row footerDW" >
                <div class="col-8 foodterL" >供应商代表签名 ......................</div>
                <div class="col-4 text-right foodterR">经手人 : <span class="user-receive"><?php echo @\common\models\User::findOne([@$model->updated_by])->username?></span></div>
            </div>
    </page>

<?php endif; ?>