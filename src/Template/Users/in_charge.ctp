<?php $title = 'Personas a Cargo';
$this->assign('title', $title);?>

<div class="row mt-3">
        <div class="col-lg-12">
            <div class="usersContainer">
                <div class="card">
                    <div class="card-body">
                        <div class="usersContainerHeader">
                            <h3 class="usersContainerHeaderTitle">Personas a Cargo</h3>
                            <?= $this->Form->create(null, ['class' => 'form-inline', 'url' => ['action' => 'inCharge']]) ?>
                            <?php if (isset($filter)): ?>
                                <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" value="<?= $filter ?>" style="position: relative; vertical-align: top;">
                            <?php else: ?>
                                <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" style="position: relative; vertical-align: top;">
                            <?php endif ?>
                            <?= $this->Form->end() ?>
                        </div>
                        <div class="usersContainerItemsLarge">
                            <?php if ($users == null): ?>
                                Aún no tenes usuarios
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <a href="<?php echo $this->Url->build('/', true) ?>person/edit/<?= $user['id'] ?>" class="userCard2 d-flex flex-column">
                                        <?php if (strlen($user['photo']) > 80): ?>
                                              <img class="usersContainerItemsImg rounded-circle" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                                        <?php else: ?>
                                            <img class="usersContainerItemsImg rounded-circle" src="<?= $this->Url->build('/', true) ?><?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                                        <?php endif ?>
                                        <p class="usersContainerItemsName">
                                            <?= $user['name'] ?>
                                        </p>
                                    </a>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                        <?php if ($userProfileCode == 'ADM'): ?>
                            <div class="d-flex justify-content-end">
                                <a href="<?php echo $this->Url->build('/', true) ?>person/add" class="btn btn-primary border-dark"><i class="fas fa-plus-circle"></i> Agregar Persona</a>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
