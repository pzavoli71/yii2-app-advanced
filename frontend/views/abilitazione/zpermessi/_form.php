
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use pzavoli71\widgets\AutocompleteDropdown;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\zpermessi $model */
/** @var yii\widgets\ActiveForm $form */

?>

<?php $this->params['breadcrumbs'][] = $this->title; 
$this->params['home'] = Url::to(['/abilitazione/zpermessi/view','idpermessi'=>$model->idpermessi]);  ?>

<div class="zpermessi-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'idgruppo')->dropDownList(
			$combo['zgruppo'],           // Flat array ('id'=>'label')
			[
				'prompt' => Yii::t('app','scegli gruppo')
				//, Questo comando serve a caricare dinamicamente un secondo combo a partire dai valori del primo
				//'onchange'=>'
				//	href = "' . Yii::$app->urlManager->createUrl(["busy/obiettivo/reloadcombo","nomecombo"=>"TpOccup"]) . '";' .
				//	'href += "&params={\"IdArg\":\"" + $(this).val() + "\"}";' .
				//	'href += "&currcombovalue=" + $( "select#obiettivo-tpoccup" ).val();' .
				//	'$.get(href, function(data) {' .
				//	'$( "select#obiettivo-tpoccup" ).html( data ).focus()' .
				//	'})'*/
			]                
	); ?>
	
	

	<?= $form->field($model,'idtrans')->dropDownList(
			$combo['ztrans'],           // Flat array ('id'=>'label')
			[
				'prompt' => Yii::t('app','scegli transazione')
				//, Questo comando serve a caricare dinamicamente un secondo combo a partire dai valori del primo
				//'onchange'=>'
				//	href = "' . Yii::$app->urlManager->createUrl(["busy/obiettivo/reloadcombo","nomecombo"=>"TpOccup"]) . '";' .
				//	'href += "&params={\"IdArg\":\"" + $(this).val() + "\"}";' .
				//	'href += "&currcombovalue=" + $( "select#obiettivo-tpoccup" ).val();' .
				//	'$.get(href, function(data) {' .
				//	'$( "select#obiettivo-tpoccup" ).html( data ).focus()' .
				//	'})'*/
			]                
	); ?>
	

	<?= $form->field($model,'idpermessi')->hiddenInput()->label(false); ?>	
		
    <?= $form->field($model,'permesso')->textInput() ?>
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
