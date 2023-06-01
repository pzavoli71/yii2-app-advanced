<?php
namespace common\models\soggetti;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "Soggetto".
 *

 * @property int	$IdSocieta,

 * @property int	$CdRuolo,

 * @property int	$IdSoggetto,

 * @property string|null	$Cognome,

 * @property string|null	$Nome,

 * @property string|null	$DescSoggetto,

 * @property boolean	$bArbitro,

 */

class Soggetto extends \common\models\BaseModel
{
	public $bool_columns = ['bArbitro'];
	public $number_columns = ['IdSocieta','CdRuolo','IdSoggetto'];
	public $datetime_columns = [];
	public $auto_increment_cols = [ ];
	
	public static function tableName()
    {
        return 'Soggetto';
    }
	
    public function rules()
    {
        return [
			[['IdSocieta','CdRuolo','IdSoggetto'], 'integer'],
			//[['bArbitro'], 'boolean','trueValue'=>'-1'],
			[['Cognome'],'string','max' => 100],
			[['Nome'],'string','max' => 100],
			[['DescSoggetto'],'string','max' => 5000],
			//[[], 'safe'],
        ];
    }	
	
    public function attributeLabels()
    {
        return [
	
			'IdSocieta' => 'Id Societa',
	
			'CdRuolo' => 'Cd Ruolo',
	
			'IdSoggetto' => 'Id Soggetto',
	
			'Cognome' => 'Cognome',
	
			'Nome' => 'Nome',
	
			'DescSoggetto' => 'Desc Soggetto',
	
			//'bArbitro' => 'bArbitro',
	
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
    }	


    /**
     * Gets query for [[SoggEvento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoggevento()
    {
        return $this->hasMany(\common\models\soggetti\SoggEvento::class,  ['IdSoggetto' => 'IdSoggetto']);
    }    

    /**
     * Gets query for [[Partecipante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartecipante()
    {
        return $this->hasMany(\common\models\soggetti\Partecipante::class,  ['IdSoggetto' => 'IdSoggetto']);
    }    

    /**
     * Gets query for [[SoggSquadra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoggsquadra()
    {
        return $this->hasMany(\common\models\soggetti\SoggSquadra::class,  ['IdSoggetto' => 'IdSoggetto']);
    }    

    /**
     * Gets query for [[Ruolo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuolo()
    {
        return $this->hasOne(\common\models\soggetti\Ruolo::class,  ['CdRuolo' => 'CdRuolo',]);
    }    

    /**
     * Gets query for [[Societa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocieta()
    {
        return $this->hasOne(\common\models\soggetti\Societa::class,  ['IdSocieta' => 'IdSocieta']);
    }    


}

