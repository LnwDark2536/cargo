<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Account */

$this->title = 'Create Account';
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">
    <div class="grid simple">
        <div class="grid-title no-border">
            <h2><i class="fa fa-plus"></i> Create Account </h2>
        </div>
        <div class="grid-body no-border">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>