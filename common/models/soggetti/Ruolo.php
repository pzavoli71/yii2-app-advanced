<?php
namespace common\models\soggetti;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "Ruolo".
 *

 * @property int	$CdRuolo,

 * @property string|null	$DsRuolo,

 */

class Ruolo extends \common\models\BaseModel
{
	public $bool_columns = [];
	public $number_columns = ['CdRuolo'];
	public $datetime_columns = [];
	public $auto_increment_cols = ['CdRuolo' ];
	
	public static function tableName()
    {
        return 'Ruolo';
    }
	
    public function rules()
    {
        return [
			[['CdRuolo'], 'integer'],
			[[], 'boolean','trueValue'=>'-1'],
			[['DsRuolo'],'string','max' => 100],
			,
			[[], 'safe'],
        ];
    }	
	
    public function attributeLabels()
    {
        return [
	
			'CdRuolo' => 'Cd Ruolo',
	
			'DsRuolo' => 'Ds Ruolo',
	
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
    }	


    /**
     * Gets query for [[Soggetto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoggetto()
    {
        return $this->hasMany(\common\models\soggetti\Soggetto::class,  ['' => '']);
    }    


}

