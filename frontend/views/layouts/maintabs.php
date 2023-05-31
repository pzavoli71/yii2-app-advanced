<?php

/** @var yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\NavBar;
use yii\web\View;

use common\components\NewNav;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
//$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => 'favicon.ico']);
$this->registerLinkTag(['rel' => 'manifest', 'href' => 'scuola.webmanifest']);

$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'57x57', 'href' => 'apple-icon-57x57.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'60x60', 'href' => 'apple-icon-60x60.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'72x72', 'href' => 'apple-icon-72x72.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'76x76', 'href' => 'apple-icon-76x76.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'114x114', 'href' => 'apple-icon-114x114.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'120x120', 'href' => 'apple-icon-120x120.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'144x144', 'href' => 'apple-icon-144x144.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'152x152', 'href' => 'apple-icon-152x152.png']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes'=>'180x180', 'href' => 'apple-icon-180x180.png']);
$this->registerLinkTag(['rel' => 'icon','sizes'=>'192x192', 'type' => 'image/png', 'href' => 'android-icon-192x192.png']);
$this->registerLinkTag(['rel' => 'icon','sizes'=>'32x32', 'type' => 'image/png', 'href' => 'favicon-32x32.png']);
$this->registerLinkTag(['rel' => 'icon','sizes'=>'96x96', 'type' => 'image/png', 'href' => 'favicon-96x96.png']);
$this->registerLinkTag(['rel' => 'icon','sizes'=>'16x16', 'type' => 'image/png', 'href' => 'favicon-16x16.png']);

$this->registerMetaTag(['name' => 'msapplication-TileColor', 'content' => '#ffffff']);
$this->registerMetaTag(['name' => 'msapplication-TileImage', 'content' => 'ms-icon-144x144.png']);
$this->registerMetaTag(['name' => 'theme-color', 'content' => '#ffffff']);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" type="text/css"  href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">    
    <link rel="stylesheet" type="text/css" href="css/addtohomescreen.css">    
	
	<?php $this->registerJsFile(
    '@web/js/addtohomescreen.js',
    ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
	);?>
	<?php $this->registerJsFile(
    '@web/js/app.js',
    ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
	);?>
	<?php $this->registerJsFile(
    '@web/js/tabs.js',
    ['depends' => [\yii\web\JqueryAsset::class, \yii\jui\JuiAsset::class]]
	);?>
    
    <?php $this->registerJs(
    "addToHomescreen(); Tabs.addTab(null, 'Home','Pagina Home','index.php?r=site/home'); return false;",
    View::POS_READY,
    'resize-page-script'
    );?>    
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header" class="fixed-top">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark']
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['site/home']],
        ['label' => 'Mappa', 'url' => ['mappe/mappe']],
        ['label' => 'Obiettivi', 'url' => ['busy/obiettivo/index']],
        ['label' => 'Quiz', 'url' => ['busy/obiettivo/index'], 'nolink'=>'true', 'items' => [
            ['label' => 'Lista Quiz', 'url' => ['patente/quiz/index']]            
        ]],        
        ['label' => 'About', 'url' => ['site/about']],
        ['label' => 'Contact', 'url' => ['site/contact']],
        ['label' => 'Tabelle',  'items' => [
            ['label' => 'Lista Occupazioni', 'url' => ['busy/tipooccupazione/index']],
            ['label' => 'Lista Argomenti', 'url' => ['busy/argomento/lista']],
            ['label' => 'Lista Tipo permessi', 'url' => ['busy/tipopermesso/lista']],
            ['label' => 'Lista permessi', 'url' => ['abilitazione/zutgr/index']],
            ['label' => 'Lista transazioni', 'url' => ['abilitazione/ztrans/lista']]
        ]],        
        
    ];
    $menuItems = \frontend\controllers\BaseController::menu($menuItems);
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup'], 'notabs' => 'true'];
    } else {
        $menuItems[] = ['label' => 'Modifica profilo', 'url' => ['/soggetti/soggetto/view','IdSoggetto' => Yii::$app->user->identity->soggetto->IdSoggetto]];        
    }
    $menuItems[] = ['label' => 'Mappe', 'url' => ['mappe/mappe/mappe'], 'notabs' => 'true'];
    echo NewNav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }        
    ?>

	<?php
    NavBar::end();
    ?>
</header>

<div id="maintabs" class="flex-shrink-0" role="main">
    <div class="container" id="main-container">
		<div id="tabs-header"><ul></ul></div>
		<!--button class="btn btn-success" onclick="Tabs.addTab('prova','Elemento','/index.php?r=site/index')"></button-->
		<div class="tabs-container"></div>
        <?= Alert::widget() ?>
        <!--?= $content ?-->
    </div>
</div>

<!--footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
