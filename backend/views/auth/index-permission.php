<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modelsAuthSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permission';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        <?= Html::a('Create Role', ['create'], ['class' => 'btn btn-warning']) ?>

        <?= Html::a('index Role', ['index'], ['class' => 'btn btn-warning   ']) ?>
        <?= Html::a('หน้าสิทธ์หน้า', ['index-permission'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('สร้างสิทธ์หน้า', ['create-permission'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'type',
            'description:ntext',
            //'rule_name',
           // 'data',
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{update} {delete}',
//                'buttons' => [
//                    'update' => function ($url, $model, $key) {
//                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['create-permission']);
//                    }
//                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
