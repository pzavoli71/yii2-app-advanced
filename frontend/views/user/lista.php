
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\View;

$this->title = 'Lista utenti';
$this->registerJsFile(
    '@web/js/app.js',
    ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
);
//$this->registerJs(
//    "AppGlob.registraFunzioniPerListe(apriMenu, apriRigaRelazioni);",
//    yii\web\View::POS_READY,'my_script_id'
//);

//Fix for closing icon (x) not showing up in dialog
$this->registerJs("if ($.fn.button && $.fn.button.noConflict) {
                var bootstrapButton = $.fn.button.noConflict(); 
                $.fn.bootstrapBtn = bootstrapButton;
            }",
            \yii\web\View::POS_READY
);
$this->params['breadcrumbs'][] = $this->title;

?>

<?php
    $hrefpage = "";
	creaLista($this, $dataProvider, $searchModel, $combo);
	$hrefpage = "";
?>

<script language="javascript" id="scripttimeout">
    setTimeout(function() {AppGlob.resize2(window); $('#scripttimeout').remove();},300);
</script>

<!--?php $this->registerJs(
"AppGlob.registraFunzioniPerListe(apriMenu, apriRigaRelazioni);AppGlob.inizializzaLista();	",
View::POS_READY,
'resize-page-script'
);?-->
	
<?php function creaLista($thisobj, $dataProvider, $searchModel, $combo) {?>

<script type="text/javascript">


// Apre il menu contestuale in corrispondenza della riga
function apriMenu(chiave, nomepdc, riga) {

}

var ajaxrequest = null;


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
		'combo' => $combo, 
    ]) ?-->

    <p style="margin-bottom:0px; margin-top:5px">
		<?php echo frontend\controllers\BaseController::linkwin('Aggiungi|fa-plus', '/user/create', [], 'Inserisci un nuovo elemento','document.location.reload(false)',['windowtitle'=>'Prova','windowwidth'=>'700']); ?>
		<!--a class="btn btn-success" onclick="apriForm(this, '/index.php?r=quiz/create')" href="javascript:void(0)" title="Update" aria-label="Update" data-pjax="0"><span class="fas fa-plus" aria-hidden="true"></span>Create user</a-->	
    </p>

    <?= Yii::$app->session->getFlash('kv-detail-success'); ?>

    <?php
		$models = $dataProvider->getModels();?>
		<table class="tabLista kv-grid-table table table-bordered table-striped kv-table-wrap"> 
		<tr >
			<th style="min-width:180px"></th>
			<th data-nomecol='id'>id</th>
			<th data-nomecol='username'>username</th>
			<th data-nomecol='auth_key'>auth_key</th>
			<th data-nomecol='status'>status</th>
			<th data-nomecol='email'>email</th>
			<th data-nomecol='created_at'>created_at</th>
			<th data-nomecol='updated_at'>updated_at</th>

		</tr>
		<!--td> Per i comandi sulla riga
		</td-->

		<?php
		
		$pos = 1;
		foreach ($models as $riga) {?>
			<tr id='Rigauser_<?=$pos?>' chiave="<?=$riga->id?>">
				<td>
					<?php echo frontend\controllers\BaseController::linkwin('Edit|fa-edit', '/user/view', ['id'=>$riga->id], 'Apri per modifica','document.location.reload(false)',['windowtitle'=>'Inserisci i parametri','windowwidth'=>'700']); ?>
				</td>   
				<td><span class="headcol">id:</span><?= $riga->id ?></td>
				<td><span class="headcol">username:</span><?= $riga->username ?></td>
				<td><span class="headcol">auth_key:</span><?= $riga->auth_key ?></td>
				<td><span class="headcol">status:</span><?= $riga->status ?></td>
				<td><span class="headcol">email:</span><?= $riga->email ?></td>
				<td><span class="headcol">created_at:</span><?= $riga->created_at ?></td>
				<td><span class="headcol">updated_at:</span><?= $riga->updated_at ?></td>
		
			<!--td-->
				<!-- Scommentare per richiamare un comando sulla riga -->
				<!--?php echo frontend\controllers\BaseController::linkcomando('Chiudi|fa-flag-checkered', 'busy/obiettivo/chiudilavoro',$rigarel->IdLavoro, ['IdLavoro'=>$rigarel->IdLavoro], 
						'Esegui il comando'); ?-->                             
				<!--?php echo frontend\controllers\BaseController::linkcomandocondialog('Chiudi|fa-flag-checkered', 'busy/obiettivo/chiudilavoro',$rigarel->IdLavoro, ['IdLavoro'=>$rigarel->IdLavoro], 
						'Apri per modifica'); ?-->                                        						
			<!--/td-->

			</tr>
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
	

