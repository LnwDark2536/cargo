<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use common\models\Customers;
use yii\helpers\Url;
use kartik\grid\GridView;

?>

<div class="grid simple form-grid">
    <div class="grid-title no-border">
        <h3>ค้นหาเลขขนส่งของลูกค้า </h3>
    </div>
    <div class="grid-body no-border">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]); ?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'customers_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => ArrayHelper::map(Customers::find()->all(),
                        'id',
                        'customer_code'),
                    'language' => 'th',
                    'pluginOptions' => ['initialize' => true],
                    'options' => [
                        'id' => 'ddl-customer',
                        'placeholder' => 'เลือกชื่อลูกค้า...'
                    ],
                ])->label('Code Customer'); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'ddl-status'],
                    'data' => $Arraystatus,
                    'pluginOptions' => [
                        'depends' => ['ddl-customer'],
                        'placeholder' => 'เลือกสถานะ...',
                        'url' => Url::to(['packing/get-status'])
                    ]
                ])->label('สถานะ Packing'); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'jj_number')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'ddl-jj_number'],
                    'data' => $Arraystatus,
                    'pluginOptions' => [
                        'depends' => ['ddl-customer','ddl-status'],
                        'placeholder' => 'เลือกสถานะ...',
                        'url' => Url::to(['packing/get-number'])
                    ]
                ])->label('เลขขนส่ง'); ?>
            </div>
            <div class="col-md-3">
                <br>
                <?= Html::submitButton('<i class="fa fa-search"></i> ค้นหา', ['class' => 'btn btn-success btn-block']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <hr>
        <?php if (!empty($dataProvider)): ?>
            <div class="text-right">
                <a href="<?=Url::to(['packing/print-customer','status'=>$status,'cus_id'=>$customers_id,'jj'=>$jj_number])?>" class="btn btn-primary"> <i class="fa fa-print"> </i></a>
            </div>
            <?php

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'showPageSummary' => true,
                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => true,
                'responsive' => true,
                'persistResize' => false,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn',
                        'width' => '36px',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'headerOptions' => ['class' => 'kartik-sheet-style'],
                    ],
                    [
                        'label' => 'วันรับของ',
                        'attribute' => 'created_at',
                        'value'=>function($model){
                            return Yii::$app->formatter->asDate($model['created_at'] ,'dd/MM/yyyy');
                        },
                        'pageSummary' => 'Total',
                        'vAlign' => 'middle',
                    ],
                    [
                        'label' => 'ออร์เดอร์',
                        'attribute' => 'invoice_id'
                    ],
                    [
                        'label' => 'ร้านค้า',
                        'attribute' => 'supplier'
                    ],
                    [
                        'label' => 'รหัสสินค้า',
                        'attribute' => 'type_code'
                    ],
                    [
                        'label' => 'จำนวน',
                        'attribute' => 'quantity',
                        'hAlign' => 'right',
                        'vAlign' => 'middle',
                        'width' => '7%',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'label' => 'ราคา',
                        'attribute' => 'unit_price',
                        'hAlign' => 'right',
                        'vAlign' => 'middle',
                        'width' => '7%',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'label' => 'รวมเงิน',
                         'hAlign' => 'right',
                        'vAlign' => 'middle',
                        'value'=>function($model){
                            return @$model['quantity'] * $model['unit_price'];
                        },
                        'width' => '7%',
                        'format' => ['decimal', 2],
                        'pageSummary' => true
                    ],
                    [
                        'label' => 'น้ำหนัก(KG.)',
                        'attribute' => 'kg',
                        'hAlign' => 'right',
                        'vAlign' => 'middle',
                        'width' => '10%',
                        'format' => ['decimal', 2],
                        'pageSummary' => true
                    ],
                    [
                        'label' => 'ลูกบาศก์เมตร',
                        'attribute' => 'm',
                        'hAlign' => 'right',
                        'vAlign' => 'middle',
                        'width' => '10%',
                        'format' => ['decimal', 2],
                        'pageSummary' => true
                    ],
                ],
            ]);

            ?>
        <?php endif; ?>
    </div>
</div>
