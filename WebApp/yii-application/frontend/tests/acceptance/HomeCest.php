<?php

namespace frontend\tests\acceptance;

use AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(AcceptanceTester $I)
    {
        $I->amOnRoute(Url::toRoute('/site/index'));
        $I->see('My Application');

        $I->seeLink('About');
        $I->click('About');
        $I->wait(2); // wait for page to be opened

        $I->see('This is the About page.');
    }

    public function buyAndCheckout(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Activities', 'h1');
        $I->see('Buy', 'button');

        $I->click('Buy');
        $I->see('Enter Quantity', 'h1');

        $I->fillField('input[name="quantity"]', '3');
        $I->click('Submit');

        $I->see('Product Name');
        $I->see('Quantity:');
        $I->see('Checkout', 'button');

        $I->click('Checkout');

    }


}
