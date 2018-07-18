<?php

namespace backend\assets;

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
        'themes/plugins/font-awesome/css/font-awesome.css',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'css/animate.css',
        'css/pnotify.custom.min.css',
        'themes/plugins/jquery-scrollbar/jquery.scrollbar.css',
        'themes/webarch/css/webarch.css',
        'css/vue-multiselect.min.css',
        'css/toastr.min.css',
        'css/backend.css',
    ];
    public $js = [
        'themes/plugins/pace/pace.min.js',
        'themes/plugins/bootstrapv3/js/bootstrap.min.js',
        'themes/plugins/jquery-block-ui/jqueryblockui.min.js',
        'themes/plugins/jquery-unveil/jquery.unveil.min.js',
        'themes/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'themes/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        'themes/plugins/jquery-validation/js/jquery.validate.min.js',
        'themes/webarch/js/webarch.js',
        'js/yii2-dynamic-edit.js',
        'js/toastr.min.js',
        'js/Vue2.js',
        'js/vee-validate.js',
        'js/moment.min.js',
        'js/vue-strap.js',
        'js/vue-multiselect.min.js',

        //'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js'

//        'themes/js/chat.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',

    ];
}
