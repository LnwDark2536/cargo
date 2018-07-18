<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property Auth[] $children
 * @property Auth[] $parents
 */
class Auth extends \yii\db\ActiveRecord
{
    public $roles;
    public $permissions;
    public static function tableName()
    {
        return 'auth_item';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['roles','permissions'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'typeGroupName' => 'ประเภทกลุ่ม',
            'description' => 'ชื่อกลุ่มใช้งาน',
            'permissions'=>'สิทธิ์การใช้งาน',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }
    public static function itemsAlias($key){

        $items = [
            'type'=>[
                1 => 'ระบบกำหนดให้',
                2 => 'กำหนดกลุ่มใช้งานเอง',
           ]
        ];
        return ArrayHelper::getValue($items,$key,[]);
        //return array_key_exists($key, $items) ? $items[$key] : [];
    }
    public function getTypeGroup()
    {
        return self::itemsAlias('type');
    }
    public function getTypeGroupName(){
        return ArrayHelper::getValue($this->getTypeGroup(),$this->type);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Auth::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Auth::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    public function getAllRoles()
    {
        $auth = Yii::$app->authManager;
        return ArrayHelper::map($auth->getRoles(), 'name', 'name');
    }

    public function getAllPermissions()
    {
        $auth = Yii::$app->authManager;
        return ArrayHelper::map($auth->getPermissions(), 'name', 'description');
    }
    public function getAllPermission()
    {
        $auth = Yii::$app->authManager;
        return ArrayHelper::map($auth->getPermissions(), 'name', 'name');
    }

    public function getPermissionsByRole()
    {
        $auth = Yii::$app->authManager;
        $rolePermission = $auth->getPermissionsByRole($this->name);
        $roleItems = $this->getAllPermission();
        $roleSelect = [];
        foreach ($roleItems as $key => $roleName) {
            foreach ($rolePermission as $role) {
                if ($key == $role->name) {
                    $roleSelect[$key] = $roleName;
                }
            }
        }
        $this->permissions = $roleSelect;
    }
}
