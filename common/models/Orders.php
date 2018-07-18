<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $invoice_id
 * @property string $bank
 * @property string $customers_id
 * @property string $supplier_id
 * @property integer $phone
 * @property integer $deposit
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 * @property integer $receive_order
 * @property  integer $total_weight
 * @property  integer $payment
 * @property  integer $date_order;
 * @property  string $account_number
 * @property  string $tracking_number
 * @property  string $account_name
 * @property integer $order_bags
 * @property  integer $type_order
 */
class Orders extends \yii\db\ActiveRecord
{
    public $items;

    public static function tableName()
    {
        return 'orders';
    }

    //status 0 add product
    // 1
    public function behaviors()
    {
        return [
//            [
//                'class' => 'mdm\autonumber\Behavior',
//                'attribute' => 'bill_number', // required
//                'value' => '?' ,  // format auto number. '?' will be replaced with generated number
//                'digit' => 5 // optional, default to null.
//            ],
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
            // [['invoice_id'], 'autonumber', 'format'=>'JJ'.$this->id],
            // [['customers_id', 'supplier_id'], 'required'],
//            [['created_at'],'date','format'=>'dd/mm/yyyy'],
            ['invoice_id', 'unique', 'targetClass' => '\common\models\Orders', 'message' => 'มีการใช้เลข No.Order ไปแล้ว!...'],
            [['supplier_id', 'customers_id', 'bank', 'phone', 'account_name', 'account_number', 'order_bags', 'deposit', 'created_at', 'updated_at', 'bill_number', 'created_by', 'updated_by', 'items', 'payment', 'supplier_id', 'invoice_id', 'status', 'total_weight', 'date_order', 'tracking_number', 'type_order'], 'safe'],
            //[['invoice_id', 'bank', 'customer_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracking_number' => 'Tracking Number',
            'invoice_id' => 'No order (订单单号)',
            'account_name' => 'Account Name (开户人)',
            'account_number' => 'Account Number (帐户/卡号)',
            'customers_id' => 'Customer(客户)',
            'bank' => 'Bank (银行)',
            'order_bags' => 'bags (订单单号)',
            'supplier_id' => 'Supplier(档口)',
            'phone' => 'Phone (手机)5',
            'payment' => 'Payment (付款方式)',
            'paymentName' => 'Payment (付款方式)',
            'fullName' => 'Customer Name',
            'deposit' => 'Deposit (定金)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function getOrderdetails()
    {
        return $this->hasMany(OrderDetails::className(), ['order_id' => 'id']);
    }

    public function getCustomers()
    {
        return $this->hasOne(Customers::className(), ['id' => 'customers_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUserName()
    {
        return @$this->user->username;
    }

    public function getFullName()
    {
        return @$this->customers->customer_code;
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getSupplierName()
    {
        return @$this->supplier->name;
    }

    public function getWeightAll()
    {
        $q = Yii::$app->db->createCommand("SELECT order_id,sum(weight_amount) as weight_total FROM weight_details
WHERE order_id=:order_id
GROUP BY order_id  ")->bindValues([':order_id' => $this->id])->queryAll();
        foreach ($q as $weightALL) {
            return $weightALL['weight_total'];
        }
    }

    public static function itemsName($key)
    {
        $items = [
            'payment' => [
                0 => 'CASH',
                1 => 'CREDIT ',
            ]
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getPayment()
    {
        return self::itemsName('payment');
    }
    public static function PaymentCheck($key)
    {
        if(!empty($key)){
            $data= [
                0 => 'CASH',
                1 => 'CREDIT ',
            ];
            return $data[$key];
        }else{
            return '-';
        }
    }

    public function getPaymentName()
    {
        return ArrayHelper::getValue($this->getPayment(), $this->payment);
    }

    static function getTypeStatus()
    {
        $data = [
            0 => 'รายการรับทั้งหมด',
            1 => 'มีใบเสร็จ',
            2 => 'ไม่มีใบเสร็จ',
            3 => 'รับสินค้าจากไปรษณีย์',
            4 => 'มีใบเสร็จ / ไม่มีใบเสร็จ',
        ];
        return $data;
    }

    static function getTypeName($key)
    {
        $type = [
            0 => '<i class="fa  fa-circle text-info" aria-hidden="true" data-placement="right" data-toggle="tooltip" title="add Order"></i>',
            1 => '<i class="fa  fa-circle text-success" aria-hidden="true"  data-placement="right" data-toggle="tooltip" title="รับสินค้ามีใบเสร็จ"></i>',
            2 => '<i class="fa  fa-circle text-danger" aria-hidden="true" data-placement="right" data-toggle="tooltip" title="รับสินค้าไม่มีใบเสร็จ"></i>',
            3 => '<i class="fa  fa-circle text-info" aria-hidden="true" data-placement="right" data-toggle="tooltip" title="จากไปรษณีย์"></i>',
            4 => 'Packing',
            6 => '<span class="label label-success">จ่ายแล้ว</span>'
        ];
        return $type[$key];
    }
    static function getTypeNameLabel($key)
    {
        $type = [
            1 => '<span class="label label-success" >รับสินค้ามีใบเสร็จ</span>',
            2 => '<span class="label label-danger">รับสินค้าไม่มีใบเสร็จ</span>',
            3 => '<span class="label label-info" >จากไปรษณีย์</span>',
            6 => '<span class="label label-success">จ่ายแล้ว</span>'
        ];
        return $type[$key];
    }
    public static function getPriceOrder($order_id){
       $query= Yii::$app->db->createCommand("SELECT sum(od.unit_price * od.quantity ) FROM orders o 
left join  order_details od on o.id = od.order_id
WHERE o.id = :id")->bindValues(['id'=>$order_id])->queryAll();
       foreach ($query as $model){
           return $model['sum'];
       }
    }
}
