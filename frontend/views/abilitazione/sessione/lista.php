
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\View;

$this->title = 'Titolo della pagina';
$this->registerJsFile(
    '@web/js/app.js',
    ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
);
$this->registerJs(
    "AppGlob.registraFunzioniPerListe(apriMenu, apriRigaRelazioni);",
    yii\web\View::POS_READY,'my_script_id'
);

//Fix for closing icon (x) not showing up in dialog
$this->registerJs("if ($.fn.button && $.fn.button.noConflict) {
                var bootstrapButton = $.fn.button.noConflict(); 
                $.fn.bootstrapBtn = bootstrapButton;
            }",
            \yii\web\View::POS_READY
);
?>

<?php
    $hrefpage = "";
    if (empty($nomerelaz)) {
		if ( empty($parametri))
			$parametri = [];
        creaLista($this, $dataProvider, $searchModel, $combo, $parametri);
        $hrefpage = "";
    }
    else {
        $rigapos = 1;
        $models = $dataProvider->getModels();
        $model = $models[0];
		
		resizeAll();
    }    
?>

<?php function resizeAll() {?>
<script language="javascript" id="scripttimeout">
    setTimeout(function() {AppGlob.resize2(window); $('#scripttimeout').remove();},300);
</script>
<?php }?>

<?php $this->registerJs(
"AppGlob.registraFunzioniPerListe(apriMenu, apriRigaRelazioni);AppGlob.inizializzaLista();	",
View::POS_READY,
'resize-page-script'
);?>
	
<?php function creaLista($thisobj, $dataProvider, $searchModel, $combo, $parametri) {?>

<script type="text/javascript">
// Apre una riga sotto quella corrente, per visualizzare le relazioni aperte con altri pdc
function apriRigaRelazioni(chiave, nomepdc, riga, rigarel) {
    // La riga corrispondente all'elemento selezionato e' la seguente
	var spezzanome = nomepdc.split('.');
	var nome = spezzanome[spezzanome.length - 1];                   
	var tr = riga; 
	var chiavi = chiave.split('_'); 
	var dati = {};
	if ( nome == 'abilitazione\\Sessione') {
		dati['id'] = chiavi[0];

	}
	// Scommentare per fare il caricamento manuale ogni volta che si clicca sul + di questa relazione
	riga.next().find('.refresh_btn.btn_riga').click();
}

// Apre il menu contestuale in corrispondenza della riga
function apriMenu(chiave, nomepdc, riga) {

}

var ajaxrequest = null;
// Apre il menu contestuale in corrispondenza della riga
function caricaRelazione(obj) { 
	// Devo andare a ritroso nel dom fino a trovare un div con class="divRelazione"
	var odivRelaz = AppGlob.trovaDivRelazione(obj);
	if ( odivRelaz == null)
		return;

	var nomepdc = odivRelaz.attr("nomepdc");
	var nomerelazione = odivRelaz.attr("nomerelaz");
	var chiave = odivRelaz.attr("chiave");
    
	var dati = {};
	var spezzanome = nomepdc.split('.');
	var nome = spezzanome[spezzanome.length - 1];           
	var chiavi = chiave.split('_'); 
    
	if(odivRelaz.find('.rowsPerPage')) {
		dati['rpp']=odivRelaz.find('.rowsPerPage').val();
	}
	if(odivRelaz.find('.currPage')) {
		if($(obj).hasClass('nextPage')) {
			dati['page']=parseInt(odivRelaz.find('.currPage').val())+1;
		}
		else if($(obj).hasClass('prevPage')) {
			dati['page']=parseInt(odivRelaz.find('.currPage').val())-1;
		}
		else {
			dati['page']=odivRelaz.find('.currPage').val();
		}
	}
	if ( nome == 'abilitazione\\Sessione') {
		dati['id'] = chiavi[0];

	}
	<?php $currentcontroller = Yii::$app->controller->id; ?>
	
	AppGlob.reloadRelazione('<?= Url::toRoute($currentcontroller . "/reloadrelazione")?>',nomepdc,nomerelazione,dati,odivRelaz, function(dati, data) {
		AppGlob.inizializzaLista();
    });
}

function richiestaComandoConDialog(nomecomando, chiave, dati, href,callback,startcomando) {
	// Qui posso variare il contenuto dell'area dati, aggiungendo attributi con nome e valore
	// che verranno riportati alla servlet come parametri.
	var chiavi = chiave.split('_');
	dati.IdDocumento=chiavi[0];
	$('#dialog').dialog({
		autoOpen: true,
		modal:true,
		title:'Inserisci i parametri e premi ok',
		position:{ my: 'top', at: 'top+150' },
		width:600,
		show: {
        	effect: "blind",
        	duration: 100
      	},
      	hide: {
        	effect: "explode",
        	duration: 400
      	},	
		buttons: {
        	Ok: function() {
				var NoteCompl = $( this ).find('#NoteCompl').val();
				//var anno = $( this ).find('#AnnoCompl').val();
				//var mese = $( this ).find('#MeseCompl').val();
				dati.NotaLavoro = NoteCompl;
                                dati.IdLavoro = chiave;
				//dati.AnnoCompl=anno;
				//dati.MeseCompl=mese;
                                debugger;
				startcomando(nomecomando,chiave, dati, callback);
          		$( this ).dialog( "close" );	
        	},
        	Annulla: function() {
          		$( this ).dialog( "close" );
        	}
      	}      	
        });	
	return true;
}

function richiestaComando(nomecomando, chiave, dati) {
	// Qui posso variare il contenuto dell'area dati, aggiungendo attributi con nome e valore
	// che verranno riportati alla servlet come parametri.
	var valore = prompt('inserisci il comando per ',chiave);
	//if ( nomecomando == 'busy/obiettivo/chiudilavoro') {
	//	dati.IdLavoro = chiave;
	//}	
	dati.Cognome='xxxxx';
	return true;
}

// Funzione che gestisce il ritorno del comando
function comandoTerminato(nomecomando, chiave, data, href, callback) {
	function terminaComando() {
		if ( nomecomando == 'busy/obiettivo/chiudilavoro') {
			var id = nomecomando.replace(/\//g,'_');
			id += '_' + chiave;                    
			$a = $('#' + id);
			//caricaRelazione($a);
		}                        
	}
	if ( data && data.error) {
		var errore = data.error;
		AppGlob.emettiErrore(errore, function() {
			terminaComando();
		});
	} else {
		terminaComando();
	}
}
</script>


<style>
.cis-field-container label {
	width:150px;
}
/*.cis-field-container .ValoreAttributo {
	width:350px;
}*/
.screen-s .cis-field-container, .screen-m .cis-field-container {
	display:inline-block;
}	
</style>


<!-- Inizio form -->
<div class="quiz-index">
    
	<!-- Dialog per richiesta parametri, normalmente invisibile -->
	<div id="dialog" class="dialogcomando">
        <div class="cis-field-container" id="CampoNoteCompl">
            <label style="">Aggiungi una nota</label>
            <div class="ValoreAttributo" style=""><textarea name="NoteCompl" class="cis-input-textarea" origvalue="seconda prova, spero che vada a buon fine" cols="30" rows="4" id="NoteCompl" title="" placeholder="inserisci una nota..."></textarea>
            </div>
        </div>					
    </div>
	
    <h4><?= Html::encode($thisobj->title) ?></h4>
    
    <!-- Maschera per la ricerca -->
    <!--?= $thisobj->render('_search', [
		'model' => $searchModel,
    ]) ?-->

    <?= Yii::$app->session->getFlash('kv-detail-success'); ?>

    <?php
		$models = $dataProvider->getModels();?>
		<table class="tabLista kv-grid-table table table-bordered table-striped kv-table-wrap"> 
		<tr >
			<th style="min-width:180px"></th>
			<th data-nomecol='id'>id</th>
			<th data-nomecol='expire'>expire</th>
			<th data-nomecol='data'>data</th>
			<th data-nomecol='user_id'>user_id</th>
			<th data-nomecol='browser_platform'>browser_platform</th>
			<th data-nomecol='last_write'>last_write</th>
			<th data-nomecol='ipaddress'>ipaddress</th>

		</tr>
		<!--td> Per i comandi sulla riga
		</td-->

		<?php
		
		$pos = 1;
		foreach ($models as $riga) {?>
			<tr id='RigaSessione_<?=$pos?>' chiave="<?=$riga->id?>" class="<?=fmod($pos,2) == 1?'rigaDispari':'rigaPari'; ?>">
				<td class="tdbottoni"><?= showToggleInrelations($riga,$pos,true) ?>
					<?php echo frontend\controllers\BaseController::linkwin('Edit|fa-edit', 'abilitazione/sessione/view', ['id'=>$riga->id], 'Apri per modifica','document.location.reload(false)',['windowtitle'=>'Inserisci i parametri','windowwidth'=>'700']); ?>
				</td>   
				<td><span class="headcol">id:</span><?= $riga->id ?></td>
				<td><span class="headcol">expire:</span><?= $riga->expire ?></td>
				<td><span class="headcol">data:</span><?= $riga->data ?></td>
				<td><span class="headcol">user_id:</span><?= $riga->user_id ?></td>
				<td><span class="headcol">browser_platform:</span><?= $riga->browser_platform ?></td>
				<td><span class="headcol">last_write:</span><?= $riga->last_write ?></td>
				<td><span class="headcol">ipaddress:</span><?= $riga->ipaddress ?></td>
		
			<!--td class="tdbottoni"-->
				<!-- Scommentare per richiamare un comando sulla riga -->
				<!--?php echo frontend\controllers\BaseController::linkcomando('Chiudi|fa-flag-checkered', 'busy/obiettivo/chiudilavoro',$rigarel->IdLavoro, ['IdLavoro'=>$rigarel->IdLavoro], 
						'Esegui il comando'); ?-->                             
				<!--?php echo frontend\controllers\BaseController::linkcomandocondialog('Chiudi|fa-flag-checkered', 'busy/obiettivo/chiudilavoro',$rigarel->IdLavoro, ['IdLavoro'=>$rigarel->IdLavoro], 
						'Apri per modifica'); ?-->                                        						
			<!--/td-->

			</tr>
			<?= RelazioniSessione($riga,$pos) ?>                         
			<?php $pos++;?>
		<?php } ?>            

		</table>

		<!-- Paginatore  -->
		<?php
		echo LinkPager::widget([
			'pagination' => $dataProvider->getPagination(),
			'pageCssClass' => 'page-item',
			'linkOptions' => ['class' => 'page-link'],
            //,'onclick' => 'Tabs.openLinkInCurrentTab(this); return false;'],
		]);	
		?>		
	
</div> <!-- div generale -->
<?php }?>	<!-- function lista() -->
	
	
<?php function showToggleInrelations($model, $pos, $ramochiuso) { ?>
    <a pos="<?=$pos?>" href="javascript:void(0)" style="margin-left:1px; margin-right:5px" onclick="AppGlob.apriRigaRelazioni(this, '<?= str_replace("\\",".",get_class($model))?>')" >
        <i class="far <?php if ($ramochiuso == false) echo('fa-minus-square'); else  echo('fa-plus-square');?>">
        </i>        
    </a>
<?php } ?>

	

<?php 
// ============================================ 
//    Righe Relazione                             
// ============================================ 
function RelazioniSessione($riga, $rigapos) { ?>

<!-- ============================================ -->
<!--    Relazioni tra i pdc                       -->
<!-- ============================================ -->
	<tr id="RigaRelSessione_<?=$rigapos?>" class="<?=fmod($rigapos,2) == 1?'rigaDispari':'rigaPari'; ?>">
    <td colspan="100" class="closed tdRelazione" >


    </td>
	</tr>
<?php } ?>


