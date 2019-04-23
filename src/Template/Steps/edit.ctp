<?php $this->assign('title', 'Editar Paso');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Editar Paso</b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">	
		        <?= $this->Form->create($step, ['class' => 'col-lg-9','id' => 'addForm', 'type' => 'file']) ?>
			        <div class="form-group">
			            <?= $this->Form->control('title', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Título:',
			                ],
			                'required',
			                'placeholder' => 'Título',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('sub_title', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Subtítulo:',
			                ],
			                'required',
			                'placeholder' => 'Subtítulo',
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <div class="form-group">
			            <?= $this->Form->control('step_order', [
			                'class' => 'form-control',
			                'label' => [
			                    'class' => 'control-label',
			                    'text' => 'Orden:',
			                ],
			                'required',
			                'placeholder' => 'Orden (Cantidad de Pasos: '.$steps.')',
			                'min' => 1,
			                'max' => $steps,
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
			        <!-- Foto -->
			        <div class="form-group">
			        	<div class="control-label">Imágen: </div>
	                    <div class="input-group">
	                      <div class="input-group-prepend">
	                        <span class="input-group-text" id="photoInput"><i class="fas fa-image"></i></span>
	                      </div>
	                      <div class="custom-file">
	                        <input type="file" class="custom-file-input" name="photo" id="photo" aria-describedby="photoInput">
	                        <label class="custom-file-label" for="photo">Agregar una imágen al paso</label>
	                      </div>
	                    </div>
                    </div>
		        <?= $this->Form->end() ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Editar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm'
            	]) ?>
				<a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark">Cancelar</a>  
            </div>
        </div>
    </div>
</div>