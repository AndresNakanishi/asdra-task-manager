<?php $this->assign('title', 'Nuevo Grupo de Tareas');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Agregar Grupo de Tareas</b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">	
		        <?= $this->Form->create($group, ['class' => 'col-lg-9','id' => 'addForm', 'type' => 'file']) ?>
			        <div class="form-group">
			            <?= $this->Form->control('title', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Título del Grupo de Tareas:',
			                ],
			                'required',
			                'placeholder' => 'Título',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('description', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Descripción:',
			                ],
			                'required',
			                'placeholder' => 'Descripción',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <!-- Foto -->
			        <div class="form-group">
			        	<div class="control-label">Imágen: </div>
	                    <div class="input-group">
	                      <div class="input-group-prepend">
	                        <span class="input-group-text" id="imageInput"><i class="fas fa-image"></i></span>
	                      </div>
	                      <div class="custom-file">
	                        <input type="file" class="custom-file-input" name="image" id="image" aria-describedby="imageInput">
	                        <label class="custom-file-label" for="image">Agregar una imágen al grupo de tareas</label>
	                      </div>
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