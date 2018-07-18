<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryProduct */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Category Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-view">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
    <p>
        <?= Html::a('BackIndex', ['index'], ['class' => 'btn btn-info btn-sm']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'description',
            'chinese_description',
            'unit',
            'created_at:datetime',
//            'updated_at',
        ],
    ]) ?>
    </div>
</div>
