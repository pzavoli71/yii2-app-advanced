
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\ztrans $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="ztrans-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'idtrans')->hiddenInput()->label(false) ?>	
		
		<?= $form->field($model,'nometrans')->textInput() ?>
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		<?php if (!empty($model->idtrans)) { ?>
        <?= Html::a('Annulla', Yii::$app->request->referrer, ['class' => 'btn btn-secondary']) ?>
        <?php }?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
