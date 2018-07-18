<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Update invoice: ' . $modelOrderS->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelOrderS->id, 'url' => ['view', 'id' => $modelOrderS->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form.backup', [
        'modelOrderS' => $modelOrderS,
        'modelsOrderSD' => (empty($modelsOrderSD)) ? [new OrderDetails] : $modelsOrderSD
    ]) ?>

</div>
