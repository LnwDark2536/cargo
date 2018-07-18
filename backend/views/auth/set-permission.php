<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Auth */

$this->title = $models->name;
$this->params['breadcrumbs'][] = ['label' => 'Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="auth-view">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h4>กำหนด <span class="semi-bold">กลุ่มผู้ใช้งาน</span></h4>
        </div>
        <div class="grid-body no-border">
            <div class="row">
                <div class="col-md-3">
                    <?= DetailView::widget([
                        'model' => $models,
                        'attributes' => [
                            'description:ntext',
                        ],
                    ]) ?>
                </div>
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
