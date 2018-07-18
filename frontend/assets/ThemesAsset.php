<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ThemesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/plugins/pace/pace-theme-flash.css',
        'themes/plugins/bootstrapv3/css/bootstrap.min.css',
        'themes/plugins/bootstrapv3/css/bootstrap-theme.min.css',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'themes/plugins/animate.min.css',
        'themes/plugins/jquery-scrollbar/jquery.scrollbar.css',
        'themes/webarch/css/webarch.css'
    ];
    public $js = [
        'themes/plugins/pace/pace.min.js',
        'themes/plugins/jquery/jquery-1.11.3.min.js',
        'themes/plugins/bootstrapv3/js/bootstrap.min.js',
        'themes/plugins/jquery-block-ui/jqueryblockui.min.js',
        'themes/plugins/jquery-unveil/jquery.unveil.min.js',
        'themes/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'themes/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        'themes/plugins/jquery-validation/js/jquery.validate.min.js',
        'themes/webarch/js/webarch.js',
        'themes/js/chat.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
