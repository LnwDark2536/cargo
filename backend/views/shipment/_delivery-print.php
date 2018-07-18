<?php
\backend\assets\PrintAsset::register($this);
use common\models\Orders;

$this->title = "พิมพ์ใบรับของ";
$order = Orders::findOne(['id' => $order_id]);
$amountTotal = 0;
$sumTotal = 0;
?>
<?php if (isset($Rawdata)): ?>

    <page size="A5" layout="portrait">
        <div class="">
            <a class="btn btn-success btn-back" href="<?= \yii\helpers\Url::to(['received']) ?>"><i
                        class="fa fa-home"></i> กลับหน้ารับสินค้า</a>
        </div>
        <div class="">
            <button class="btn btn-info btn-print" onClick="window.print()"><i class="fa fa-print"></i> Print</button>
        </div>
        <div class="conten">
            <img src="<?= Yii::getAlias('@web/images/header1.jpg') ?>" class="logo_header1"> <br>
            <p class="font-heder2">代 理 单</p>
            <div class="row">
                <div class="col-lg-6 col-md-6 header-content1">
                    <h5 class="">Customer<span class="tableJP">(客户)</span> : <?= $order->getFullName() ?></h5>
                </div>
                <div class="col-lg-6 col-md-6 text-right header-content2">
                    <h5>No.<?= $order->invoice_id ?></h5>
                </div>
            </div>
            <div class="row" style="margin-top: -10px">
                <div class="col-lg-6 col-md-6" style="">
                    <h5>Supplier<span class="tableJP">(档口)</span> : <?= $order->getSupplierName() ?></h5>
                </div>
                <div class="col-lg-6 col-md-6 text-right header-content4"  >
                    <h5>Date<span class="tableJP">(日期) </span>:<?php
                        $receiveDate = date("d/m/Y", strtotime($order->updated_at));
                        echo $receiveDate;
                        date('d/m/Y') ?></h5>
                </div>
            </div>
            <table class="table  table-bordered  table-responsive" width="100%">
                <thead>
                <tr>
                    <th width="2%" rowspan="2" class="text-center" style="padding-bottom: 15px ">No</th>
                    <th class="tableJP text-center">交货日期</th>
                    <th class="tableJP " width="30%" >款号</th>
                    <th  class="tableJP text-left"> 款号</th>
                    <th class="tableJP text-right">数量</th>
                    <th class="tableJP text-right">单价</th>
                    <th class="tableJP text-right">金额</th>
                </tr>
                <tr>
                    <th width="5%" class="text-center">วันรับของ</th>
                    <th width="30%" class="">ชื่อสินค้า</th>
                    <th width="8%" class="text-left">Style</th>
                    <th width="5%" class="text-right">จำนวนรับ</th>
                    <th width="5%" class="text-right">ราคา</th>
                    <th width="5%" class="text-right">รวมเงิน</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($Rawdata as $k => $model): ?>
                    <?php $amountTotal += $model['amount_receive'];
                    $sumTotal += $model['amount_receive'] * $model['unit_price'];
                    ?>
                    <tr>
                        <td class="text-center"><?= $k + 1 ?></td>
                        <td><?= Yii::$app->formatter->asDate($model['updated_at'], 'dd/M/Y') ?></td>
                        <td style=""><?= $model['product_code'] ?></td>
                        <td style=""><?= $model['style'] ?></td>
                        <td class="text-right"><?= $model['amount_receive'] ?></td>
                        <td class="text-right"><?= $model['unit_price'] ?></td>
                        <td class="text-right"><?= number_format($model['unit_price'] * $model['amount_receive'], 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                        <tr>
                            <td colspan="5" class="text-right" ><strong>Cost <span class="tableJP">(共计)</span></strong></td>
                            <td colspan="2" class="text-right" ><strong><?= number_format($sumTotal, 2) ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right" ><strong>Deposit<span class="tableJP"> (预付定金)</span></strong></td>
                            <td colspan="2" class="text-right" ><strong><?= number_format($order->deposit, 2) ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right" ><strong>Total <span class="tableJP"> (余额)</span></strong></td>
                            <td colspan="2" class="text-right" ><strong></strong><?= number_format($sumTotal - $order->deposit, 2) ?></strong></td>
                        </tr>
                </tfoot>
            </table>
            <div class="row" style="margin-top: -10px">
                <div class="col-7">
                    <table>
                        <tr>
                            <th>   1.<span class="tableJP2">开户银行</span>:
                            </th>
                            <td>
                                <?=@$order->bank?>
                            </td>
                        </tr>
                        <tr>
                            <th>   2.<span class="tableJP2"> 开户人 </span>:
                            </th>
                            <td>
                                <?=@$order->account_name?>
                            </td>
                        </tr>
                        <tr>
                            <th>  3.<span class="tableJP2"> 帐户/卡号</span>:
                            </th>
                            <td>
                                <?=@$order->account_number?>
                            </td>
                        </tr>
                        <tr>
                            <th> 4.<span class="tableJP2"> 供應商電話</span>:

                            </th>
                            <td>
                                <?=@$order->supplier->phone?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-5 text-right">
                    <div class="" style="color: red;">
                        <span class="font-jp">电话 :36479092,(泰国电话) +6622542699</span>
                        <br>
                        *<span class="font-jp">本公司只代客户收货，概不负责货款</span>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row footerDW" >
                <div class="col-8 foodterL" >供应商代表签名 ......................</div>
                <div class="col-4 text-right foodterR">经手人 : <span class="user-receive"><?php echo @\common\models\User::findOne([$order->updated_by])->username?></span></div>
            </div>
    </page>

<?php endif; ?>