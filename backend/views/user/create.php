<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h2><?= Html::encode($this->title) ?></h2>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
