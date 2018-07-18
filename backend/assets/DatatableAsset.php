<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DatatableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/plugins/jquery-datatable/css/jquery.dataTables.css',
        'themes/plugins/datatables-responsive/css/datatables.responsive.css',
    ];
    public $js = [
        //'themes/plugins/jquery/jquery-1.11.3.min.js',
//        'themes/plugins/jquery-block-ui/jqueryblockui.min.js',
//        'themes/plugins/jquery-unveil/jquery.unveil.min.js',
//        'themes/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'themes/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        'themes/plugins/jquery-datatable/js/jquery.dataTables.min.js',
        'themes/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js',
        'themes/plugins/datatables-responsive/js/datatables.responsive.js',
        'themes/plugins/datatables-responsive/js/lodash.min.js',
         'themes/plugins/bootstrap-select2/select2.min.js',
        'themes/js/datatable_boostrap_extend.js',
        'themes/js/datatables.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
       // 'yii\bootstrap\BootstrapAsset',
    ];
}
