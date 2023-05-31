<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\base\Event;
use common\models\soggetti\Soggetto;
use common\models\abilitazione\zutgr;
use common\models\abilitazione\ztrans;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /* Mantiene in memoria l'array dei permessi di questo user. Caricato durante il login*/
    public $gruppi;
    
    public function init() {
        parent::init();
        Event::on(User::class, yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
            Yii::debug(get_class($event->sender) . ' is logged in');
            $this->gruppi = $this->getZgruppi();
            if ( \Yii::$app->session != null) {
                \Yii::$app->session['gruppi'] = $gruppi;
            }
        });        
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Gets query for [[Id0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoggetto()
    {
        return $this->hasOne(Soggetto::class, ['id' => 'id']);
    }

    /**
     * Gets query for zgruppo.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZgruppi()
    {
        if ( empty($this->gruppi) || $this->gruppi === null) {
            $query = ztrans::find()->select('ztrans.*') // era $this->gruppi = 
                    ->innerJoin('zpermessi', '`zpermessi`.`idtrans` = `ztrans`.`idtrans`')
                    ->innerJoin('zutgr', '`zutgr`.`idgruppo` = `zpermessi`.`idgruppo`')
                    ->where(['zutgr.id' => $this->id]);
                    //->sql;
                    //->all();
                    //$this->hasMany(zgruppo::class, ['id' => 'id'])->with(['zutgr','zgruppo','zpermessi','ztrans']);
            
            $command = $query->createCommand();
            $result = $command->queryAll();
            $this->gruppi = $result;
        }
        return $this->gruppi;
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ( !$insert) 
            return true;
        
        // Aggiungo anche la tabella Soggetto
        $soggetto = new Soggetto();
        $soggetto->id = $this->id;
        $soggetto->NomeSoggetto = $this->username;
        $soggetto->EmailSogg = $this->email;
        $soggetto->utente = $this->username;

        // Aggiungo anche il gruppo per questo utente
        $zutgr = new zutgr();
        $zutgr->id = $this->id;
        $zutgr->idgruppo = 1;
        
        if ( $zutgr->save())
            return true;
        return false;
    }
    
}
