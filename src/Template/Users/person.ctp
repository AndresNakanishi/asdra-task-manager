<?php $this->assign('title', $user->name);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body profileContainer">
                <h4 class="profileContainer-title">Administración de Perfil</h4>
                <div class="profileContainer-content mt-3">
                    <div class="profileContainerEdit">            
                        <?= $this->Form->create($user, ['type' => 'file', 'id' => 'form', 'class' => 'profileContainer-form d-flex flex-wrap align-content-center', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->user_id]]) ?>

                            <input type="text" id="avatar-code" name="avatar-code" style="display: none">
                            <?=$this->Form->hidden('photo_2', ['id' => 'agent-photo']); ?>
                            <!-- Nombre Completo -->
                            <input class="col-lg-7 form-control" required name="name" type="text" placeholder="Nombre Completo" value="<?= $user->name ?>">
                            <!-- Teléfono -->
                            <input class="col-lg-4 form-control" required name="phone" type="text" placeholder="Teléfono" value="<?= $user->phone ?>">
                            <!-- Dirección -->
                            <input class="col-lg-11 form-control mt-3" required name="address" type="text" placeholder="Dirección" value="<?= $user->address ?>">
                            <!-- Foto -->
                            <div class="input-group col-lg-11 mt-3 p-0">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="photoInput"><i class="fas fa-user"></i></span>
                              </div>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" name="photo" id="photo" aria-describedby="photoInput">
                                <label class="custom-file-label" for="photo">Cambiar Foto de la Persona</label>
                              </div>
                            </div>
                        <?= $this->Form->end() ?>
                        <!-- Imagen -->
                        <div class="text-center">
                            <?php if (strlen($user['photo']) > 80): ?>
                                  <img class="rounded-circle" height="200" width="200" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                            <?php else: ?>
                                <img class="rounded-circle" height="200" width="200" src="<?= $this->Url->build('/', true) ?><?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                            <?php endif ?>                        
                        </div>    
                    </div>
                </div>
                <div class="tutorTable mt-2">                    
                    <table class="table text-center">
                        <tbody>
                            <?php if (isset($supervisors)): ?>
                                <?php foreach ($supervisors as $super): ?>
                                    <tr>
                                        <td><?= $super['name'] ?></td>
                                        <td><?= $super['phone'] ?></td>
                                        <?php if ($super['role'] == 'TUT'): ?>
                                            <td><?= $super['role'] ?></td>
                                            <td>                    
                                                <a href="#" class="btn btn-info btn-sm disabled border-dark">Reasignar</a>
                                            </td>
                                        <?php else: ?>
                                            <td><?= $super['role'] ?></td>
                                            <td>                    
                                                <?= $this->Form->postLink(
                                                    'Eliminar', 
                                                    ['action' => 'deleteTutor', $super['id'], $user->user_id], 
                                                    [
                                                        'confirm' => __('¿Está seguro que eliminar a "{0}" como tutor?', $super['name']),
                                                        'class' => 'btn btn-sm btn-danger border-dark'
                                                    ]
                                                ) ?>  
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">
                                        No tiene tutores
                                    </td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <a href="<?= $this->Url->build('/', true) ?>tutor/add/<?= $user->user_id?>" class="btn btn-primary border-dark ml-3"><i class="fas fa-plus-circle"></i> Agregar Tutores</a>
                </div>
                <div class="profileContainerTasks mt-3">
                    <h6>Tipos de Tareas Asignadas</h6>
                    <div class="profileContainerTasks-tasks">
                        <!-- Group Type 1 ==> Tareas del trabajo  -->
                        <a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user->user_id?>/1" class="task text-center">
                            <div class="icon rounded-circle">
                                <i class="icon-fontawesome fas fa-wrench"></i>
                            </div>
                            <p class="mt-3">En el trabajo</p>
                        </a>
                        <!-- Group Type 2  ==> Tareas de la casa -->
                        <a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user->user_id?>/2" class="task text-center">
                            <div class="icon rounded-circle">
                                <i class="icon-fontawesome fas fa-home"></i>
                            </div>
                            <p class="mt-3">En casa</p>
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <?= $this->Form->postLink(
                        "¿Desea eliminar a $user->name de la base de datos?", 
                        ['action' => 'delete', $user->user_id], 
                        [
                            'confirm' => __('¿Está seguro que eliminar a "{0}" como tutor?', $super['name']),
                            'class' => 'link'
                        ]
                    ) ?> 
                    <div class="d-flex">
                        <a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark mr-3">Cancelar</a>    
                        <input form="form" type="submit" class="btn btn-success border-dark" value="Confirmar Cambios">    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->css(['/plugins/jcrop/jquery.Jcrop.min']) ?>
<?= $this->Html->script(['/plugins/jcrop/jquery.Jcrop.min']) ?>