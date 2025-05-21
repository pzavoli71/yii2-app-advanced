<?php
namespace common\models\abilitazione;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\abilitazione\zgruppo;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for searching zgruppo.
 */

class ZgruppoSearch extends zgruppo
{
	
	public static function tableName()
    {
        return 'zgruppo';
    }
	
    public function rules()
    {
        return [
			[['idgruppo'], 'integer'],
			[[], 'boolean','trueValue'=>'-1'],
			[['nomegruppo'],'string','max' => 255],
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
        $query = zgruppo::find();

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
            'idgruppo'=>$this->idgruppo
        ]);

        /*$query->andFilterWhere(['like', 'DescObiettivo', $this->DescObiettivo])
            ->andFilterWhere(['like', 'NotaObiettivo', $this->NotaObiettivo])
            ->andFilterWhere(['like', 'utente', $this->utente]);
            */
        return $dataProvider;
    }	


    /**
     * Gets query for [[zpermessi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function searchZpermessi($params, $id)
    {
		$query = zgruppo::find()->with('zpermessi')->where('idgruppo=' . $id); // domandaquiz.domanda
		// add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ]            
        ]);
        $this->load($params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
         //   return $dataProvider;
        //}

        /*if ( !empty($this->DtFineRicerca)) {
            $query->andWhere(['<=','DtCreazioneTest',$this->DtFineRicerca]);
        }*/

        // grid filtering conditions
        /*$query->andFilterWhere([
            'IdQuiz' => $id, //$params->expandRowKey, //$this->expandRowInd, //IdQuiz,
        ]);	*/	
        /*$query->andFilterWhere(['like', 'DescObiettivo', $this->DescObiettivo])
            ->andFilterWhere(['like', 'NotaObiettivo', $this->NotaObiettivo])
            ->andFilterWhere(['like', 'utente', $this->utente]);
		*/
		return $dataProvider;
    }    

    /**
     * Gets query for [[zutgr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function searchZutgr($params, $id)
    {
		$query = zgruppo::find()->with('zutgr')->where('idgruppo=' . $id); // domandaquiz.domanda
		// add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ]            
        ]);
        $this->load($params);

        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
         //   return $dataProvider;
        //}

        /*if ( !empty($this->DtFineRicerca)) {
            $query->andWhere(['<=','DtCreazioneTest',$this->DtFineRicerca]);
        }*/

        // grid filtering conditions
        /*$query->andFilterWhere([
            'IdQuiz' => $id, //$params->expandRowKey, //$this->expandRowInd, //IdQuiz,
        ]);	*/	
        /*$query->andFilterWhere(['like', 'DescObiettivo', $this->DescObiettivo])
            ->andFilterWhere(['like', 'NotaObiettivo', $this->NotaObiettivo])
            ->andFilterWhere(['like', 'utente', $this->utente]);
		*/
		return $dataProvider;
    }    


}

