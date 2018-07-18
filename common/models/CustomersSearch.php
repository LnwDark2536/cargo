<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customers;

/**
 * CustomersSearch represents the model behind the search form about `common\models\Customers`.
 */
class CustomersSearch extends Customers
{
    /**
     * @inheritdoc
     */
    public $fullName;
    public function rules()
    {
        return [
//            [['id', 'sex', 'rate', 'user_id'], 'integer'],
            [['name','customer_code', 'fullName','lastname', 'email', 'phone', 'recommender', 'updated_at'], 'safe'],
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
        $query = Customers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_ASC,
                ]
            ],
        ]);
        $dataProvider->sort->attributes['fullName'] = [
            'asc' => ['name' => SORT_ASC, 'lastname' => SORT_ASC],
            'desc' => ['name' => SORT_DESC, 'lastname' => SORT_DESC],
            'default' => SORT_ASC
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $date_start=substr($this->created_at,0,10);
        $date_end=substr($this->created_at,13,10);

//        $query->andFilterWhere(['>=', 'created_at', $this->$date_start])
//            ->andFilterWhere(['<=', 'created_at', $this->$date_end]);
        // grid filtering conditions
        $query->orFilterWhere(['like', 'name', $this->fullName])
            ->orFilterWhere(['like', 'lastname', $this->fullName]);
        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'recommender', $this->recommender]);

        return $dataProvider;
    }
}
