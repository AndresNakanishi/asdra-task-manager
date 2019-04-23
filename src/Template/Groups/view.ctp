<?php $this->assign('title', $group->title);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b><?= $group->title ?></b></h3>
            </div>
            <div class="card-body d-flex justify-content-center flex-column">	
            	<?php if ($group->image !== null or $group->image == ''): ?>
        			<img src="<?= $this->Url->build('/', true) ?><?= $group->image ?>" style="width:100%; max-width: 300px;" alt="<?= $group->title ?>">		
        			<hr>
        		<?php endif ?>	
		    	<?php if (count($tasks) !== 0): ?>
			    	<table class="table">
			    		<thead>
			    			<tr>
			    				<th>Orden</th>
			    				<th>Descripción</th>
			    				<th class="text-center">Acciones</th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			<?php foreach ($tasks as $task): ?>
			    				<tr>
			    					<td><?= $task->task_order ?></td>
			    					<td><?= $task->description_1 ?></td>
			    					<td class="text-right">
			    						<?= $this->Html->link(
		                                    'Ver', 
		                                    ['controller' => 'tasks', 'action' => 'view', $task->task_id], 
		                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
		                                ) ?>
		                                <?= $this->Html->link(
		                                    'Editar', 
		                                    ['controller' => 'tasks', 'action' => 'edit', $task->task_id], 
		                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
		                                ) ?>
		                                <?= $this->Form->postLink(
		                                    'Eliminar', 
		                                    ['controller' => 'tasks', 'action' => 'delete', $task->task_id], 
		                                    [
		                                        'confirm' => __('¿Está seguro que desea eliminar el grupo de tareas "{0}"?', $task->description_1),
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
		    		<h3>Agregue la primer tarea!</h3>	
		    	<?php endif ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
				<a href="<?= $this->Url->build('/', true) ?>tasks/add/<?= $group->group_id ?>" class="btn btn-primary border-dark mr-2"><i class="fas fa-plus-circle"></i> Agregar Tarea</a>  
				<a href="<?= $this->Url->build('/', true) ?>groups" class="btn border-dark">Volver</a>  
            </div>
        </div>
    </div>
</div>