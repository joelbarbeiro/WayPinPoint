<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class TemplateAsset extends AssetBundle
{
    public $basePath = '@webroot/template-assets';
    public $baseUrl = '@web/template-assets';

    public $css = [
        'css/style.css',
        'css/responsive.css',
    ];

    public $js = [
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
