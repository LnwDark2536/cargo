<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Customers */

$this->title = 'Update Customers: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customers-update">

    <?= $this->render('_form', [
        'model' => $model,
        'user'=>$user,
    ]) ?>

</div>
