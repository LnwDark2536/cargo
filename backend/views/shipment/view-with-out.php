<?php
use yii\helpers\Html;
$this->title=$model->id;
$data =Yii::$app->db->createCommand("SELECT
	os.style,
CONCAT(pt.type_code, ' - ', pt.description) AS product_code,
	os.quantity
FROM
	order_details os
	LEFT JOIN product_type pt ON os.product_id = pt.ID 
WHERE os.order_id =:id ")->bindValues(['id'=>$model->id])->queryAll();
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
