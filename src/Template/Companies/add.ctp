<?php $title = 'Agregar una Compañia';
$this->assign('title', $title);?>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body profileContainer">
                <h4 class="profileContainer-title"><?= $title ?></h4>
                <div class="profileContainer-content mt-3">
                    <div class="profileContainerEdit">            
                        <?= $this->Form->create($company, ['type' => 'file', 'id' => 'form', 'class' => 'profileContainer-form d-flex flex-wrap align-content-center']) ?>
                            <div class="form-group col-lg-12">
                                <label for="company_name"></label>
                                <input class="col-lg-12 form-control mt-3" required name="company_name" type="text" maxlength="25"placeholder="Nombre de la Compañía">
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
                <div class="col-lg-12 d-flex align-items-center mt-3">
                    <a href="<?= $this->Url->build('/', true) ?>companies" class="btn btn-danger border-dark">Cancelar</a>    
                    <input form="form" type="submit" class="btn btn-success border-dark ml-3" value="Guardar">    
                </div>
            </div>
        </div>
    </div>
</div>
