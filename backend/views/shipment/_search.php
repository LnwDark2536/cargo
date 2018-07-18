<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="input-group">
<?php

echo \kartik\daterange\DateRangePicker::widget([
    'name'=>'created_at',
    'useWithAddon'=>true,
    'language'=>'th',             // from demo config
    'hideInput'=>true,           // from demo config
    'presetDropdown'=>true, // from demo config
    'pluginOptions'=>[
        //'locale'=>[ 'format' => 'Y-mm-d'], // from demo config
//        'separator'=>'-',       // from demo config
        'opens'=>'left'
    ]
]);
?>
        <span class="input-group-btn">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
               <span class="input-group-btn">
    </div>
    <?php ActiveForm::end(); ?>

</div>
