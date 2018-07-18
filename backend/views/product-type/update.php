<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryProduct */

$this->title = 'Update Product: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Category Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-product-update">

    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
