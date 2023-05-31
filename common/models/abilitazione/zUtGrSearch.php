<?php
namespace common\models\abilitazione;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\abilitazione\zutgr;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for searching zUtGr.
 */

class zUtGrSearch extends zutgr
{
	
	public static function tableName()
    {
        return 'zutgr';
    }
	
    public function rules()
    {
        return [
			[['idgruppo','id','idutgr'], 'integer'],
			[[], 'boolean','trueValue'=>'-1'],
			[[], 'safe'],
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
        $query = zutgr::find()->with(['zgruppo','user','user.soggetto']);

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
            'idutgr'=>$this->idutgr
        ]);

        /*$query->andFilterWhere(['like', 'DescObiettivo', $this->DescObiettivo])
            ->andFilterWhere(['like', 'NotaObiettivo', $this->NotaObiettivo])
            ->andFilterWhere(['like', 'utente', $this->utente]);
            */
        return $dataProvider;
    }	


    /**
     * Gets query for [[zGruppo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZgruppo()
    {
        return $this->hasOne(\common\models\abilitazione\zgruppo::class,  ['idgruppo' => 'idgruppo']);
    }    

    /**
     * Gets query for [[user]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\abilitazione\user::class,  ['id' => 'id']);
    }    


}

