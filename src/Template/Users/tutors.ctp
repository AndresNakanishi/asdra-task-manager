<?php $title = 'Tutores';
$this->assign('title', $title);?>

<div class="row mt-3">
        <div class="col-lg-12">
            <div class="usersContainer">
                <div class="card">
                    <div class="card-body">
                        <div class="usersContainerHeader">
                            <h3 class="usersContainerHeaderTitle">Tutores</h3>
                            <?= $this->Form->create(null, ['class' => 'form-inline', 'url' => ['action' => 'tutors']]) ?>
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
                                    <a href="<?php echo $this->Url->build('/', true) ?>users/edit-tutor/<?= $user['id'] ?>" class="userCard2 d-flex flex-column">
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
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo $this->Url->build('/', true) ?>users/add-tutor" class="btn btn-primary border-dark"><i class="fas fa-plus-circle"></i> Agregar Tutor</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
