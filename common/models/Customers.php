<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customers".
 *
 * @property integer $id
 * @property string $name
 * @property string $lastname
 * @property integer $sex
 * @property string $email
 * @property integer $id_card
 * @property string $phone
 * @property string $address
 * @property integer $rate
 * @property string $recommender
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $customer_code
 */
class Customers extends \yii\db\ActiveRecord
{
//    public  $fullName;
    const SEX_MEN = 1;
    const SEX_WOMEN = 2;

    public static function tableName()
    {
        return 'customers';
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
           // [['sex', 'id_card', 'rate', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [[ 'sex'], 'required'],
            [['created_at', 'updated_at', 'address','customer_code','email', 'phone', 'sex','name', 'lastname','recommender','phone'], 'safe'],
//            [['id_card'],'integer','max' => 13],
            ['customer_code', 'unique', 'targetClass' => '\common\models\Customers', 'message' => 'This Customers Not null.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'customer_code'=>'Code',
            'id_card'=>'ID Card',
            'lastname' => 'Lastname',
            'sex' => 'Sex',
            'email' => 'Email',
            'phone' => 'Phone',
            'rate' => 'Rate',
            'recommender' => 'ผู้แนะนำ',
            'user_id' => 'User ID',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'Updated At',
        ];
    }

    public static function itemsAlias($key)
    {

        $items = [
            'sex' => [
                self::SEX_MEN => 'Male',
                self::SEX_WOMEN => 'Female'
            ],
            'title' => [
                1 => 'นาย',
                2 => 'นางสาว',
                3 => 'นาง'
            ],

        ];
        return ArrayHelper::getValue($items, $key, []);
        //return array_key_exists($key, $items) ? $items[$key] : [];
    }

    public function getFullname(){
        return $this->name. " " .$this->lastname;
    }
    public function getItemSex()
    {
        return self::itemsAlias('sex');
    }
    public function getSexName(){
        return ArrayHelper::getValue($this->getItemSex(),$this->sex);
    }
}
