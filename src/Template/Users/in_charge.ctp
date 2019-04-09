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
                                    <a href="<?php echo $this->Url->build('/', true) ?>person/<?= $user['id'] ?>" class="userCard2 d-flex flex-column">
                                        <img class="usersContainerItemsImg rounded-circle" src="<?= $user['photo'] ?>" alt="User">
                                        <p class="usersContainerItemsName">
                                            <?= $user['name'] ?>
                                        </p>
                                    </a>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
