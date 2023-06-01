<?php
namespace common\models\abilitazione;

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "Profilo".
 *

 * @property int	$id,

 * @property int	$IdProfilo,

 * @property string|null	$Cognome,

 * @property string|null	$Nome,

 * @property string|DateTime|null	$Nascita,

 * @property int	$AnnoNascita,

 */

class Profilo extends \common\models\BaseModel
{
	public $bool_columns = [];
	public $number_columns = ['id','IdProfilo','AnnoNascita'];
	public $date_columns = ['Nascita'];
	public $auto_increment_cols = [ ];
	
	public static function tableName()
    {
        return 'Profilo';
    }
	
    public function rules()
    {
        return [
			[['id','IdProfilo','AnnoNascita'], 'integer'],
			[[], 'boolean','trueValue'=>'-1'],
			[['Cognome'],'string','max' => 100],
			[['Nome'],'string','max' => 100],
			[['Nascita'], 'safe'],
        ];
    }	
	
    public function attributeLabels()
    {
        return [
	
			'id' => 'id',
	
			'IdProfilo' => 'Id Profilo',
	
			'Cognome' => 'Cognome',
	
			'Nome' => 'Nome',
	
			'Nascita' => 'Nascita',
	
			'AnnoNascita' => 'Anno Nascita',
	
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
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

