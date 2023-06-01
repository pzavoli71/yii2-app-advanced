
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\Profilo $model */

$this->title = 'Profilo ' . $model->IdProfilo;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="profilo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modifica', ['update', 'IdProfilo'=>$model->IdProfilo], ['class' => 'btn btn-primary']) ?>
        <!--?= Html::a('Delete', ['delete', 'IdProfilo'=>$model->IdProfilo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?-->
		<!--?= Html::a('Insert', ['create'], ['class' => 'btn btn-insert']) ?-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		
			'id',
		
			'IdProfilo',
		
			'Cognome',
		
			'Nome',
		
			'Nascita',
		
			'AnnoNascita',
		
            'ultagg',
            'utente',
        ],
    ]) ?>

</div>
