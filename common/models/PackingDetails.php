<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "packing_details".
 *
 * @property integer $id
 * @property integer $packing_id
 * @property integer $ctn_no
 * @property integer $order_id
 * @property integer $od_id
 * @property integer $customers_id
 * @property string $quantity
 * @property string $unit_price
 * @property string $bags
 * @property string $kg
 * @property string $width
 * @property string $length
 * @property string $height
 */
class PackingDetails extends \yii\db\ActiveRecord
{
    public $status=0;
    public $jj_number=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packing_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packing_id', 'ctn_no', 'order_id', 'od_id', 'customers_id'], 'integer'],
            [['quantity', 'unit_price', 'bags', 'kg', 'width', 'length', 'height','status','jj_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'packing_id' => 'Packing ID',
            'ctn_no' => 'Ctn No',
            'order_id' => 'Order ID',
            'od_id' => 'Od ID',
            'customers_id' => 'Customers ID',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'bags' => 'Bags',
            'kg' => 'Kg',
            'width' => 'Width',
            'length' => 'Length',
            'height' => 'Height',
        ];
    }
}
