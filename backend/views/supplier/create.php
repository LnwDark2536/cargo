<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Supplier */

$this->title = 'Create Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
