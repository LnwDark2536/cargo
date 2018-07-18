<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CategoryProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-index">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="text-right">
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>
<?php Pjax::begin(); ?>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'type_code',
            'description',
            'chinese_description',
            'unit',
            'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
    </div>
</div>
