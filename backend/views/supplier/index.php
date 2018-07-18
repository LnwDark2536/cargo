<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="text-right">
            <?= Html::a('<i class="fa fa-plus-circle"></i>  Create Supplier', ['create'], ['class' => 'btn btn-primary']) ?>
        </div>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'name',
                'phone',
                'address',
                'created_at:datetime',
                // 'updated_at',
                // 'created_by',
                // 'updated_by',
                ['class' => 'yii\grid\ActionColumn',
                    'contentOptions' => [
                        'noWrap' => true
                    ],
                'template' => '  {update} {delete}',
                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        return   Html::a('<i class="glyphicon glyphicon-pencil text-info"></i>', $url);
                    },
                    'delete' => function ($url, $model, $key) {
                $sp=\common\models\Orders::findOne(['supplier_id'=>$model->id]);
              if(empty($sp)){
                  return Html::a('<i class="glyphicon glyphicon-trash text-error"></i>', ['delete', 'id' => $model->name], [
                      'class' => '',
                      'data' => [
                          'confirm' => 'Are you sure you want to delete this item?',
                          'method' => 'post',
                      ],
                  ]);
              }else{
                  return null;

              }

                    }
                ]
            ],

            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>