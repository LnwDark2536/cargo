<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "packing".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $transport_number
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Packing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $customers_id;
    public $type_id;
    public static function tableName()
    {
        return 'packing';
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
            [['transport_number'],'required'],
            [['transport_number'], 'string', 'min' => 5, 'max' => 10,'tooShort' => 'ใส่เลขขนส่งไม่ถูกต้อง...'],
//            ['transport_number', 'unique', 'targetClass' => '\common\models\Packing', 'message' => 'มีการใช้งานเลขขนส่งนี้แล้ว.'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at','transport_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customers_id'=>'เลือก customers Code',
            'type_id'=>'เลือกประเภทการรับ',
            'status' => 'Status',
            'transport_number' => 'Transport Number',
            'created_at' => 'วันที่สร้าง',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    static function getStatus($key){
        $status=[
            1=>'<i class="fa fa-circle-o-notch fa-spin  tex-info"></i> loading...',
            2=>'<i class="fa fa-telegram text-error"></i>   ปิดตู้และส่งของแล้ว'
        ];
        return $status[$key];
    }
}
