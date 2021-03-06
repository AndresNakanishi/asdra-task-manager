<?php $title = 'Dashboard';
$this->assign('title', $title);?>

<!-- Usuarios con tareas atrasadas -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body d-flex align-items-end flex-column">
                <?= $this->Form->create(null, ['class' => 'form-inline', 'url' => ['action' => 'dashboard']]) ?>
                    <?php if (isset($filter)): ?>
                        <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" value="<?= $filter ?>" style="position: relative; vertical-align: top;">
                    <?php else: ?>
                        <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" style="position: relative; vertical-align: top;">
                    <?php endif ?>
                <?= $this->Form->end() ?>
                <div class="updateContainerHeader d-flex justify-content-between align-items-center mt-3">
                    <h5><?= date('d/m/Y'); ?> - Con tareas atrasadas a la <?= date('H:i A'); ?></h5>
                    <a class="updateContainer-iconSize" href="#" onclick="location.reload();"><i class="fas fa-sync-alt"></i></a>
                </div>
                <div class="updateContainerData">
                    <?php if (!empty($users['withPendingTasks'])): ?>
                        <?php foreach ($users['withPendingTasks'] as $user): ?>
                            <div class="updateContainerDataItem">
                                <a href="<?= $this->Url->build('/', true) ?>person/edit/<?= $user['id'] ?>" class="updateContainerDataItemUserData text-dark">
                                    <div class="updateContainerDataItemUserDataImg">
                                        <img class="user-image rounded-circle" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                                    </div>
                                    <div class="updateContainerDataItemUserDataContainer">
                                        <div class="updateContainerData-header">
                                            <p class="updateContainerData-username"><?= $user['name'] ?></p>
                                            <p class="updateContainerData-business">(Empresa: <?= $user['company'] ?>)</p>
                                        </div>
                                        <p class="updateContainerData-lastTask"><?= count($user['tasks']) ?> Tareas Pendientes</p>
                                    </div>
                                </a>
                                <div class="updateContainerDataItemActions">
                                    <a class="updateContainer-iconSize" href="<?= $this->Url->build('/', true) ?>person/pending-tasks/<?= $user['id'] ?>"><i class="fas fa-bars"></i></a>
                                    <a class="updateContainer-iconSize disabled" title="Llamar" href="tel:<?= $user['phone'] ?>"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php else: ?>
                        No hay personas con tareas pendientes
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Usuarios con Tareas Atrasadas -->


<!-- Búsqueda de Users -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="usersContainer">
            <div class="card">
                <div class="card-body">
                    <div class="usersContainerHeader">
                        <h3 class="usersContainerHeaderTitle">Con tareas al día</h3>
                    </div>
                    <div class="usersContainerItems">
                        <?php if (!empty($users['withoutPendingTasks'])): ?>
                            <?php foreach ($users['withoutPendingTasks'] as $user): ?>
                                <a href="<?= $this->Url->build('/', true) ?>person/edit/<?= $user['id'] ?>" class="userCard d-flex flex-column text-dark">
                                    <img class="usersContainerItemsImg rounded-circle" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                                    <p class="usersContainerItemsName">
                                        <?= $user['name'] ?>
                                    </p>
                                </a>
                            <?php endforeach ?>
                        <?php else: ?>
                            No hay usuarios con tareas al día
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Búsqueda de Users -->
