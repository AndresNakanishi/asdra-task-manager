<?php $title = 'Compañias';
$this->assign('title', $title);?>

<div class="row mt-3">
        <div class="col-lg-12">
            <div class="usersContainer">
                <div class="card">
                    <div class="card-body">
                        <div class="usersContainerHeader">
                            <h3 class="usersContainerHeaderTitle">Compañias</h3>
                            <?= $this->Form->create(null, ['class' => 'form-inline', 'url' => ['action' => 'index']]) ?>
                            <?php if (isset($filter)): ?>
                                <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" value="<?= $filter ?>" style="position: relative; vertical-align: top;">
                            <?php else: ?>
                                <input type="search" class="form-control usersContainerSearch" id="search-input" name="filter" placeholder="Buscar..." autocomplete="off" style="position: relative; vertical-align: top;">
                            <?php endif ?>
                            <?= $this->Form->end() ?>
                        </div>
                        <div class="usersContainerItemsLarge">
                            <?php if ($companies == null): ?>
                                Aún no tenes usuarios
                            <?php else: ?>
                                <?php foreach ($companies as $company): ?>
                                    <a href="<?php echo $this->Url->build('/', true) ?>companies/edit/<?= $company['id']?>" class="userCard2 d-flex flex-column">
                                        <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=0D8ABC&color=fff&name=<?= $company['name']?>" alt="<?= $company['name']?>">
                                        <p class="usersContainerItemsName">
                                            <?= $company['name']?>
                                        </p>
                                    </a>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo $this->Url->build('/', true) ?>companies/add" class="btn btn-primary border-dark"><i class="fas fa-plus-circle"></i> Agregar Compañia</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
