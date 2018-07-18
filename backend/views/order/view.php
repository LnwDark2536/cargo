<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$totalAll=0;
?>
<div class="order-view">
    <div class="text-right">
        <?= Html::a('<i class="fa fa-home"></i> back index', ['index'], ['class' => 'btn btn-info btn-small']) ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-small']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-small',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

            <div class="row">
                <div class="col-md-5">
                    <div class="grid simple vertical green">
                        <div class="grid-title no-border">
                            <h4><i class=" fa fa-file"></i>#Order <?= $model->invoice_id ?></h4>
                        </div>
                        <div class="grid-body ">
                                <?= DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        //  'id',
                                        'invoice_id',
//                            'bank',
                                        'fullName',
                                        'supplier.name',
                                        'phone',
                                        ['attribute' => 'deposit',
                                            'format' => ['decimal', 0]
                                        ],
                                        'paymentName',
                                        [
                                            'label' => 'Date Order',
                                            'attribute' => 'date_order',
                                            'headerOptions' => ['class' => 'text-center'],
                                            'format' => ['date', 'php:d/m/Y'],
                                            //'contentOptions' => ['style' => 'width:100px;']
                                        ],
                                    ],
                                ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="grid simple vertical warning">
                        <div class="grid-title no-border">
                            <h4><i class="fa fa-list-ol"></i> List item</span></h4>
                        </div>
                        <div class="grid-body">
                            <div class="row-fluid">
                                <table class="table table-bordered table-hover" width="100%">
                                    <thead >
                                    <tr >
                                        <th width="5%">#</th>
                                        <th width="30%" >Product Type (类别)</th>
                                        <th width="12%">Style (款号)</th>
                                        <th  width="15%" class="text-right">Quantity (数量)</th>
                                        <th  width="15%" class="text-right">UnitPrice (单价)</th>
                                        <th  width="10%" class="text-right" >Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($dataProvider as$index=> $data):?>
                                        <?php $totalAll +=intval($data['quantity']*$data['unit_price'])?>
                                    <tr>
                                        <td><?=@$index+1?></td>
                                        <td><?=@$data['product_code']?></td>
                                        <td><?=@$data['style']?></td>
                                        <td class="text-right"><?=@number_format($data['quantity'],0)?></td>
                                        <td class="text-right"><?=@number_format($data['unit_price'],0)?></td>
                                        <td class="text-right"><?=@number_format($data['quantity']*$data['unit_price'],0)?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="warning">
                                        <th colspan="5" class="text-right" style="font-size: 18px;"> Summary</th>
                                        <th class="text-right" style="font-size: 18px;"><?=@number_format($totalAll-$model->deposit,2)?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

