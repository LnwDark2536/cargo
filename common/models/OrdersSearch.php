<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `common\models\Orders`.
 */
class OrdersSearch extends Orders
{
    public $fullName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         //   [['id', 'phone', 'deposit', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['invoice_id', 'bank', 'customers_id', 'supplier_id','fullName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find()->orderBy('created_at DESC');
        $query->joinWith(['customers']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        $dataProvider->sort->attributes['fullName'] = [
//            'asc' => ['customers.name' => SORT_ASC],
//            'desc' => ['customers.name' => SORT_DESC],
//            'default' => SORT_ASC
//        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->orFilterWhere(['like','customers.name',$this->fullName]);
        $query->orFilterWhere(['like','customers.lastname',$this->fullName]);
        $query->orFilterWhere(['like','customers.customer_code',$this->fullName]);
        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'deposit' => $this->deposit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'invoice_id', $this->invoice_id]);


        return $dataProvider;
    }
}
