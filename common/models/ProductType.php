<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_type".
 *
 * @property integer $id
 * @property string $type_code
 * @property string $description
 * @property string $chinese_description
 * @property integer $unit
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_type';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit', 'created_at', 'updated_at'], 'integer'],
            [['type_code', 'description', 'chinese_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_code' => 'Product Code',
            'description' => 'Product Type',
            'chinese_description' => 'Chinese Description',
            'unit' => 'Unit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
