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
    <?= $this->Html->css('animate.css') ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('select/select2.css') ?>
    <?= $this->Html->css('tempus/tempus.css') ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <!-- Javascript -->
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <div class="container">
        <!-- Starts Navbar -->
        <?= $this->element('navbar') ?>
        <!-- Ends Navbar -->
        <!-- Starts Content -->
        <?= $this->fetch('content') ?>
        <!-- Ends Content -->
    </div>
    <br><br><br>
    <script type="text/javascript" src=""></script>
    <?= $this->Html->script('bootstrap.js') ?>
    <?= $this->Html->script('moment.js') ?>
    <?= $this->Html->script('tempus/tempus.js') ?>
    <?= $this->Html->script('bootstrap-notify.js') ?>
    <?= $this->Html->script('select/select2.min.js') ?>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <?= $this->fetch('script') ?>
    <?= $this->Flash->render() ?>
</body>
</html>
