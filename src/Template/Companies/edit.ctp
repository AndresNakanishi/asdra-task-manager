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
                                <input class="col-lg-12 form-control mt-3" required name="company_name" type="text" value="<?= $company->company_name ?>" placeholder="Nombre de la Compañía">
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
                <div class="col-lg-12 d-flex align-items-center mt-3">
                    <?= $this->Form->postLink(
                        "¿Desea eliminar a $company->company_name de la base de datos?", 
                        ['action' => 'delete', $company->company_id], 
                        [
                            'confirm' => __('¿Está seguro que eliminar a "{0}" de la base de datos?', $company->company_name),
                            'class' => 'link'
                        ]
                    ) ?> 
                    <div class="d-flex">
                        <a href="<?= $this->Url->build('/', true) ?>companies" class="btn btn-danger border-dark ml-3">Cancelar</a>    
                        <input form="form" type="submit" class="btn btn-success border-dark ml-3" value="Guardar">    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
