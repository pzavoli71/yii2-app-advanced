
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\abilitazione\zgruppo . $model */

$this->title = 'Modifica gruppo:' . $model->idgruppo;

\yii\web\YiiAsset::register($this);
?>
<div class="zgruppo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'combo' => isset($combo) ? $combo: null,
		// Eventuali items per i combo
        //'itemsTpOccup'=>$itemsTpOccup,        
    ]) ?>

</div>
