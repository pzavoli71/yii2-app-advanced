<?php

namespace common\models\abilitazione;

use Yii;

/**
 * This is the model class for table "zpermessi".
 *
 * @property int $idpermessi
 * @property int|null $idtrans
 * @property int|null $idgruppo
 * @property string|null $permesso
 * @property string|null $ultagg
 * @property string|null $utente
 *
 * @property Zgruppo $idgruppo0
 * @property Ztrans $idtrans0
 */
class zpermessi extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zpermessi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtrans', 'idgruppo'], 'integer'],
            [['ultagg'], 'safe'],
            [['permesso', 'utente'], 'string', 'max' => 45],
            [['idgruppo'], 'exist', 'skipOnError' => true, 'targetClass' => zgruppo::class, 'targetAttribute' => ['idgruppo' => 'idgruppo']],
            [['idtrans'], 'exist', 'skipOnError' => true, 'targetClass' => ztrans::class, 'targetAttribute' => ['idtrans' => 'idtrans']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpermessi' => 'Idpermessi',
            'idtrans' => 'Idtrans',
            'idgruppo' => 'Idgruppo',
            'permesso' => 'Permesso',
            'ultagg' => 'Ultagg',
            'utente' => 'Utente',
        ];
    }

    /**
     * Gets query for [[Idgruppo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZGruppo()
    {
        return $this->hasOne(zgruppo::class, ['idgruppo' => 'idgruppo']);
    }

    /**
     * Gets query for [[Idtrans0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZtrans()
    {
        return $this->hasOne(ztrans::class, ['idtrans' => 'idtrans']);
    }
}
