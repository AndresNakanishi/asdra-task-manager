<?php $this->assign('title', $task->description_1);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b><?= $task->group->title ?>: <?= $task->description_1 ?></b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">
            	<?php if (count($steps) !== 0): ?>
			    	<table class="table">
			    		<thead>
			    			<tr>
			    				<th>Orden</th>
			    				<th>Título</th>
			    				<th class="text-center">Acciones</th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			<?php foreach ($steps as $step): ?>
			    				<tr>
			    					<td><?= $step->step_order ?></td>
			    					<td><?= $step->title ?></td>
			    					<td class="text-right">
			    						<?= $this->Html->link(
		                                    'Ver', 
		                                    ['controller' => 'steps', 'action' => 'view', $step->step_id], 
		                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
		                                ) ?>
		                                <?= $this->Html->link(
		                                    'Editar', 
		                                    ['controller' => 'steps', 'action' => 'edit', $step->step_id], 
		                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
		                                ) ?>
		                                <?= $this->Form->postLink(
		                                    'Eliminar', 
		                                    ['controller' => 'steps', 'action' => 'delete', $step->step_id], 
		                                    [
		                                        'confirm' => __('¿Está seguro que desea eliminar el paso "{1}" de la tarea "{0}"?', $task->description_1, $step->title),
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
					<h3>Agregue el primer paso!</h3>            			
        		<?php endif ?>	
            </div>
            <div class="card-footer d-flex justify-content-end">
				<a href="<?= $this->Url->build('/', true) ?>steps/add/<?= $task->task_id ?>" class="btn btn-primary border-dark mr-2"><i class="fas fa-plus-circle"></i> Agregar Paso</a>  
				<a href="<?= $this->Url->build('/', true) ?>groups/view/<?= $task->group->group_id ?>" class="btn border-dark">Volver</a>  
            </div>
        </div>
    </div>
</div>