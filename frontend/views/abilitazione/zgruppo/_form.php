
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use pzavoli71\widgets\AutocompleteDropdown;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\zgruppo $model */
/** @var yii\widgets\ActiveForm $form */

?>

<?php $this->params['breadcrumbs'][] = $this->title; 
$this->params['home'] = Url::to(['/abilitazione/zgruppo/view','idgruppo'=>$model->idgruppo]);  ?>

<div class="zgruppo-form">

	<?php $form = ActiveForm::begin([
		//'enableAjaxValidation' => true,
	]); ?>
	
	
	<?= $form->field($model,'idgruppo')->hiddenInput() ?>	
		
		<?= $form->field($model,'nomegruppo')->textInput() ?>
		
	
	<!--?= $form->field($model, 'imageFile')->fileInput() ?--> <!-- Scommentare per fare fileupload -->
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
