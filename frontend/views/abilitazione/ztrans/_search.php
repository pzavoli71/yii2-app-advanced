
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\ztrans $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="ztrans-search searchform">
    <style>
        .ztrans-search .form-control {
            /*width:initial;*/
        }    
    </style>

    <fieldset>
        <legend>Inserisci il filtro</legend>
	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
        'action' => ['lista'],
        'method' => 'get',		
	]); ?>
	
	
	<?= $form->field($model,'idtrans')->hiddenInput() ?>	
		
		<?= $form->field($model,'nometrans')->textInput() ?>
		
	
    <div class="form-group-search">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	</fieldset>
</div>
