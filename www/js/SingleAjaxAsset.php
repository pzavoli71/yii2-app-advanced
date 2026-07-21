<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * AssetBundle per le maschere "Single" aperte in div via ajax.
 *
 * Posizionare i file:
 *   frontend/assets/SingleAjaxAsset.php   (questo file)
 *   frontend/web/js/single-ajax.js
 *   frontend/web/css/single-ajax.css
 *
 * Le view generate fanno SingleAjaxAsset::register($this).
 */
class SingleAjaxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/single-ajax.css',
    ];

    public $js = [
        'js/single-ajax.js',
    ];

    public $depends = [
        \yii\web\YiiAsset::class,        // include yii.js (CSRF) e jQuery
        \yii\web\JqueryAsset::class,
    ];
}
