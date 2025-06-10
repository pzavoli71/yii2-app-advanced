<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\View;
use pzavoli71\cookieconsent\CookieDialog;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head profile="http://www.w3.org/2005/10/profile">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
<link rel="icon" 
      type="image/png" 
      href="favicon.png">    

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>    

    <!-- **************** -->
    <!-- Consent decision -->
    <?php $this->registerJsFile(
        '@web/js/app.js',
        ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
    );?>
    
    <?php 
        //Fix for closing icon (x) not showing up in dialog
        $this->registerJs("if ($.fn.button && $.fn.button.noConflict) {
                        var bootstrapButton = $.fn.button.noConflict(); 
                        $.fn.bootstrapBtn = bootstrapButton;
                    }",
                    \yii\web\View::POS_READY
        );    
     ?>

    
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<main role="main" class="flex-shrink-0">
    <?= Html::tag('div',Html::a("Torna all'applicazione",['/site/index'],['class' => ['text-decoration-none'],'target'=>'_top']),['class' => ['d-flex']]);?>    
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div> 

    <?php 
        \pzavoli71\cookieconsent\Module::addCookieConsent();
     ?>        
    
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end">            
               <?php echo(\Yii::t('yii', 'Icon by {Freepik}', ['Freepik' => '<a href="https://www.freepik.com/search/" rel="external">' . \Yii::t('yii', 'Freepik') . '</a>'])) ?>             
             <!--?= Yii::powered() ?--></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
