<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modelsAuthSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'กลุ่มผู้ใช้งาน';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
        <div class="auth-index">
            <div class="grid simple">
                <div class="grid-title no-border">
                    <h4>กำหนด <span class="semi-bold">กลุ่มผู้ใช้งาน</span></h4>
                </div>
                <div class="grid-body no-border">
            <p>
                <?= Html::a('สร้างกลุ่มใช้งาน', ['create'], ['class' => 'btn btn-primary']) ?>

            </p>
              <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'description:ntext',
                    [
                        'label' => 'ประเภทกลุ่ม',
                        'value' => function($model){
                            return $model->name != 'Customer' && $model->name != 'Admin'?'กำหนดเอง':'ระบบกำหนดให้';
                        }
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'contentOptions' => [
                            'noWrap' => true
                        ],
                        'template' => ' {index} {update} {delete}',
                        'buttons' => [
                            'index' => function ($url, $model, $key) {
                                return $model->name != 'Customer' && $model->name != 'Admin' ?Html::a('<i class="fa fa-check-circle text-success" ></i>', $url):null ;
                            },
                            'update' => function ($url, $model, $key) {
                                return $model->name != 'Customer' && $model->name != 'Admin'? Html::a('<i class="glyphicon glyphicon-pencil text-info"></i>', $url):null ;
                            },
                            'delete' => function ($url, $model, $key) {
                            $Asm=\common\models\AuthAssignment::findOne(['item_name'=>$model->name]);
                            if($Asm['item_name']===$model->name){
                            }else{
                                return $model->name != 'Customer' && $model->name != 'Admin' ? Html::a('<i class="glyphicon glyphicon-trash text-error"></i>', ['delete', 'id' => $model->name], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) : null;
                            }

                            }
                        ]
                    ],
                ],
            ]); ?>
                </div>
            </div>
        </div>
    </div>
    <?php if(!empty($model)): ?>
    <div class="col-md-6">

            <div class="grid simple">
                <div class="grid-title no-border">
                    <h4>กำหนด <span class="semi-bold">กลุ่มผู้ใช้งาน</span></h4>
                </div>
                <div class="grid-body no-border">
                    <br>
                    <br>
                    <br>
                    <div class="clearfix"></div>
                    <div class="row">

                        <div class="col-md-9">
                            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
                            <h4> <code>เลือกสิทธ์ใช้งาน</code></h4>
                            <?= $form->field($model, 'permissions')->checkboxList($model->getAllPermissions())->label(false) ?>
                            <div class="form-group text-right">
                                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'บันทึกข้อมูล', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
                                <?= Html::a('cancel', ['index'], ['class' => 'btn btn-danger']) ?>
                            </div>

                            <?php \yii\widgets\ActiveForm::end(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div>

