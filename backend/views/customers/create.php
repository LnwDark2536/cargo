<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Customers */

$this->title = 'Create Customers';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="page-title"> <i class="fa fa-user-plus"></i>
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'user'=>$user
    ]) ?>

