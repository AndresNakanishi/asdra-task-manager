<?php $this->assign('title', 'Tareas para : '. $user->name);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3><b><?= $groupType->description.' para: '. $user->name ?></b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">
                <?php if (count($groupUsers) !== 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Grupo de Tareas</th>
                                <th>Repetición</th>
                                <th>Ventana de Ejecución</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupUsers as $tasks): ?>
                                <tr>
                                    <td><?= $tasks->group->title ?></td>
                                    <td><?= $tasks->repetition ?></td>
                                    <td><?= date('H:i',strtotime($tasks->start_time)).'hs. a '.date('H:i',strtotime($tasks->end_time)).'hs.' ?></td>
                                    <td class="text-right">
                                        <?= $this->Html->link(
                                            'Editar', 
                                            ['action' => 'edit', $tasks->group_id, $tasks->user_id], 
                                            ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            'Eliminar', 
                                            ['controller' => 'GroupUsers', 'action' => 'delete', $tasks->group_id, $tasks->user_id], 
                                            [
                                                'confirm' => __('¿Está seguro que desea eliminar el grupo de tareas "{0}"?', $tasks->group->title),
                                                'style' => 'padding-right: 10px;',
                                                'class' => 'btn btn-sm btn-danger border-dark'
                                            ]
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h3>Agregue el primer grupo de tareas para <?= $user->name ?>!</h3>                        
                <?php endif ?>  
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a href="<?= $this->Url->build('/', true) ?>group-users/add/<?= $user->user_id ?>/<?= $groupType->group_type_id ?>" class="btn btn-primary border-dark mr-2"><i class="fas fa-plus-circle"></i> Agregar Grupo de Tareas</a>  
                <a href="<?= $this->Url->build('/', true) ?>person/edit/<?= $user->user_id ?>/<?= $groupType->group_type_id ?>" class="btn border-dark">Volver</a>  
            </div>
        </div>
    </div>
</div>