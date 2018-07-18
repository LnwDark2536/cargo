<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "packing_list".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $weight_total
 */
class PackingList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packing_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'weight_total'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'weight_total' => 'Weight Total',
        ];
    }
}
