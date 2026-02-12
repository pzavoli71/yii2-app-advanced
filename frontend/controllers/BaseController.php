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
    public $DatiCombo = [];
    
    protected function addCombo($name, $items) {
        $this->DatiCombo[$name] = $items;
    }
    
    // In fase di rendering invio il parametro DatiCombo alle views
    public function render($view, $params = [])
    {
        $combo = [];
        //$pars['model'] = $params['model'];     
        
        if ( !empty($this->DatiCombo)) {
            foreach ($this->DatiCombo as $key => $value) {
                $combo[$key] = $value;
            }
        }
        //$pars['combo'] = $combo; // $this->DatiCombo;
        $params['combo'] = $combo;
        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContent($content);
    }
    
    public function upload($imageFile, $path = "uploads/")
    {
        if (!str_ends_with($path, "/")) {
            $path .= "/";
        }
        $imageFile->saveAs($path . $imageFile->baseName . '.' . $imageFile->extension);
        return true;
    }   
    
    // Controllo se c'è una sessione attiva, altrimenti errore
    public function beforeAction($action): bool {
        if (!parent::beforeAction($action)) { return false; }
        if ($this->devoControllarePermesso($action)) {
            if ( Yii::$app->session == null || Yii::$app->user->isGuest) {
                throw new UserException("Non esiste una sessione per l'utente. Eseguire il login.");
            }
            $gruppi = Yii::$app->session['gruppi'];
            if ( $gruppi == null ) {
                throw new UserException("Non esiste una sessione per l'utente o non trovo i permessi. Eseguire il login.");
            }
            if ( !empty($gruppi['admin'])) {
                return true;
            }
            
            $trovato = false;
            $action_name = $this->id . '/' . $action->id;
            if ( empty($gruppi[$action_name])) {
                throw new UserException("Non si hanno i permessi per accedere a questa funzione.");                
            }
        }
        /*if ( !isset(\Yii::$app->user) || !(isset(\Yii::$app->user->identity)) || !isset(\Yii::$app->user->identity->profilo->IdProfilo)) {
            //$this->layout = 'mainform';
            throw new UserException("Non esiste una sessione per l'utente. Eseguire il login.");
        }*/
        return true;
    }
    
    // Indica al sistema che per questo action devo controllare di avere il permesso per questa action
    public function devoControllarePermesso($action) {
        return false;
    }

    public static function linkwin1par($params) {
        $text = '';
        if ( !empty($params['text']))
            $text = $params['text'];
        $action = ''; 
        if ( !empty($params['action']))
            $action = $params['action'];
        $requestparams = '';
        if ( !empty($params['requestparams'])) {
            $requestparams = "";
            $keys = array_keys($params['requestparams']);
            foreach ($params['requestparams'] as $key => $value) {
                $requestparams .= $key . '=' . $value . '&';
            }
            $requestparams = $params['requestparams']; //[$key];        
        }
        $linktitle = $params['linktitle'];
        if ( !empty($params['linktitle']))
            $linktitle = $params['linktitle'];        
        $callback = '';
        if ( !empty($params['callback']))
            $callback = $params['callback'];                
        $windowparams = '';
        if ( !empty($params['windowparams']))
            $windowparams = $params['windowparams'];                
        $buttonclass = 'btn btn-primary';
        if ( !empty($params['buttonclass']))
            $buttonclass = $params['buttonclass'];                
        $onbeforeclick = null;
        if ( !empty($params['onbeforeclick']))
            $onbeforeclick = $params['onbeforeclick'];                
        $otherparams = null;
        if ( !empty($params['otherparams']))
            $otherparams = $params['otherparams'];                
        return self::linkwin($text, $action, $requestparams, $linktitle, $callback, $windowparams, $buttonclass, $onbeforeclick, $otherparams);
    }
    
    /**
     * 
     * @param type $action Nome dell'azione del tipo controller/action
     * @param type $permesso AIRVLC
     */
    public static function linkwin($text, $action, $params, $linktitle,  $callback, $windowparams=[], $buttonclass = 'btn btn-primary', $onbeforeclick = null, $otherparams = null) {
        $trovato = false;
        if ( !empty($windowparams['freetoall'])) {
            $trovato = true;
        } else {
            if ( Yii::$app->session != null ) {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi != null) {
                    if ( !empty($gruppi['admin']) || !empty($gruppi[$action])) 
                        $trovato = true;
                    /*foreach ($gruppi as $value) {
                        if ( $value['nometrans'] == $action) {
                            $trovato = true;
                            break;
                        }
                    }
                     */
                }
            }
        }
        $url = '';
        $fa = ''; $far = ''; $fas = '';
        if (str_contains($text, '|fa-')) {
            $pos = strpos($text, '|fa-');
            $fa = substr($text,$pos + 1);
            $text = substr($text,0,$pos);
        } else if (str_contains($text, '|far')) {
            $pos = strpos($text, '|far');
            $far = substr($text,$pos + 1);
            $text = substr($text,0,$pos);            
        } else if (str_contains($text, '|fas')) {
            $pos = strpos($text, '|fas');
            $fas = substr($text,$pos + 1);
            $text = substr($text,0,$pos);            
        }
        if ( $trovato) {
            if (is_array($params)) {
                $paramslink = array_merge([$action],$params);
            } else {
                $paramslink = array_merge([$action],[$params]);
            }
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
            $onclick = "return AppGlob.apriForm(this,'', '" . $callback ."'," . $p . ",'" . $titoloform . "')";
            if (!empty($onbeforeclick) )
                $onclick = 'if (' . $onbeforeclick . ") return AppGlob.apriForm(this,'', '" . $callback ."'," . $p . ",'" . $titoloform . "')";
            if ( !empty($otherparams['delete'])) {
                $url = Html::a($text, $paramslink,[ //['delete', $params[1]], [
                    'class' => $buttonclass,
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            } else {
                if ( $fas != '') {
                    $url = Html::a("<span class='fas " . $fas . "'></span>&#xA0;" . $text,$paramslink, ['title'=>$linktitle,'class'=>$buttonclass, 'onclick'=>$onclick]);
                } else if ( $far != '') {
                    $url = Html::a("<span class='far " . $far . "'></span>&#xA0;" . $text,$paramslink, ['title'=>$linktitle,'class'=>$buttonclass, 'onclick'=>$onclick]);
                } else {
                    $url = Html::a(($fa != ''?"<span class='fas " . $fa . "'></span>&#xA0;":"") . $text,$paramslink, ['title'=>$linktitle,'class'=>$buttonclass, 'onclick'=>$onclick]);
                }                                
            }
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
        if ( !empty($params['freetoall'])) {
            $trovato = true;
        } else {        
            if ( Yii::$app->session != null ) {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi != null) {
                    if ( !empty($gruppi['admin']) || !empty($gruppi[$action])) 
                        $trovato = true;
                    /*foreach ($gruppi as $value) {
                        if ( $value['nometrans'] == $action) {
                            $trovato = true;
                            break;
                        }
                    }
                     */
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
        /*if ( Yii::$app->user->isGuest)
            return null;*/
        $ret = [];
        $i = 0;
        foreach ($menuitems as $item) {
            $trovato = false;
            // Elaboro eventuali submenu
            if ( !isset($item['url']) || isset($item['forall']))
                    $trovato = true;
            else {
                $gruppi = Yii::$app->session['gruppi'];
                if ( $gruppi == null && !empty(Yii::$app->user->identity)) {
                    $gruppi = \Yii::$app->user->identity->getzGruppi();
                    Yii::$app->session['gruppi'] = $gruppi;
                }
                if ( $gruppi != null) {
                    $val = $item['url'][0];
                    if ( !empty($gruppi['admin']) || !empty($gruppi[$val])) 
                        $trovato = true;
                    /*
                    foreach ($gruppi as $value) {
                        $val = $item['url'][0];
                        if ( $value['nometrans'] == $val) {
                            $trovato = true;
                            break;
                        }                            
                    }
                     */
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
    
    public static function getToday() {
        return date('Y-m-d H:i:s');
    }
    
    public static function getTodayDate() {
        return date('Y-m-d');
    }
          
    public function getCookieConsent() {
        $session = Yii::$app->session;
        if (isset($session['cookieconsent'])) {
            return $session['cookieconsent'];
        }
        return null;
    }    
    
    public static function creaMenuContestualeRiga($params) { 
        echo '<div class=\'divmenucontestuale\'>   
        <a class=\'togglemenu\' pos=\'' .$params['pos'] . '\' href=\'javascript:void(0)\' onclick=\'AppGlob.apriMenuContestuale(this)\' >
            <i class=\'fa fa-angle-down\'><!--fa fa-ellipsis-h-->
            </i>        
        </a>
        <div class=\'menucontestuale\'>';
            $link = $params['link'];
            $chiavi = $params['chiavi']; // Array di chiave=>valore
            $items = $params['items']; // Array di chiave=>valore
            $callback = "document.location.reload(false)";
            if ( !empty($params['callback'])) {
                $callback = $params['callback'];
            }            
            foreach ($items as $key => $value) {
                $windowparams = [];                
                if ( !empty($params['windowparams']))
                    $windowparams = $params['windowparams'];
                if ( $key == 'edit' || $key == 'view' ) {
                    if ( !empty($value['windowwidth'])) {
                        $wwidth = $value['windowwidth'];
                        $windowparams['windowwidth'] = $wwidth;
                    } else {
                        $windowparams['windowwidth'] = 700;                            
                    }
                    if ( !empty($value['windowtitle'])) {
                        $wtitle = $value['windowtitle'];
                        $windowparams['windowtitle'] = $wtitle;
                    } else {
                        $windowparams['windowtitle'] = 'Modifica';
                    }                    
                }                
                if ( !empty($value['callback'])) { 
                    $callback = $value['callback'];
                }
                if ( $key == 'edit') {
                    echo \frontend\controllers\BaseController::linkwin1par(['text'=>Yii::t('app', 'Modifica'), 'action'=>$link . '/update','requestparams'=> $chiavi, 
                        'linktitle'=>Yii::t('app','Apri per modifica'),'callback'=>$callback,'buttonclass'=>'linkmenu',
                        'onbeforeclick'=>'AppGlob.closeMenuContestuale(this)',
                        'windowparams'=>$windowparams]); 
                } else if ( $key == 'delete') {
                    echo \frontend\controllers\BaseController::linkwin1par(['text'=>Yii::t('app', 'Cancella'), 'action'=>$link . '/delete','otherparams'=>['delete'=>'true'],'requestparams'=> $chiavi, 
                        'linktitle'=>Yii::t('app','cancella la riga'),'callback'=>$callback,'buttonclass'=>'linkmenu','onbeforeclick'=>'AppGlob.closeMenuContestuale(this)',
                        'windowparams'=>$windowparams]); 
                } else {
                    if (is_array($value)) {
                        $link1 = $value['link'];
                        $title = "";
                        if ( !empty($value['title'])) {
                            $title = $value['title'];
                        }
                        if ( !empty($value['freetoall'])) {
                            $free = $value['freetoall'];
                            if ( $free == true)
                                $windowparams['freetoall'] = 'true';
                        }                        
                        echo \frontend\controllers\BaseController::linkwin1par(['text'=>Yii::t('app', $key), 'action'=>$link1,'requestparams'=> $chiavi, 
                            'linktitle'=>Yii::t('app',$title),'callback'=>'document.location.reload(false)','buttonclass'=>'linkmenu',
                            'onbeforeclick'=>'AppGlob.closeMenuContestuale(this)',
                            'windowparams'=>$windowparams]);
                    }
                }                
            }
        echo '</div>
    </div>';
    }
    
    public static function formattaDataLunga($valore) {
        $formatter = new \IntlDateFormatter(
            'it_IT',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Europe/Rome',
            \IntlDateFormatter::GREGORIAN
        );
        $formatter->setPattern("EEEE d MMMM yyyy kk:mm");
        $ret = $formatter->format($valore);
        return $ret;
    }
    
    public static function formattaDataCorta($valore) {
        $formatter = new \IntlDateFormatter(
            'it_IT',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Europe/Rome',
            \IntlDateFormatter::GREGORIAN
        );
        $formatter->setPattern("EEEE d MMMM yyyy");
        $ret = $formatter->format($valore);
        return $ret;
    }
    
}
