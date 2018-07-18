<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <?php Pjax::begin(); ?>
    <div class="grid simple">
        <div class="grid-title no-border">
            <h2><i class="fa fa-book"></i> Account </h2>
        </div>
        <div class="grid-body no-border">
            <p>
                <?= Html::a('Create Account', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <div class="table-responsive">

                <?php echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table  table-responsive'],
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'label' => 'ชื่อธนาคาร',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return !empty($model['name_bank']) ? $model['name_bank'] : '-';
                            }
                        ],
                        'name_account',
                        'number_bank',
                        [
                            'label' => 'ยอดคงเหลือ',
                            'format' => ['decimal', 2],
                            'attribute' => 'balance'
                        ],
                        [
                            'format' => 'raw',
                            'value' => function ($model) {
                                $tr = \common\models\Transactions::findOne(['account_id' => $model['id']]);
                                if ($model['id'] != $tr['account_id']) {
                                    return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['account/update', 'id' => $model['id']]) . ' ' .
                                        Html::a('<i class="glyphicon glyphicon-trash"></i>', ['account/delete', 'id']);
                                } else {
                                    return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['account/update', 'id' => $model['id']]);
                                }
                            }
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>

    <?php Pjax::end(); ?></div>
