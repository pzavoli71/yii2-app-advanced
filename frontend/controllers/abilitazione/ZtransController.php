<?php

namespace frontend\controllers\abilitazione;

use common\models\abilitazione\ztrans;
use common\models\abilitazione\ztransSearch;
use yii\web\Controller;
use frontend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * ZtransController implements the CRUD actions for ztrans model.
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/ztrans/create' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/ztrans/update' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/ztrans/delete' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/ztrans/view' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	INSERT INTO zTrans (NomeTrans,ultagg,utente) VALUES ('abilitazione/ztrans/lista' ,CURRENT_TIMESTAMP,'appl');
	SET @id = (SELECT LAST_INSERT_ID());
	INSERT INTO zPermessi(IdTrans,IdGruppo, Permesso, ultagg, utente) VALUES (@id, 1,'LAGMIRVC',CURRENT_TIMESTAMP,'appl');
	
	
 */
class ZtransController extends BaseController
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
        $this->layout = "main";
		$searchModel = new ztransSearch();
		$dataProvider = $searchModel->search($this->request->queryParams);

		return $this->render('lista', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	* Displays a single ztrans model.
	* @param int $idtrans Id Doc Obiettivo
	* @return string
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionView($idtrans)
	{
		return $this->render('view', [
			'model' => $this->findModel($idtrans),
		]);
	}

	/**
	* Creates a new ztrans model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return string|\yii\web\Response
	*/
	public function actionCreate()
	{
		$model = new ztrans();

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
					return $this->redirect(['view', 'idtrans'=>$model->idtrans]);
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
	* Updates an existing ztrans model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param int $idtrans Id Doc Obiettivo
	* @return string|\yii\web\Response
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionUpdate($idtrans)
	{
		$model = $this->findModel($idtrans);

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
					return $this->redirect(['view', 'idtrans'=>$model->idtrans]);
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
	* Deletes an existing ztrans model.
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param int $idtrans Id 
	* @return \yii\web\Response
	* @throws NotFoundHttpException if the model cannot be found
	*/
	public function actionDelete($idtrans)
	{
		$model = $this->findModel($idtrans);
		if ( $model->delete()) {
			Yii::$app->session->setFlash('success', 'Cancellazione effettuata correttamente.Chiudere la maschera.');
			return $this->redirect(['create']);
		}			
		return $this->redirect(['view','idtrans'=>$model->idtrans]);   
	}

	/**
	* Finds the ztrans model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param int $idtrans Id 
	* @return ztrans the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
	protected function findModel($idtrans)
	{
		if (($model = ztrans::findOne($idtrans)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
    
	/**
	* Load relazione
	*
	* @return string
	*/
	public function actionReloadrelazione($nomepdc, $nomerelaz, $idtrans, $DaSingle = false)
	{
		$searchModel = new ztransSearch();
if ($nomerelaz == "ztrans_zpermessi" ) 
				$dataProvider = $searchModel->searchZpermessi($this->request->queryParams, $idtrans);

		else {
			return;
		}
		
		if ( $DaSingle) {
			return $this->renderPartial('viewtabs', [
				'model' => $searchModel,
				'dataProvider' => $dataProvider,
				'$idtrans' => $idtrans,
				'nomepdc' => $nomepdc,
				'nomerelaz' => $nomerelaz,      
				'rigapos' => 1,
			]);            
		}

		return $this->renderPartial('lista', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'$idtrans' => $idtrans,
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
