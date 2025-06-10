
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\View;
/** @var yii\web\View $this */
/** @var common\models\\user $model */

$this->title = '';
//$this->params['breadcrumbs'][] = ['label' => 'Obiettivos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

</div>

<?php Yii::$app->view->on(View::EVENT_END_BODY, function () {
    //echo ('<span class="ultagg">Modificato da <b>'. $this->params['model']['utente'] . '</b> in data <b>' . $this->params['model']['ultagg'] . '</b></span>');
});
?>

