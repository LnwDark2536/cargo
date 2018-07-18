<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $status
 * @property integer $order_id
 * @property integer $packing_id
 * @property string $amount_money
 * @property string $details
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $amount_thai
 * @property integer $account_id
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
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
            [['customer_id', 'status', 'order_id', 'packing_id', 'created_by', 'updated_by'], 'integer'],
//            [['amount_money'], 'number'],
            [['created_at', 'updated_at','amount_money','amount_thai','account_id'], 'safe'],
            [['details'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'status' => 'Status',
            'order_id' => 'Order ID',
            'packing_id' => 'Packing ID',
            'amount_money' => 'Amount Money',
            'details' => 'Details',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public static function History(){
       return Yii::$app->db->createCommand("SELECT
	tn.ID,ac.name_account,C.customer_code,
	tn.amount_money,tn.created_at 
    FROM transactions tn
        LEFT JOIN customers C ON tn.customer_id = C.ID 
        left join account ac on tn.account_id = ac.id
    WHERE tn.status = 0 
ORDER BY tn.created_at DESC")->queryAll();
    }
    public static function BalanceCustomer($id){
        foreach (Yii::$app->db->createCommand("SELECT sum(amount_money)  as balance FROM transactions tn WHERE  tn.customer_id = $id")->queryAll() as $model){
            return !empty($model['balance'])?$model['balance']:0;
        }
    }
    public static function PaidAmount($order_id,$customers_id){
        foreach (Yii::$app->db->createCommand("SELECT sum(amount_money) as paid_amount   FROM transactions 
WHERE status = 1 and order_id = :order_id and customer_id = :customer_id")->bindValues(['order_id'=>$order_id,'customer_id'=>$customers_id])->queryAll() as $model){
            return !empty($model['paid_amount'])?$model['paid_amount'] *-1:0;
        }
    }
    public static function PayList($order_id,$customers_id){
        return Yii::$app->db->createCommand("SELECT *  FROM transactions WHERE status = 1 and order_id = :order_id and customer_id = :customer_id ORDER by created_at DESC ")
         ->bindValues(['order_id'=>$order_id,'customer_id'=>$customers_id])->queryAll();
    }

    public static function SaveTransactions($customers_id,$amountPay,$order_id){
        //หาid บัญชี
        $account_id = Transactions::getAccountID($customers_id,$amountPay);
        $order=Orders::findOne(['id'=>$order_id]);
        $tn = new  Transactions();
        $tn->customer_id = $customers_id;
        $tn->amount_money = -$amountPay;
        $tn->order_id = $order_id;
        $tn->account_id = $account_id;
        $tn->status = 1;
        if($tn->save()){
            $order->status = 4 ;
            $order->save();
        }
    }
    public static function getAccountID($customers_id,$amountPay){
       $query=Yii::$app->db->createCommand("SELECT sum(amount_money) as balanace ,account_id FROM transactions WHERE customer_id = :customer_id and status in (0,1)  GROUP BY account_id")->bindValues(['customer_id'=>$customers_id])->queryAll();
       foreach ($query as $model){
           if($amountPay < $model['balanace'] && $model['balanace'] > 0){
                   return $model['account_id'];
           }
       }
    }

}
