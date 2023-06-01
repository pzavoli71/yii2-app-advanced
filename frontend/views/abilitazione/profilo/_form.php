
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\Profilo $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="profilo-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'id')->hiddenInput(); ?>

	<?= $form->field($model,'IdProfilo')->hiddenInput() ?>	
		
		<?= $form->field($model,'Cognome')->textInput() ?>
		
		<?= $form->field($model,'Nome')->textInput() ?>
		
		<?= $form->field($model,'Nascita')->widget(DateControl::className(),
			['type'=>DateControl::FORMAT_DATE,  
			'convertFormat'=>false,
			]); 
		?>				
				
	<?= $form->field($model,'AnnoNascita')->widget(\yii\widgets\MaskedInput::className(),
			\frontend\controllers\BaseController::$MASK_INTEGER_PARAMS_WIDGET,
	); ?>
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
