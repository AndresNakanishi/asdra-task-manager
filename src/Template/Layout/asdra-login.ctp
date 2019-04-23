<!doctype html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        ASDRA |
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('favicon.gif', 'img/favicon.gif', ['type' => 'icon']); ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('animate.css') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body class="text-center d-flex justify-content-center align-items-center" style="height: 100vh">
    <!-- Content -->
    <?= $this->fetch('content') ?>
    <!-- Javascript -->
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('bootstrap-notify.js') ?>
    <?= $this->Html->script('bootstrap.js') ?>
    <?= $this->fetch('script') ?>
    <?= $this->Flash->render() ?>
</body>
</html>
