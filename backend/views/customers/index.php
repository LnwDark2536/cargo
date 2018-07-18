<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
    <div class="page-title"><i class="fa fa-user"></i>
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="text-right">
        <?= Html::a('<i class="fa fa-plus-circle"> </i> Create Customers', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'customer_code',
         'fullName',
            'sexName',
            'email:email',
            'phone',
            'address',
            // 'rate',
            // 'recommender',
            // 'user_id',
            'created_at:date',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
