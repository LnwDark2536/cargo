<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $unit_price
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $transport_name
 * @property string $tacking_id
 * @property string $bags
 * @property string $bill_number
 * @property string $details
 */
class OrderDetails extends \yii\db\ActiveRecord
{
    public $receive_amount;
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        //    [['product_id','quantity'],'required'],
//            [['quantity','unit_price' ],'trim'],
            [['order_id', 'product_id', 'quantity', 'unit_price', 'created_at','details','bags',
                'updated_at','receive_amount','style','transport_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Orders ID',
            'product_id' => 'Product Type (类别)',
            'quantity' => 'Quantity (数量)',
            'unit_price' => 'UnitPrice (单价)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    //sum receive Product
    public function getReceive(){
        $q=Yii::$app->db->createCommand("SELECT sum(receive_amount) as receive FROM receive_details
 WHERE product_id = :id AND  order_id=:order_id")->bindValues([':id'=>$this->product_id,':order_id'=>$this->order_id])->queryAll();
        foreach ($q as $num){
            return $num['receive'];
        }
    }
    public function getWeightTotal(){
        $q=Yii::$app->db->createCommand("SELECT product_id ,sum(weight_amount) weight_total FROM weight_details
WHERE product_id=:product_id and order_id=:order_id
GROUP BY product_id ")->bindValues([':product_id'=>$this->product_id,':order_id'=>$this->order_id])->queryAll();
        foreach ($q as $weight){
            return $weight['weight_total'];
        }
    }
    public function getWeightAll(){
        $q=Yii::$app->db->createCommand("SELECT order_id,sum(weight_amount) as weight_total FROM weight_details
WHERE order_id=:order_id
GROUP BY order_id 
 ")->bindValues([':order_id'=>$this->order_id])->queryAll();
        foreach ($q as $weightALL){
            return $weightALL['weight_total'];
        }
    }
    public  function getProductType(){
        $query=Yii::$app->db->createCommand("SELECT CONCAT(type_code,' - ',description) as product_type FROM product_type WHERE id='".$this->product_id."'")->queryAll();
        foreach ( $query as $m){
            return $m['product_type'];
        }
    }
}
