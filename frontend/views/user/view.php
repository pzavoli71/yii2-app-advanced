
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;
/** @var yii\web\View $this */
/** @var common\models\\user $model */

$this->title = $model->id;
$this->params['model'] = $model;
//$this->params['breadcrumbs'][] = ['label' => 'Obiettivos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id'=>$model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id'=>$model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		'id',
		'username',
		'auth_key',
		'status',
		'email',
		'created_at',
		'updated_at',
		
        ],
    ]) ?>

</div>

<?php Yii::$app->view->on(View::EVENT_END_BODY, function () {
    //echo ('<span class="ultagg">Modificato da <b>'. $this->params['model']['utente'] . '</b> in data <b>' . $this->params['model']['ultagg'] . '</b></span>');
});
?>

