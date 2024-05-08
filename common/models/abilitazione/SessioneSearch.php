<?php
namespace common\models\abilitazione;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\abilitazione\Sessione;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for searching Sessione.
 */

class SessioneSearch extends Sessione
{
    public function rules()
    {
        return [
			[['expire','user_id'], 'integer'],
			[['id'],'string','max' => 40],
			[['browser_platform'],'string','max' => 200],
			[['ipaddress'],'string','max' => 40],
			[['last_write'], 'safe'],
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
        $query = Sessione::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'=>$this->id
        ]);

        /*$query->andFilterWhere(['like', 'DescObiettivo', $this->DescObiettivo])
            ->andFilterWhere(['like', 'NotaObiettivo', $this->NotaObiettivo])
            ->andFilterWhere(['like', 'utente', $this->utente]);
            */
        return $dataProvider;
    }	



}

