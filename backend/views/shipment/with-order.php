<?php
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$this->title="รับสินค้ามีใบสั่งของ";
$customer=ArrayHelper::map(Yii::$app->db->createCommand("SELECT customer_code,id,CONCAT(customer_code, '(',name,lastname,')') AS code_fullname FROM customers")->queryAll(), 'customer_code', 'code_fullname');
?>
<style>
    .btn-none {
        display: none;
    }
</style>


<?php

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'attribute' => 'invoice_id',
        'format'=>'raw',
        'value'=>function($model){
            return Html::a($model->invoice_id,['receive-order', 'id' => $model->id]);
        }
    ],
    [
        'attribute' => 'fullName',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $customer,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'minimumInputLength' => 2,
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => ['placeholder' => 'SearchCustomer...'],
        'format' => 'raw'
    ],
    // 'fullName',
    'supplier.name',
    [
        'attribute' => 'deposit',
        'headerOptions' => ['class' => 'text-center'],
        'format' => ['decimal', 0],
        //'contentOptions' => ['style' => 'width:100px;']
    ],
    [
        'label' => 'Created Date',
        'attribute' => 'created_at',
        'headerOptions' => ['class' => 'text-center'],
        'format' => ['date', 'php:d/m/Y'],
        //'contentOptions' => ['style' => 'width:100px;']
    ],
    [
        'class' => 'yii\grid\ActionColumn',

        'contentOptions' => [
           'class' => 'text-center',
            'noWrap' => true
        ],
      //  'template'=>'<div class="btn-group btn-group-sm text-center" role="group">{receive} {order-update} {delete}</div>',
        'template' => ' {receive} {receive-deliverynot}  {delete}',
        'buttons' => [
                'delete' => function ($model,$key,$url) {
                    $url_to = Url::to(['delete', 'id' => $url]);
                    return Html::a('<i class="fa fa-trash "></i>', $url_to, [
                        'class' => 'text-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ;
                },
            'receive' => function ($model, $key, $url) {
                $url_to = Url::to(['receive-order', 'id' => $url,'page'=>'receive']);
                return Html::a('<i class="fa fa-file"></i>', $url_to, ['class' => 'text-success','data-toggle'=>'tooltip','data-placement'=>'top','data-original-title'=>'บันทึกรับสินค้า']);
            },

        ],
    ],
];
?>
<div class="row-fluid">
    <div class="span12">
        <div class="grid simple grid simple vertical green">
            <div class="grid-title">
                <h3 class="text-left"><i class="fa fa-file text-success"> </i> <?= Html::encode($this->title) ?></h3>
            </div>
            <div class="grid-body ">
                <div class="table-responsive" style="overflow-x:auto;">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'bordered' => true,
                    'striped' => false,
                    'condensed' => false,
                    'responsive' => true,
                    'responsiveWrap' => false,
                    'hover' => false,
                    'columns' => $columns
                ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>




