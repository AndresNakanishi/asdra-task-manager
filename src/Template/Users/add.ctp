<?php $title = 'Agregar una Persona';
$this->assign('title', $title);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body profileContainer">
                <h4 class="profileContainer-title">Nuevo de Perfil</h4>
                <div class="profileContainer-content mt-3">
                    <div class="profileContainerEdit">            
                        <?= $this->Form->create($user, ['type' => 'file', 'id' => 'form', 'class' => 'profileContainer-form d-flex flex-wrap align-content-center']) ?>
                            <!-- Nombre Completo -->
                            <input class="col-lg-7 form-control" required name="name" type="text" placeholder="Nombre Completo">
                            <!-- Teléfono -->
                            <input class="col-lg-4 form-control" required name="phone" type="text" placeholder="Teléfono">
                            <!-- Dirección -->
                            <input class="col-lg-11 form-control mt-3" required name="address" type="text" placeholder="Dirección">
                            <!-- Foto -->
                            <div class="input-group col-lg-11 mt-3 p-0">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="photoInput"><i class="fas fa-user"></i></span>
                              </div>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" name="photo" id="photo" aria-describedby="photoInput">
                                <label class="custom-file-label" for="photo">Foto de la Persona</label>
                              </div>
                            </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark">Cancelar</a>    
                    <input form="form" type="submit" class="btn btn-success border-dark mr-3" value="Guardar">    
                </div>
            </div>
        </div>
    </div>
</div>