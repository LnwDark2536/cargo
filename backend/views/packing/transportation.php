<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
$this->title='รายการเลขขนส่ง';
?>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple ">
            <div class="grid-title">
                <h4><i class="fa fa-list-alt"></i>รายการเลขขนส่ง </h4>
            </div>
            <div class="grid-body table-responsive">
                <table class=" table table-hover table-bordered table-responsive-lg table-responsive-md table-responsive-sm" id="table" width="100%">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">เลขรอบขนส่ง</th>
                        <th width="10%">จำนวนรายการ</th>
                        <th width="10%" class="">สถานะ</th>
                        <th width="7%" class="text-center">จัดการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as$n=> $item):?>
                        <tr>
                            <td><?=$n+1?></td>
                            <td><?=$item['transport_jjnp']?></td>
                            <td><?=$item['count_item']?></td>
                            <td><?=@\common\models\Packing::getStatus($item['status'])?></td>
                            <td class="v-align-middle text-center">
                                <?php if($item['status']===1):?>
                                    <?= Html::a('<i
                                            class="fa fa-truck"></i> ปิดตู้', ['packing/close-tran', 'id' => $item['transport_number']], [
                                        'class' => 'btn btn-primary btn-small',
                                        'data-toggle'=>'tooltip',
                                        'data-placement'=>'ปิดตู้ ส่งของ',
                                        'data' => [
                                            'confirm' => 'ต้องการปิดตู้หรือไหม ?',
                                        ],
                                    ])?>

                            <?php endif;?>
                                <a data-toggle="tooltip" data-placement="left" title="รายละเอียด Packing"
                                   href="<?= Url::to(['packing/tran-details', 'id' => $item['transport_number']]) ?>" class="btn btn-warning btn-small"><i
                                            class="fa fa-list-ol "></i> รายละเอียด</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

<?php
$JS=<<<JS

JS;
$this->registerJS($JS);
\backend\assets\DatatableAsset::register($this);

?>

