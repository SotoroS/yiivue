<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 10:07
 */

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => 'Трипл',
    'brandUrl' => '#',
    'options' => [
        'class' => 'navbar-dark bg-dark',
    ]
]);

$items = [];

if (!Yii::$app->user->isGuest) {
    $items[] = '<li>'
        . Html::beginForm(['/auth/logout'], 'post')
        . Html::submitButton(
            'Выйти',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
} else {
    $items[] = ['label' => 'Войти', 'url' => ['/auth/login']];
    $items[] = ['label' => 'Регистрация', 'url' => ['/auth/signup']];
    $items[] = ['label' => 'Восстановление пароля', 'url' => ['/auth/request-password-reset']];
}

echo Nav::widget([
    'items' => $items,
    'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();
?>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
