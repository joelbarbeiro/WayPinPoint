<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\models\Category;
use common\widgets\Alert;
use frontend\assets\TemplateAsset;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

TemplateAsset::register($this);
$this->registerCssFile('@web/css/site.css', ['depends' => [BootstrapAsset::class]]);

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">


        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">


        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php

        NavBar::begin([
            'brandLabel' => Html::tag(
                'div',
                Html::a(
                    Html::img('@web/assets/logo/waypinpointFRONT.png', ['alt' => 'Logo', 'class' => 'logo-class']),
                    Yii::$app->homeUrl,
                    ['class' => 'text-decoration-none']
                ),
                ['class' => 'navbar-brand', 'style' => 'margin-right: 100px;']
            ),
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark',
            ],
        ]);

        $categories = Category::find()->select(['id', 'description'])->asArray()->all();

        $categories[0] = ['id' => 0, 'description' => 'All Activities'];

        $categoryDropdownItems = [];
        foreach ($categories as $category) {
            $categoryDropdownItems[] = [
                'label' => $category['description'],
                'url' => ['/activity/index', 'ActivitySearch[category_id]' => $category['id']],
            ];
        }

        $menuItems[] = [
            'label' => 'Categories',
            'options' => ['class' => 'nav-item dropdown'],
            'items' => $categoryDropdownItems, 'All',
            'dropdownOptions' => [
                'class' => 'dropdown-menu custom-dropdown-menu', // Add custom class
            ],
        ];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        }

        echo '<div class="container-fluid">';
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'], // Left aligned items
            'items' => $menuItems,
        ]);

        // Search bar
        echo Html::beginForm(['/activity/index'], 'get', ['class' => 'form-inline me-2']);
        echo Html::textInput('ActivitySearch[search]', '', ['class' => 'form-control', 'placeholder' => 'Search for Activities']);
        echo Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-outline-primary']);
        echo Html::endForm();

        // Other user actions
        echo Html::a(
            '<i class="fa fa-shopping-cart"></i>',
            ['/cart/index'],
            ['class' => 'btn btn-outline-primary me-2']
        );

        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            echo Html::a(
                '<i class="fa fa-user-alt"></i>',
                ['/user/view', 'id' => $userId],
                ['class' => 'btn btn-outline-primary']
            );
        }

        if (Yii::$app->user->isGuest) {
            echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => 'btn btn-link text-decoration-none']), ['class' => 'ms-2']);
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex ms-2']) // Add margin-start
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout text-decoration-none']
                )
                . Html::endForm();
        }

        echo '</div>'; // End of user actions
        echo '</div>'; // End of container-fluid
        NavBar::end();
        ?>
    </header>


    <main role="main" class="flex-shrink-0" style="padding-top: 10px">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5 d-flex justify-content-between">
            <!-- About Us Section (on the left) -->
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">About us</h5>
                <p class="mb-4">We are students of Polytechnic Institute of Leiria studying Programming and Information
                    Systems</p>
                <p class="mb-2"><i class="fa fa-user-alt text-primary mr-3"></i>André Barroso</p>
                <p class="mb-2"><i class="fa fa-user-alt text-primary mr-3"></i>Joel Barbeiro</p>
                <p class="mb-0"><i class="fa fa-user-alt text-primary mr-3"></i>Pedro Lourenço</p>
            </div>

            <!-- My Account Section (on the right) -->
            <div class="col-lg-4 col-md-12">
                <h5 class="text-secondary text-uppercase mb-4">Redirect</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-secondary mb-2" href="<?= Url::to(['activity/index']) ?>"><i
                                class="fa fa-home mr-2"></i>Home</a>
                    <a class="text-secondary mb-2" href="<?= Url::to(['cart/index']) ?>"><i
                                class="fa fa-shopping-cart mr-2"></i>Your Cart</a>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; <a class="text-primary" href="#">Domain</a>. All Rights Reserved. Designed
                    by
                    <a class="text-primary" href="https://htmlcodex.com">HTML Codex</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();