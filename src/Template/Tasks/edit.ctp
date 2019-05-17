<?php $this->assign('title', 'Editar Tarea');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Editar Tarea</b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">	
		        <?= $this->Form->create($task, ['class' => 'col-lg-9','id' => 'addForm', 'type' => 'file']) ?>
			        <div class="form-group">
			            <?= $this->Form->control('description_1', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Descripción 1:',
			                ],
			                'required',
			                'placeholder' => 'Descripción 1',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('description_2', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Descripción 2:',
			                ],
			                'placeholder' => 'Descripción 2',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('task_order', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Orden:',
			                ],
			                'required',
			                'placeholder' => 'Orden (Cantidad de Tareas: '.($tasks).')',
			                'min' => 1,
			                'max' => $tasks,
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
		        <?= $this->Form->end() ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Guardar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm'
            	]) ?>
				<a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark">Cancelar</a>  
            </div>
        </div>
    </div>
</div>