<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Asdra TM |
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('favicon.gif', 'img/favicon.gif', ['type' => 'icon']); ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <div class="container">
        <?= $this->Flash->render() ?>
        <!-- Starts Navbar -->
        <?= $this->element('navbar') ?>
        <!-- Ends Navbar -->
        <!-- Starts Content -->
        <?= $this->fetch('content') ?>
        <!-- Ends Content -->
    </div>
    <br><br><br>
    <!-- Javascript -->
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('bootstrap.js') ?>
    <?= $this->fetch('script') ?>
</body>
</html>
