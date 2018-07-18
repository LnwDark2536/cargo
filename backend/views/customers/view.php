<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customers */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-view">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('BackIndex', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
         //   'id',
            'fullName',
            'sexName',
            'email:email',
            'phone',
            'address',
            'id_card',
            'rate',
            'recommender',
//            'user_id',
            'created_at:datetime',
//            'updated_at',
        ],
    ]) ?>

</div>
</div>
