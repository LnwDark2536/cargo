<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CategoryProduct */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-create">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
