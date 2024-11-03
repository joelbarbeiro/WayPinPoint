<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class TemplateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',  // Adjust with the actual path to your template's main CSS file
        // Add other CSS files here
    ];
    public $js = [
        'js/main.js',  // Adjust with the actual path to your template's main JS file
        // Add other JavaScript files here
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
