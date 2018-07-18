<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Auth */

$this->title = 'Update Auth: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
