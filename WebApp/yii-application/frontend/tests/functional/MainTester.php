<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class MainTester extends FunctionalTester
{
    public function amOnPage($url)
    {
        $page = \Yii::$app->getUrlManager()->createUrl($url);
        return parent::amOnPage($page);
    }
}