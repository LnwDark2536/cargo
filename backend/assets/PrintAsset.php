<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PrintAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap4.css',
        'themes/plugins/font-awesome/css/font-awesome.css',
        'css/print.css'
    ];
    public $js = [
    ];
    public $depends = [
       // 'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
