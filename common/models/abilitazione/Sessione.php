<?php
namespace common\models\abilitazione;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "Sessione".
 *

 * @property string|null	$id,

 * @property int	$expire,

 * @property string|null	$data,

 * @property int	$user_id,

 * @property string|null	$browser_platform,

 * @property string|DateTime|null	$last_write,

 * @property string|null	$ipaddress,

 */

class Sessione extends \common\models\BaseModel
{
	public $bool_columns = [];
	public $number_columns = ['expire','user_id'];
	public $datetime_columns = ['last_write'];
	public $auto_increment_cols = [ ];
	
    public static function tableName()
    {
        return 'session';
    }
	
    public function rules()
    {
        return [
			[['expire','user_id'], 'integer'],
			[['id'],'string','max' => 40],
			[['browser_platform'],'string','max' => 200],
			[['ipaddress'],'string','max' => 40],
			[['last_write'], 'safe'],
			//['DtFineTess', 'compare', 'compareAttribute' => 'DtInizioTess', 'operator' => '>=','enableClientValidation'=>false],
			//[['CdRuolo','IdSocieta','IdSoggetto','DtInizioTess'], 'required'],

        ];
    }	
	
    public function attributeLabels()
    {
        return [
	
			'id' => 'id',
	
			'expire' => 'expire',
	
			'data' => 'data',
	
			'user_id' => 'user_id',
	
			'browser_platform' => 'browser_platform',
	
			'last_write' => 'last_write',
	
			'ipaddress' => 'ipaddress',
	
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
    }	




    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ( !$insert) 
            return true;
        
        // Aggiungo anche il gruppo per questo utente
        //$zutgr = new zutgr();
        //$zutgr->id = $this->id;
        //$zutgr->idgruppo = 1;
        
        //if ( $zutgr->save())
        //    return true;
        //return false;
		return true;
    }

}

