<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ActivitySearch represents the model behind the search form of `app\models\Activity`.
 */
class ActivitySearch extends Activity
{

    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search'], 'safe'],
            [['id', 'category_id', 'maxpax'], 'integer'],
            [['name', 'description', 'photo', 'address'], 'safe'],
            [['priceperpax'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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

        $query = Activity::find();

        // Initialize ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Load the search term
        $this->load($params);

        if (!$this->validate()) {
            // If validation fails, return unfiltered data
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'maxpax' => $this->maxpax,
            'priceperpax' => $this->priceperpax,
            'category_id' => $this->category_id
        ]);

        // Apply filtering conditions based on the `search` attribute
        $query->andFilterWhere(['like', 'name', $this->search])
            ->orFilterWhere(['like', 'description', $this->search])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'address', $this->address]);


        return $dataProvider;
    }
}