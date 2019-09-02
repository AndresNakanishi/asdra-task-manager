<?php $this->assign('title', $step->title);?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3>
	            	<b>
	            		Paso <?= $step->step_order ?>: <?= $step->title ?>
	            	</b>
	            </h3>
            </div>
            <div class="card-body d-flex flex-column">
            	<?php if ($step->photo !== null or $step->photo == ''): ?>
            		<img src="<?= $step->photo ?>" style="width:100%; max-width: 300px;" alt="<?= $step->title ?>">
        			<hr>		
        		<?php endif ?>	
			    <h5 class="card-title"><?= $step->title ?></h5>
			    <p class="card-text"><?= $step->sub_title ?></p>
			    <hr>
			    <?php if (count($stepOptions) !== 0): ?>
			    	<table class="table">
			    		<thead>
			    			<tr>
			    				<th>Orden</th>
			    				<th>Paso Siguiente</th>
			    				<th>Opción</th>
			    				<th class="text-center">Acciones</th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			<?php foreach ($stepOptions as $option): ?>
			    				<tr>
			    					<td><?= $option->option_order ?></td>
			    					<td><?= $option->next_step ?></td>
			    					<td><?= $option->option_description ?></td>
			    					<td class="text-right">
		                                <?= $this->Form->postLink(
		                                    'Eliminar', 
		                                    ['controller' => 'StepsOptions', 'action' => 'delete', $option->step_id, $option->option_order ], 
		                                    [
		                                        'confirm' => __('¿Está seguro que desea eliminar la opción "{0}"?', $option->option_description),
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
			    	<h3>Este paso no tiene opciones de momento...</h3>
			    <?php endif ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
				<a href="<?= $this->Url->build('/', true) ?>steps-options/add/<?= $step->step_id ?>" class="btn btn-primary border-dark mr-2"><i class="fas fa-plus-circle"></i> Agregar Opción</a>  
				<a href="<?= $this->Url->build('/', true) ?>tasks/view/<?= $step->task_id ?>" class="btn border-dark">Volver</a>  
            </div>
        </div>
    </div>
</div>