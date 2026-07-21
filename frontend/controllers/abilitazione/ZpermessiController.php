<?php

namespace frontend\controllers\abilitazione;

use common\models\abilitazione\zpermessi;
use common\models\abilitazione\zpermessiSearch;
use yii\web\Controller;
use frontend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * ZpermessiController implements the CRUD actions for zpermessi model.
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/zpermessi/create' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/zpermessi/update' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/zpermessi/delete' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/zpermessi/view' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/zpermessi/lista' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	
	
 */
class ZpermessiController extends BaseController
{
	public $layout = "mainform";
	/**
	* @inheritDoc
	*/
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'delete' => ['POST'],
					],
				],
			]
		);
	}

	/**
	*
	* @return string
	*/
	public function actionLista()
	{
		$searchModel = new zpermessiSearch();
		$dataProvider = $searchModel->search($this->request->queryParams);

		return $this->render('lista', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	* Displays a single zpermessi model.
	* @param int $idpermessi Id Doc Obiettivo
	* @return string
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionView($idpermessi)
	{
		return $this->render('view', [
			'model' => $this->findModel($idpermessi),
		]);
	}

	/**
	* Creates a new zpermessi model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return string|\yii\web\Response
	*/
	public function actionCreate()
	{
		$model = new zpermessi();

		if ($this->request->isPost) {
			// Scommentare se ci sono campi upload
			// $filesalvato = '';
			//$model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			//if (isSet($model->imageFile) && !($filesalvato = $model->upload(900))) {
				// file is uploaded successfully
			//    return;
			//}
			if ($model->load($this->request->post())) {
				//$transaction = $model->getDb()->beginTransaction();
				// if (isSet($model->imageFile)) {
					//$model->PathDoc = $filesalvato;
				// }
				if ($model->save()) {
					//$transaction->commit();
					return $this->redirect(['view', 'idpermessi'=>$model->idpermessi]);
				//} else {
				//    $transaction->rollBack();
				//    return false;
				}            
			}
		} else {
			// Mettere qui eventuali valori da assegnare a colonne calcolate
			//$model->IdObiettivo = $this->request->queryParams['IdObiettivo'];            
						
			$model->loadDefaultValues();
		}
		// Combo da aggiungere alla maschera
		$this->actionCombo();
		// 'id' e 'username' devono essere capitalizzati!!
		//$items = ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username');
		//$this->addCombo('users', $items);          

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	* Updates an existing zpermessi model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param int $idpermessi Id Doc Obiettivo
	* @return string|\yii\web\Response
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionUpdate($idpermessi)
	{
		$model = $this->findModel($idpermessi);

		if ($this->request->isPost) {
			// Scommentare se ci sono campi upload
			// $filesalvato = '';			
			//$model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			//if (isSet($model->imageFile) && !($filesalvato = $model->upload(900))) {
				// file is uploaded successfully
			//    return;
			//}
			if ($model->load($this->request->post())) {
				//if (isSet($model->imageFile))
				//    $model->PathDoc = $filesalvato; 
				if ($model->save()) {
					return $this->redirect(['view', 'idpermessi'=>$model->idpermessi]);
				}
			}
		}
		$this->actionCombo($model);
		// Combo da aggiungere alla maschera
		// 'id' e 'username' devono essere capitalizzati!!
		//$items = ArrayHelper::map(\common\models\User::find()->all(), 'id', 'username');
		//$this->addCombo('users', $items);          

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	* Deletes an existing zpermessi model.
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param int $idpermessi Id 
	* @return \yii\web\Response
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionDelete($idpermessi)
	{
		$model = $this->findModel($idpermessi);
        /* Controllo se ci sono relazioni collegate ed emetto errore
		if (count($model->notaopzione) > 0) {
			Yii::$app->session->setFlash('success', 'Ci sono delle note collegate. Impossibile cancellare.');            
    		return $this->redirect(['view','idpermessi'=>$model->idpermessi]);   
        }*/		
		if ( $model->delete()) {
			Yii::$app->session->setFlash('success', 'Cancellazione effettuata correttamente.Chiudere la maschera.');
			return $this->render('/generics/view',[]);   
			//return $this->redirect(['create']);
		}			
		return $this->redirect(['view','idpermessi'=>$model->idpermessi]);   
	}

	/**
	* Finds the zpermessi model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param int $idpermessi Id 
	* @return zpermessi the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
	protected function findModel($idpermessi)
	{
		if (($model = zpermessi::findOne($idpermessi)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
    
	/**
	* Load relazione
	*
	* @return string
	*/
	public function actionReloadrelazione($nomepdc, $nomerelaz, $idpermessi, $DaSingle = false)
	{
		$searchModel = new zpermessiSearch();
		
		if ( $DaSingle) {
			return $this->renderPartial('viewtabs', [
				'model' => $searchModel,
				'dataProvider' => $dataProvider,
				'$idpermessi' => $idpermessi,
				'nomepdc' => $nomepdc,
				'nomerelaz' => $nomerelaz,      
				'rigapos' => 1,
			]);            
		}

		return $this->renderPartial('lista', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'$idpermessi' => $idpermessi,
			'nomepdc' => $nomepdc,
			'nomerelaz' => $nomerelaz,      
			'rigapos' => 1,
		]);
	}
		
	public function actionUpload()
	{
		$model = new UploadForm();

		if (Yii::$app->request->isPost) {
			$model->imageFile = UploadedFile::getInstance($model, 'imageFile');
			if ($model->upload()) {
				// file is uploaded successfully
				return;
			}
		}

		return $this->render('upload', ['model' => $model]);
	}

	public function actionCombo($model = null, $nomecombo = null) {
		if (!empty($this->request->queryParams['NomeCombo']))
			$nomecombo = $this->request->queryParams['NomeCombo'];	
		if ( $nomecombo != null) {           
			$activequery = \common\models\busy\TipoOccupazione::find();
			if ($nomecombo === 'IdArg') {
				// Per i combo condizionati, $currvalue è il valore corrente del combo condizionato
				//$activequery->where('IdArg = '.$currvalue);
				// Per i combo dinamici
				$IdSocieta = $this->request->queryParams['IdSocieta'];
				$activequery->where('IdSocieta = '.$IdSocieta);				
			}
			//$items = ArrayHelper::map($activequery->all(),'TpOccup','DsOccup');
			$activequery = \common\models\soggetti\Soggetto::find()->select(['IdSoggetto as id','concat(Cognome, SPACE(1), Nome) as label']);
			$term = $this->request->queryParams['term'];
			if (!empty($term) ) {
				$activequery->andWhere('concat(Cognome, SPACE(1), Nome) like \'%'.$term.'%\'');
			}
			$items = $activequery->orderBy('Cognome, Nome')->asArray()->all();
			return $this->asJson($items);

			/*echo "-";
			foreach($items as $key => $val) {
				echo "<option value='".$key."'";
				if ($key == $currdestvalue) {
					echo " selected='yes'";
				}
				echo ">".$val."</option>";
			}*/
		} else {

			// Mettere al posto di id e username il codice e la descrizione da usare nel combo
			$items = ArrayHelper::map(\common\models\abilitazione\zgruppo::find()->all(), 'idgruppo', 'nomegruppo');
			$this->addCombo('zgruppo', $items);          		

			// Mettere al posto di id e username il codice e la descrizione da usare nel combo
			$items = ArrayHelper::map(\common\models\abilitazione\ztrans::find()->all(), 'idtrans', 'nometrans');
			$this->addCombo('ztrans', $items);          		

         
			/*if ($model != null && !empty($model->IdArg)) {
				$IdArg = $model->IdArg;
				$items = ArrayHelper::map(\common\models\busy\TipoOccupazione::find()->where('IdArg='.$IdArg)->all(), 'TpOccup', 'DsOccup');
				$this->addCombo('TipoOccupazione', $items);          		
			} else {
				$items = ArrayHelper::map(\common\models\busy\TipoOccupazione::find()->all(), 'TpOccup', 'DsOccup');
				$this->addCombo('TipoOccupazione', $items);          		
			}
			$items = ArrayHelper::map(\common\models\soggetti\Squadra::find()->joinWith('societa.progetto.campionato')->where(['campionato.idcampionato'=>$this->request->queryParams['IdCampionato']])->
				andFilterWhere(['not exists',(new Query())->select('idsquadra')->from('iscrizione')->where('iscrizione.IdCampionato=campionato.idcampionato and iscrizione.IdSquadra=squadra.idsquadra')])->all(), 'IdSquadra', 'NomeSquadra');
			$items = ArrayHelper::map(\common\models\soggetti\Soggetto::find()->select(['IdSoggetto','concat(Cognome, SPACE(1), Nome) as Nome'])->asArray()->all(),'IdSoggetto','Nome');								
			
			*/
		}
	}
	
	/* Caricamento di un combo a partire dalla variazione di un altro combo*/
	public function actionReloadcombo($nomecombo, $params = null, $currcombovalue = null) {
		if ( $nomecombo === 'TpOccup') {           
			$activequery = \common\models\busy\TipoOccupazione::find();
			if ( $params !== null) {
				$params = json_decode($params, true);
				foreach($params as $key => $value) {
					$activequery->where($key . ' = ' . $value);
				}
			}
			$items = ArrayHelper::map($activequery->all(),'TpOccup','DsOccup');
			echo "-";
			foreach($items as $key => $val) {
				echo "<option value='".$key."'";
				if ($key == $currcombovalue) {
					echo " selected='yes'";
				}
				echo ">".$val."</option>";
			}
		}
	}	
}
