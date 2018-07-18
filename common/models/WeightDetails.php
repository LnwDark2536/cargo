<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "weight_details".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $weight_amount
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 *   @property integer $order_details_id
 * @property integer $updated_by
 */
class WeightDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'weight_details';
    }

    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'weight_amount', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','order_details_id'], 'safe'],
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
            'product_id' => 'Product ID',
            'weight_amount' => 'Weight Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    public  function getProductType(){
        $query=Yii::$app->db->createCommand("SELECT CONCAT(type_code,' - ',description) as product_type FROM product_type WHERE id='".$this->product_id."'")->queryAll();
        foreach ( $query as $m){
            return !empty($m['product_type'])?$m['product_type']:0;
        }
    }
}
