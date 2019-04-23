<?php $this->assign('title', 'Nueva Opción');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Agregar Opción</b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">	
		        <?= $this->Form->create($stepsOption, ['class' => 'col-lg-9','id' => 'addForm']) ?>
			        <div class="form-group">
			            <?= $this->Form->control('next_step_id', [
			                'options' => $nextSteps,
			                'empty' => 'Seleccione la Próx. Paso',
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Próximo paso:'
			                ]
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('option_description', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Descripción de la Opción:',
			                ],
			                'required',
			                'placeholder' => 'Ejemplo: Si - No',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
				        <div class="input text required">
				        	<label class="control-label" for="option_order">Order de la opción:</label>
				        	<input type="number" name="option_order" class="form-control" required="required" placeholder="Orden (Cantidad de Opciones: <?= ($stepsOptions + 1)?>)" autocomplete="off" min="1" max="<?= ($stepsOptions + 1) ?>" id="option_order">
				        </div>
			        </div>
		        <?= $this->Form->end() ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Agregar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm'
            	]) ?>
				<a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark">Cancelar</a>  
            </div>
        </div>
    </div>
</div>