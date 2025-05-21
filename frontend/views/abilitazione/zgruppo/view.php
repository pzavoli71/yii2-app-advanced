
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;
/** @var yii\web\View $this */
/** @var common\models\abilitazione\zgruppo $model */

$this->title = $model->idgruppo;
$this->params['model'] = $model;
//$this->params['breadcrumbs'][] = ['label' => 'Obiettivos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="zgruppo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idgruppo'=>$model->idgruppo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idgruppo'=>$model->idgruppo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		<?= Html::a('Insert', ['create'], ['class' => 'btn btn-insert']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		'idgruppo',
		'nomegruppo',
		
        ],
    ]) ?>

</div>

<?php Yii::$app->view->on(View::EVENT_END_BODY, function () {
    echo ('<span class="ultagg">Modificato da <b>'. $this->params['model']['utente'] . '</b> in data <b>' . $this->params['model']['ultagg'] . '</b></span>');
});
?>

