<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace common\models;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\imagine\Image;  
use Imagine\Image\Box;  

/**
 * Description of BaseModel
 *
 * @author Paride
 */
class BaseModel extends \yii\db\ActiveRecord {
    
    public $number_columns = [];
    public $decimal_columns = [];
    public $date_columns = [];
    public $datetime_columns = [];
    public $bool_columns = [];
    public $auto_increment_cols = [];
    
    // Usato per il fileupload
    public $imageFile;
    
      public function behaviors()
          {
            $params = [
                    'timestamp' => [
                         'class' => 'yii\behaviors\TimestampBehavior',
                         'attributes' => [
                             ActiveRecord::EVENT_BEFORE_INSERT => ['ultagg'],
                             ActiveRecord::EVENT_BEFORE_UPDATE => ['ultagg'],
                         ],
                         'value' => new Expression('NOW()'),
                     ],                  
                  'blameable' => [
                      'class' => BlameableBehavior::className(),
                      'createdByAttribute' => 'utente',
                      'updatedByAttribute' => 'utente',
                      'value' => function ($event) {
                            if(isset(\Yii::$app->user->identity->username)) {                                
                                return \Yii::$app->user->identity->username;
                            } else {
                                return 'batch';
                            }                      
                      }
                  ],
              ];            
            return $params;
          }
          
        public function load($data, $formName = null)
        {
            $scope = $formName === null ? $this->formName() : $formName;
            if ($scope === '' && !empty($data)) {
                $this->setAttributes($data);
                if ( $this->hasErrors())
                    return false;
                return true;
            } elseif (isset($data[$scope])) {
                $this->setAttributes($data[$scope]);
                if ( $this->hasErrors())
                    return false;
                return true;
            }
            return false;
        }
        
          public static function convertiNumero($numero, $bDecimal = false) {
            if (!$bDecimal) {
                $conv = str_replace('.', '', $numero);
                $conv = str_replace(',', '.', $conv);  
                if (str_contains($conv, '.'))
                        $conv = (double) $conv;
                else
                    $conv = (int) $conv;
            } else {
                if (str_contains($numero, ',')) {
                    $conv = str_replace('.', '', $numero);
                    $conv = str_replace(',', '.', $conv);
                } else {
                    $conv = $numero;
                }
            }
            return $conv;              
          }
          
          public static function convertiBoolInIntero($valore) {
                if ($valore == null)  {
                    return null;
                }
                if ( $valore instanceof string) {
                    if ( strlen($valore) == 0) {
                        return null;
                    }
                    if ( $valore == 'true') {
                        return -1;
                    } else {
                        return 0;
                    }
                }
                if ( $valore) {
                    return -1;
                }
                return 0;              
          }
          
          public static function convertiStringToDateTime($valore) {
              if ( $valore == null || $valore == '')
                  return null;
              $format = \common\config\db\mysql\ColumnSchema::$saveDateTimeFormat;
              $conv = \DateTime::createFromFormat($format, $valore);
              if (!$conv) 
                  throw new \UnexpectedValueException("Could not parse the date: " . $valore);
              return $conv;
          }
          
          public static function convertiStringToDate($valore) {
              if ( $valore == null || $valore == '')
                  return null;
              $format = \common\config\db\mysql\ColumnSchema::$saveDateFormat;
              $conv = \DateTime::createFromFormat($format, $valore);
              if (!$conv) 
                  throw new \UnexpectedValueException("Could not parse the date: " . $valore);
              return $conv;
          }                    
          
          public function setAttributes($values, $safeOnly = true) {
              parent::setAttributes($values, $safeOnly);
            foreach ($this->number_columns as $nomecol) {
                if ( $this->attributes[$nomecol] != null) {
                    $val = $this->convertiNumero($this->attributes[$nomecol]);
                    $this->setAttribute($nomecol, $val);
                }
            }
            
            foreach ($this->bool_columns as $nomecol) {
                $val = $this->convertiBoolInIntero($this->attributes[$nomecol]);
                $this->setAttribute($nomecol, $val);
            }
            
            foreach ($this->datetime_columns as $nomecol) {
                $val = $this->convertiStringToDateTime($this->attributes[$nomecol]);
                $this->setAttribute($nomecol, $val);
            }
            
            foreach ($this->date_columns as $nomecol) {
                $val = $this->convertiStringToDate($this->attributes[$nomecol]);
                $this->setAttribute($nomecol, $val);
            }
            foreach ($this->decimal_columns as $nomecol) {
                if ( $this->attributes[$nomecol] != null) {
                    $val = $this->convertiNumero($this->attributes[$nomecol], true);
                    $this->setAttribute($nomecol, $val);
                }
            }            
          }
          
          public function beforeSave($insert) {
            if ( !parent::beforeSave($insert)) {
                  return false;
            }
            if ( $insert && isset($this->auto_increment_cols)) {
                foreach ($this->auto_increment_cols as $nomecol) {
                    if ( isset($this[$nomecol]) && $this[$nomecol] > 0)
                        return true;
                    $max = $this::find()->max($nomecol);
                    if ($max == null )
                        $max = 0;
                    if ( $max < 0) 
                        throw new \UnexpectedValueException("Impossibile caricare il valore di " . $nomecol);
                    $max++;
                    $this[$nomecol] = $max;
                }
            }
            return true;
          }

            public function afterFind() { 
                parent::afterFind(); // Converti da 11.00 (DB) a 11,00 (form) 
                if ( isset($this->decimal_columns) ) {
                    foreach ($this->decimal_columns as $nomecol) {
                        $this[$nomecol] = \Yii::$app->formatter->asDecimal($this[$nomecol], 2);     
                    }               
                }
            }   
    
          protected function validaDaStringaAData($nomeparametro, $valore) {
            if ( $valore === null || strlen($valore) == 0)
                return true;
            $parsed = \DateTime::createFromFormat('Y-m-d H:i:s', $valore);        
            if ( $parsed)
                return true;
            $parsed = \DateTime::createFromFormat('dmY', $valore);        
            if ( !$parsed) {
                $this->addError($nomeparametro, 'il campo ' . $nomeparametro . ' non è valido');     
                return false;
            }
            return true;
          }
          
          protected function convertiDaStringaAData($nomeparametro, $valore) {
            if ( $valore === null || strlen($valore) == 0)
                return true;
            $parsed = \DateTime::createFromFormat('d/m/Y H:i', $valore);        
            if ( $parsed)
                return true;
            $parsed = \DateTime::createFromFormat('dmY', $valore);        
            if ( !$parsed) {
                $this->addError($nomeparametro, 'il campo ' . $nomeparametro . ' non è valido');     
                return false;
            }
            $formatted = \Yii::$app->formatter->asDate($parsed, 'php:d/m/Y');
            $this[$nomeparametro] = $formatted;
          }
          
    public function upload(string $relpath = '', int $maximgwidth = 900)
    {
        if (!isset($this->imageFile)) {
            throw new \UnexpectedValueException("Errore in salvataggio del file. File non trovato. ");            
        }
        if ( isset($relpath)) {
            if (!file_exists('uploads/' . $relpath)) {
                if ( !mkdir('uploads/' . $relpath, 0777, true))
                    throw new \UnexpectedValueException("Errore in creazione directory " . $relpath);            
            }          
            if (!str_ends_with($relpath, "/"))
                    $relpath = $relpath . '/';
        }
        $filesalvato = $relpath . $this->imageFile->baseName . '.' . $this->imageFile->extension;
        $this->imageFile->saveAs('uploads/' . $filesalvato);
        if ( $this->imageFile->extension == 'jpg' ||$this->imageFile->extension == 'jpeg' || 
                $this->imageFile->extension == 'tiff' || $this->imageFile->extension == 'png') {
            // Ridimensiono l'immagine dopo averla salvata
            $filename = 'uploads/' . $filesalvato;
            list($width, $height, $type, $attr) = getimagesize($filename); // $sizes = 
            $exif = exif_read_data($filename);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        //$image = imagerotate($image,90,0);
                        $tmp = $width; $width = $height; $height = $tmp;
                        break;
                    case 3:
                        //$image = imagerotate($image,180,0);
                        break;
                    case 6:
                        $tmp = $width; $width = $height; $height = $tmp;
                        break;
                }
            }            
            
            if ( $width > $maximgwidth) { // $sizes[0]
                $height = round($height * $maximgwidth / $width); 
                $width = $maximgwidth;            
                //$height = round($sizes[1]*$width/$sizes[0]); 
                $savepath = 'uploads/' . $relpath . 'thumbnail-' . $width . 'x' . $height . '_' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
                $image = Image::getImagine()->open($filename);
                $image = Image::autorotate($image);
                $image->thumbnail(new Box($width, $height))->save($savepath , ['quality' => 90]);
                //Image::getImagine()->open($filename)->(thumbnail(new Box($width, $height))->save($savepath , ['quality' => 90]);
                unlink($filename);
                $filesalvato = $relpath . 'thumbnail-' . $width . 'x' . $height . '_' . $this->imageFile->baseName. '.' . $this->imageFile->extension;
            }
        }
        
        //$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
        return $filesalvato;
    }              
}
