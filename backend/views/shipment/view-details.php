<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\Json;
use yii\helpers\Url;
$order=  Yii::$app->db->createCommand('SELECT o.invoice_id,o.id,o.bank,o.date_order,cs.customer_code,
	o.account_name,o.account_number,o.deposit,o.status,o.deposit,
	o.payment,s.name as supplier,o.created_at,
	o.phone,CASE o.payment 
		WHEN 0 THEN \'CASH\' 
		WHEN 1  THEN \'CREDIT\'
    ELSE Null
	END AS payment 
  FROM orders o
	LEFT JOIN customers cs ON o.customers_id = cs.ID 
	LEFT join supplier s on o.supplier_id = s.id
WHERE o.ID  = :id')->bindValues(['id'=>$order_id])->queryOne();
?>
<?php if($order['status']===1):?>
<?php
$this->title="รายละเอียดการรับสินค้า";
$models = new \common\models\ReceiveDetails();
$totalAll = 0;
$weightAll=0;
$receiveAll=0;
?>
<style>
    [v-cloak] {
        display:none;
    }
</style>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple ">
            <div class="grid-title">
                <h3 class="text-left"><i class="fa fa-list-ol text-success"></i> รายละเอียดสินค้า</h3>
                <div class="text-right" style="margin-top: -40px;">
                    <?= Html::a('<i class="fa fa-home"></i> กลับหน้าหลัก', ['received'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('<i class="fa fa-print"></i>  พืมพ์', ['delivery-print', 'id' => $order_id], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="grid-body ">
                <div class="text-right">
                    <?php if($order['status']===6):?>
                        <p class="label label-success" style="font-size: 20px;">จ่ายแล้ว</p>
                        <?php
                    else:
                    ?>
                        <p class="label label-danger" style="font-size: 20px;">ยังไม่ชำระ</p>
                    <?php endif;?>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">Order</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table ">
                                    <tbody>
                                    <tr>
                                        <th>Customer(客户)</th>
                                        <td>
                                            <?=@$order['customer_code']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Supplier(档口)</th>
                                        <td>
                                            <?=@$order['supplier']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>PAYMENT (付款方式)</th>
                                        <td>
                                            <?=@$order['payment']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone (手机)</th>
                                        <td>
                                            <?=@$order['phone']?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table ">
                                    <tbody>
                                    <tr>
                                        <th>No order (订单单号)</th>
                                        <td>
                                            <?=@$order['invoice_id']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Deposit (定金)</th>
                                        <td>
                                            <?=@$order['deposit']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>created date</th>
                                        <td>
                                            <?=@$order['date_order']?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table ">
                                    <tbody>
                                    <tr>
                                        <th>BANK (银行)</th>
                                        <td>
                                            <?=@$order['bank']?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ACCOUNT NAME (开户人)</th>
                                        <td>
                                            <?=@$order['account_name']?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>ACCOUNT NUMBER (帐户/卡号)</th>
                                        <td>
                                            <?=@$order['account_number']?>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>

                    <table class="table  table-bordered table-responsive" width="100%">
                        <thead>
                        <tr >
                            <th width="3%">#</th>
                            <th width="20%">Product Type (类别)</th>
                            <th width="10%">Style (款号)</th>
                            <th width="5%" class="text-right">Unit</th>
                            <th width="10%" class="text-right">UnitPrice (单价)</th>
                            <th width="5%" class="text-right">RECEIVE</th>
                            <th width="5%" class="text-right">weight</th>
                            <th width="10%" class="text-right">Total Price</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($Rawdata as$k=> $model):?>
                            <?php $totalAll+=$model['receive']*$model['unit_price'];
                            $receiveAll+=@$model['receive'];
                            $weightAll+=!empty($model['weight'])?$model['weight']:0;
                            ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td><?=$model['product_code']?></td>
                                <td><?=$model['style']?></td>
                                <td class="text-right" ><?=@$model['quantity']?></td>
                                <td class="text-right"><?=@$model['unit_price']?></td>
                                <td class="text-right"><?=@$model['receive']?></td>
                                <td class="text-right"><?=@$model['weight']?></td>
                                <td class="text-right"><?=number_format($model['receive']*$model['unit_price'],2)?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><h4>รวมทั้งหมด</h4></td>
                            <td class="text-right"><h4><?=$receiveAll?></h4></td>
                            <td class="text-right" ><h4><?=$weightAll?></h4></td>
                            <td class="text-right"><h4><?=number_format($totalAll-intval($order['deposit']),2)?></h4></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>
</div>
<?php elseif($order['status']===2):?>
    <?php
    $model=\common\models\Orders::findOne($order_id);
    $data =Yii::$app->db->createCommand("SELECT
    os.style,
    CONCAT(pt.type_code, ' - ', pt.description) AS product_code,
    os.quantity
    FROM
    order_details os
    LEFT JOIN product_type pt ON os.product_id = pt.ID
    WHERE os.order_id =:id ")->bindValues(['id'=>$order_id])->queryAll();
    ?>
    <div class="col-md-12">
        <div class="text-right">
            <?= Html::a('<i class="fa fa-home"></i> back index', ['with-out-order'], ['class' => 'btn btn-info btn-small']) ?>
            <?= Html::a('<i class="fa fa-pencil"></i> Update', ['shipment/update-with-out', 'id' => $model->id], ['class' => 'btn btn-primary btn-small']) ?>
            <?= Html::a('<i class="fa fa-trash"></i> Delete', ['shipment/delete-with-out', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-small',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="grid simple ">
            <div class="grid-title no-border">
                <h3> <i class="fa fa-file"></i> รายละเอียด รับสินค้าไม่มีใบสั่งของ</h3>
            </div>
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Bill Number : <?=@$model->invoice_id?></h4>
                        <h4>Customer(客户)  : <?=@$model->fullName?></h4>
                        <h4>bags (订单单号) : <?=@$model->order_bags?></h4>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-right">No : <?=@sprintf("%07d", $model->bill_number);?></h4>
                        <h4 class="text-right">วันที่รับ : <?=@Yii::$app->formatter->asDate($model->created_at, 'dd/MM/yyyy');?></h4>
                    </div>
                </div>
                <table class="table table-hover no-more-tables">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Code product</th>
                        <th width="25%">Product type(类别)</th>
                        <th width="15%">Quantity (数量)</th>
                        <th width="5%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as$k=>$od):?>
                        <tr>
                            <td><?=$k+1?></td>
                            <td ><?=@$od['style']?></td>
                            <td ><?=@$od['product_code']?></td>
                            <td ><?=@$od['quantity']?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <h5 class="text-right">รับโดย <code><?=@$model->userName?></code></h5>
            </div>
        </div>
    </div>

<?php endif;?>
