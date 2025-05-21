
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use pzavoli71\widgets\AutocompleteDropdown;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\ztrans $model */
/** @var yii\widgets\ActiveForm $form */

?>

<?php $this->params['breadcrumbs'][] = $this->title; 
$this->params['home'] = Url::to(['/abilitazione/ztrans/view','idtrans'=>$model->idtrans]);  ?>

<div class="ztrans-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'idtrans')->hiddenInput() ?>	
		
		<?= $form->field($model,'nometrans')->textInput() ?>
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
