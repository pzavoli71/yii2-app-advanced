
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\zUtGr $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="zutgr-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'idgruppo') ->dropDownList(
			$combo['zGruppo'],           // Flat array ('id'=>'label')
			['prompt'=>'']    // options
	)->label('Gruppo'); ?>

	<?= $form->field($model,'id') ->dropDownList(
			$combo['user'],           // Flat array ('id'=>'label')
			['prompt'=>'']    // options
	)->label('Utente'); ?>

	<?= $form->field($model,'idutgr')->hiddenInput()->label(false) ?>	
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
