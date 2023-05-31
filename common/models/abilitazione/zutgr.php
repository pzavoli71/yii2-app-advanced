<?php

namespace common\models\abilitazione;
use \common\models\User;

use Yii;

/**
 * This is the model class for table "zutgr".
 *
 * @property int $idutgr
 * @property int|null $id
 * @property int|null $idgruppo
 * @property string|null $ultagg
 * @property string|null $utente
 *
 * @property User $id0
 * @property Zgruppo $idgruppo0
 */
class zutgr extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zutgr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idgruppo'], 'integer'],
            [['ultagg'], 'safe'],
            [['utente'], 'string', 'max' => 45],
            [['idgruppo'], 'exist', 'skipOnError' => true, 'targetClass' => zgruppo::class, 'targetAttribute' => ['idgruppo' => 'idgruppo']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idutgr' => 'Idutgr',
            'id' => 'ID',
            'idgruppo' => 'Idgruppo',
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
    }

    /**
     * Gets query for [[Id0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[Idgruppo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZgruppo()
    {
        return $this->hasOne(zgruppo::class, ['idgruppo' => 'idgruppo']);
    }
}
