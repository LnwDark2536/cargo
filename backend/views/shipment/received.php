<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title = 'รายการของที่รับแล้ว';
?>
<?php Pjax::begin(); ?>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="grid simple vertical green">
            <div class="grid-title no-border">
                <h3 class="text-left header-x"><i class="fa fa-file text-success"></i> Received ( รายการของที่รับแล้ว )
                </h3>
            </div>
            <div class="grid-body ">
                <div class="row-fluid table-responsive">
                    <table class="table table-hover table-bordered" id="reciveorder" width="100%">
                        <thead>
                        <tr class="info">
                            <th width="3%">#</th>
                            <th width="9%">No Order</th>
                            <th width="5%">tracking_number</th>
                            <th width="10%">Customer</th>
                            <th width="10%">Supplier</th>
                            <th width="10%" class="text-right">Bags</th>
                            <th width="10%" class="text-right">จำนวนรับ</th>
                            <th width="9%" class="text-right">น้ำหนัก</th>
                            <th width="5%" class="text-right">total price</th>
                            <th width="4%" class="text-right">type</th>
                            <th width="9%" class="text-center">จัดการข้อมูล</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($dataReceive as $num => $order): ?>
                            <tr class="<?php

if($order['status']==4){
    echo  "success";
}
                            ?>">
                                <td class="v-align-middle">
                                    <?= $num + 1 ?>
                                </td>
                                <td class="v-align-middle">
                                        <?= @!empty($order['invoice_id']) ? $order['invoice_id'] : '-' ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['tracking_number'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['code_fullname'] ?>
                                </td>
                                <td class="v-align-middle">
                                    <?= @$order['supplier'] ?>
                                </td>

                                <td class="v-align-middle text-right text-danger">
                                    <?php if($order['type_order']===2):?>
                                    <?= @number_format($order['order_bags'], 0) ?>
                                    <?php elseif ($order['type_order']===3):?>
                                    <?= @number_format($order['bags'], 0) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="v-align-middle text-right text-danger">
                                    <?= @number_format($order['receive'], 0) ?>
                                </td>
                                <td class="v-align-middle text-right">
                                    <?= @$order['weight'] ?>
                                </td>

                                <td class="v-align-middle text-right">
                                    <?= @number_format($order['total_price'] - $order['deposit'], 2) ?>
                                </td>
                                <td class="text-center">
                                    <?= @\common\models\Orders::getTypeName($order['type_order']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if($order['status']==4 ):?>
                                        <i class="fa fa-check text-success"></i>
                                    <?php endif; ?>
                                    <?php if ($order['type_order'] == 1): ?>
                                        <a data-toggle="tooltip" data-placement="left" title="รายละเอียด"
                                           href="<?= Url::to(['view-details', 'id' => $order['id']]) ?>" class=""><i
                                                    class="fa fa-list-ol "></i></a>
                                        <a data-placement="right" data-toggle="tooltip" title="พิมพ์"
                                           href="<?= Url::to(['delivery-print', 'id' => $order['id']]) ?>" class=""><i
                                                    class="fa fa-print "></i></a>
                                    <?php elseif ($order['type_order'] == 2): ?>
                                        <a data-toggle="tooltip" data-placement="left" title="รายละเอียด"
                                           href="<?= Url::to(['view-details', 'id' => $order['id']]) ?>" class=""><i
                                                    class="fa fa-list-ol "></i></a>
                                        <a data-placement="right" data-toggle="tooltip" title="พิมพ์"
                                           href="<?= Url::to(['print-with-out', 'id' => $order['id']]) ?>" class=""><i
                                                    class="fa fa-print "></i></a>

                                    <?php else: ?>
                                    <?php endif; ?>


                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>


<?php
\backend\assets\DatatableAsset::register($this);
$js = <<<JS
$('#reciveorder').dataTable({
// "responsive": true,
"processing": true,
 "iDisplayLength": 20,
    "aLengthMenu": [[10 ,20, 35, 50 , -1], [10 ,20, 35, 50,  "All"]],
		//"sPaginationType": "bootstrap",
		 "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 0 ] }
		],
		// "aaSorting": [[ 1, "asc" ]],
		"oLanguage": {
			"sLengthMenu": "_MENU_ ",
			"sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
		},
		 "zeroRecords": "ไม่พบข้อมูล..",
		 "columns": [
            { "data": "miColumnToSum", "name": "CodigoDeBarras", "autoWidth": true }
             //etc etc.....
        ],
        "rowCallback": function( row, data, index ) {
           var sum = 0;
           sum += data.miColumnToSum;
           //DO WHAT YOU WANT
        }
});
$('#reciveorder').on( "click",function() {
		$("#quick-access").css("bottom","0px");
    });

	$('#reciveorder_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#reciveorder_wrapper .dataTables_length select').addClass("select2-wrapper span12");

	

JS;
$this->registerJs($js);
?>
