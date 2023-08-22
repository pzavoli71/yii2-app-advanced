<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\UserException;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Description of BaseController
 *
 * @author Paride
 */
class BaseController  extends Controller{
    public static $MASK_DECIMAL_PARAMS_WIDGET = [
                    'clientOptions' => [
                    'alias' => 'decimal',
                    'digits' => 2,
                    'digitsOptional' => false,
                    'radixPoint' => ',',
                    'groupSeparator' => '.',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                    ]];
    public static $MASK_INTEGER_PARAMS_WIDGET = [
                    'clientOptions' => [
                    'alias' => 'decimal',
                    'digits' => 0,
                    'digitsOptional' => false,
                    'radixPoint' => ',',
                    'groupSeparator' => '.',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                    ]];
    
    // Qui vengono mantenuti i valori generati dal combo in maschera
    public $DatiCombo = [];
    
    // Qui vengono mantenuti i parametri inviati dall'utente
    public $ParametriMask = [];
    
    protected function addCombo($name, $items) {
        $this->DatiCombo[$name] = $items;
    }
    protected function addParametroMask($name, $value) {
        $this->ParametriMask[$name] = $value;
    }
    
    // In fase di rendering invio il parametro DatiCombo alle views
    public function render($view, $params = [])
    {
        $combo = [];        
        if ( !empty($this->DatiCombo)) {
            foreach ($this->DatiCombo as $key => $value) {
                $combo[$key] = $value;
            }
        }
        $params['combo'] = $combo;
        
        // Aggiungo anche i parametri che mi sono stati inviati dalla maschera
        $maskparam = [];        
        if ( !empty($this->ParametriMask)) {
            foreach ($this->ParametriMask as $key => $value) {
                $maskparam[$key] = $value;
            }
        }
        $params['parametri'] = $maskparam;
        
        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContent($content);
    }
    
    public function upload($imageFile)
    {
        $imageFile->saveAs('uploads/' . $imageFile->baseName . '.' . $imageFile->extension);
        return true;
    }   
    
    // Controllo se c'è una sessione attiva, altrimenti errore
    public function beforeAction($action): bool {
        if (!parent::beforeAction($action)) { return false; }
        $this->riempiParametriMask();
        if ( !isset(\Yii::$app->user) || !(isset(\Yii::$app->user->identity)) || !isset(\Yii::$app->user->identity->profilo->IdProfilo)) {
            //$this->layout = 'mainform';
            throw new UserException("Non esiste una sessione per l'utente. Eseguire il login.");
        }
        return true;
    }
    
    private function riempiParametriMask() {
        foreach ($this->request->queryParams as $key => $value) {
            $this->addParametroMask($key, $value);
        }        
    }
    
    /**
     * 
     * @param type $action Nome dell'azione del tipo controller/action
     * @param type $permesso AIRVLC
     */
    public static function linkwin($text, $action, $params, $linktitle,  $callback, $windowparams=[], $buttonclass = 'btn btn-primary') {
        $trovato = false;
        if ( !empty($windowparams['freetoall'])) {
            $trovato = true;
        } else {
            if ( Yii::$app->session != null ) {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi != null) {
                    //foreach ($gruppi as $key => $value) {
                    foreach ($gruppi as $value) {
                        if ( $value['nometrans'] == $action) {
                            $trovato = true;
                            break;
                        }
                    }
                }
            }
        }
        $url = '';
        $fa = '';
        $testolink = $text;        
        if (!str_starts_with($testolink, '<span') && (str_contains($text, '|fa-') || str_contains($text, ' fa-'))) {
            if (str_contains($text, '|fa-') )
                $pos = strpos($text, '|fa-');
            else
                $pos = strpos($text, '|fa');
            if (str_contains($text, '|far') || str_contains($text, '|fas'))
                $fa = substr($text,$pos + 1);
            else
                $fa = 'fas ' . substr($text,$pos + 1);
            $text = substr($text,0,$pos);
            if (strlen($text) > 0)
                $text = '&#xA0;' . $text;
            $testolink = ($fa != ''?"<span class='" . $fa . "'></span>":"") . $text;
        }
        if ( $trovato) {
            $params = array_merge([$action],$params);
            $p = '{';
            if ( !empty($windowparams['windowwidth'])) {
                $p .= "width:" . $windowparams['windowwidth'] . ",";
            }
            $p .= '}';
            $titoloform = "Inserisci i parametri";
            if ( !empty($windowparams['windowtitle'])) {
                $titoloform = $windowparams['windowtitle'];
                $titoloform = str_replace("'","\'",$titoloform);
            }
            //$url = Html::a(($fa != ''?"<span class='" . $fa . "'></span>":"") . $text,$params, ['title'=>$linktitle,'class'=>$buttonclass, 'onclick'=>"return AppGlob.apriForm(this,'', '" . $callback ."'," . $p . ",'" . $titoloform . "')"]);
            $url = Html::a($testolink,$params, ['title'=>$linktitle,'class'=>$buttonclass, 'onclick'=>"return AppGlob.apriForm(this,'', '" . $callback ."'," . $p . ",'" . $titoloform . "')"]);
        } else {
            $url = ''; //Html::a($text,null,['title'=>$title]);
        }
        return $url;
    }

    /**
     * 
     * @param type $action Nome dell'azione del tipo controller/action
     * @param type $permesso AIRVLC
     */
    public static function link($text, $action, $params, $linktitle,  $callback, $windowparams=[], $buttonclass = 'btn btn-primary') {
        $trovato = false;
        if ( !empty($windowparams['freetoall'])) {
            $trovato = true;
        } else {
            if ( Yii::$app->session != null ) {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi != null) {
                    //foreach ($gruppi as $key => $value) {
                    foreach ($gruppi as $value) {
                        if ( $value['nometrans'] == $action) {
                            $trovato = true;
                            break;
                        }
                    }
                }
            }
        }
        $url = '';
        $fa = '';
        if (str_contains($text, '|fa-') || str_contains($text, ' fa-')) {
            if (str_contains($text, '|fa-') )
                $pos = strpos($text, '|fa-');
            else
                $pos = strpos($text, '|fa');
            if (str_contains($text, '|far') || str_contains($text, '|fas'))
                $fa = substr($text,$pos + 1);
            else
                $fa = 'fas ' . substr($text,$pos + 1);
            $text = substr($text,0,$pos);
            if (strlen($text) > 0)
                $text = '&#xA0;' . $text;
        }
        if ( $trovato) {
            $params = array_merge([$action],$params);
            $p = '{';
            if ( !empty($windowparams['windowwidth'])) {
                $p .= "width:" . $windowparams['windowwidth'] . ",";
            }
            $p .= '}';
            $titoloform = "Inserisci i parametri";
            if ( !empty($windowparams['windowtitle'])) {
                $titoloform = $windowparams['windowtitle'];
                $titoloform = str_replace("'","\'",$titoloform);
            }
            $url = Html::a(($fa != ''?"<span class='" . $fa . "'></span>":"") . $text,$params, ['title'=>$linktitle,'class'=>$buttonclass]);
        } else {
            $url = ''; //Html::a($text,null,['title'=>$title]);
        }
        return $url;
    }
    
    public static function linkcomandocondialog($text, $action, $chiave, $params, $title, $funrichiestacomando ='richiestaComandoConDialog', $callback = 'comandoTerminato',$buttonclass = 'btn btn-primary') {
        return BaseController::linkcomando($text, $action, $chiave, $params, $title, $funrichiestacomando, $callback, $buttonclass, 'eseguiComandoConDialog');
    }
    /**
     * 
     * @param type $action Nome dell'azione del tipo controller/action
     * @param type $permesso AIRVLC
     */
    public static function linkcomando($text, $action, $chiave, $params, $title, $funrichiestacomando ='richiestaComando', $callback = 'comandoTerminato',$buttonclass = 'btn btn-primary', $tipoesegui = 'eseguiComando') {
        $trovato = false;
        if ( Yii::$app->session != null ) {
            $gruppi = Yii::$app->session['gruppi'];
            if ( $gruppi != null) {
                //foreach ($gruppi as $key => $value) {
                foreach ($gruppi as $value) {
                    if ( $value['nometrans'] == $action) {
                        $trovato = true;
                        break;
                    }
                }
            }
        }
        $url = '';
        $fa = '';
        if (str_contains($text, '|fa-')) {
            $pos = strpos($text, '|fa-');
            $fa = substr($text,$pos + 1);
            $text = substr($text,0,$pos);
        }
        if ( $trovato) {
            $id = str_replace('/', '_', $action) . '_' . $chiave; //str_replace('/', '_', $action);
            $params = array_merge([$action],$params);
            //$url = 'index.php';
            $url = Url::toRoute($action);
            $url = Html::button(($fa != ''?"<span class='fas " . $fa . "'></span>&#xA0;":"") . $text, 
                    ['title'=>$title,'class'=>$buttonclass, 'id'=>$id, 'onclick'=>'AppGlob.' . $tipoesegui . '("' . $url . '","' . $action . '","' . $chiave . '",[],'
                        . $funrichiestacomando . ',' . $callback . ')']);
        } else {
            $url = ''; //Html::a($text,null,['title'=>$title]);
        }
        return $url;
    }
    
    /**
     * 
     * @param type $action Nome dell'azione del tipo controller/action     
     */
    public static function menu($menuitems) {
        if ( Yii::$app->user->isGuest)
            return null;
        $ret = [];
        $i = 0;
        foreach ($menuitems as $item) {
            $trovato = false;
            // Elaboro eventuali submenu
            if ( !isset($item['url']) || isset($item['forall']))
                $trovato = true;
            else {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi == null) {
                    $gruppi = \Yii::$app->user->identity->getzGruppi();
                    Yii::$app->session['gruppi'] = $gruppi;
                }
                //$gruppi = Yii::$app->user->identity->gruppi;
                if ( $gruppi != null) {
                    foreach ($gruppi as $value) {
                        $val = $item['url'][0];
                        if ( $value['nometrans'] == $val) {
                            $trovato = true;
                            break;
                        }                            
                    }
                }
            }
            if ( $trovato ) {
                $r = [];
                $r['label'] = $item['label'];
                if ( isset($item['url'])) {
                    $r['url'] = $item['url'];
                }
                if ( isset($item['items'])) {
                    $retr = \frontend\controllers\BaseController::menu($item['items']);
                    if ( $retr != null ) {
                        $r['items'] = $retr;
                    }
                }            
                $ret[] = $r;
                $i++;
            }
        }
        return $ret;    
    }    
    
    public static function menuPanino($menuitems) {
        $menu = '<div class="td contenitorebottoni">';
        $menu .= '<div class="panino fas fa-ellipsis-h" onclick="$(this).parents(\'.tabledisplay\').find(\'.contenitorebottoni\').css(\'z-index\',\'0\');$(this).parent().css(\'z-index\',\'1000\').find(\'.vocimenu\').toggle(100);"></div>';  
        $menu .= '<div class="vocimenu">';
        foreach ($menuitems as $item) {
            $label = $item['label'];
            $action = $item['action'];
            $params = $item['params'];
            
            $linktype = '';
            if ( isset($item['linktype']))
                $linktype = $item['linktype'];
            
            $linkclass = $params['class'];            
            if ( $linkclass === null)
                $linkclass = 'btn_link';                        
            
            $linktitle = $params['title'];
            
            $closecallback = '';
            if ( isset($params['callback']))
                $closecallback = $params['callback'];               
            if ( $closecallback == null || $closecallback === '')
                $closecallback = 'document.location.reload(false)';

            $windowtitle = 'Inserisci i dati';
            if ( isset($params['windowtitle']))
                $windowtitle = $params['windowtitle'];   
            
            $testo = $label; $fa = '';
            if (str_contains($label, "|")) {
                $pos = strpos($label,"|");
                $testo = substr($label,0, $pos);
                if ( $linktitle == null || $linktitle === '')
                    $linktitle = $testo;
                $fa = substr($label, $pos + 1);
            }
            if ( $fa !== '') { // && $linktype == null || $linktype != 'linkwin') {
                $testo = '<span class="testovocemenu">' . $testo . '</span>' . Html::tag('i',null,['class'=>$fa]);
            }
            if ( $linktype != null && $linktype === 'linkwin') {
                $a = $action[0];
                $idpars = array_keys($action)[1];
                $pars = $action[array_keys($action)[1]];                
                $menu .= BaseController::linkwin($testo, $action[0], [$idpars => $pars], $linktitle,$closecallback,['freetoall'=>true,'windowtitle'=>$windowtitle,'windowwidth'=>'700'],$linkclass);
            }
            else
                $menu .= Html::a($testo, $action, $params);            
        }        
        $menu .= '</div>';
        $menu .= '</div>';
        return $menu;
    }
    
    public static function getToday() {
        return date('Y-m-d H:i:s');
    }
}
