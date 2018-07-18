<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Auth */

$this->title = 'สร้างกลุ่มใช้งาน';
$this->params['breadcrumbs'][] = ['label' => 'กลุ่มผู้ใช้งาน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
