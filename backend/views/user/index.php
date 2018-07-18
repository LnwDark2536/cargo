<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="text-right">
        <?= Html::a('<i class="fa fa-user-md"></i>  สร้างผู้ใช้งาน', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           // 'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'email:email',
            [
                'attribute'=>'status',
                'format'=>'html',
               //'filter'=>$searchModel->itemStatus,
                'value'=>function($model){
                    return $model->statusName=='Active' ?'<span class="text-success">'.$model->statusName.'</span>' : $model->statusName ;
                }
            ],
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {cancel}',
                'buttons' => [
                    'cancel' => function ($url, $model, $key) {
                        return $model->id != 1  ?Html::a('<i class="glyphicon glyphicon-lock text-danger"></i>', ['cancel', 'id' => $model->id], [
                            'class' => '',
                            'data' => [
                                'confirm' => 'ต้องการปิดใช้านใช้ หรือไม่ ?',
                                'method' => 'post',
                            ],
                        ]):null;
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
