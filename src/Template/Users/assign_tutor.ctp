<?php $this->assign('title', 'Asignar Tutor');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Agregar Grupo de Tareas</b></h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">	
  		        <?= $this->Form->create(null, ['class' => 'col-lg-9','id' => 'addForm']) ?>
                <input type="text" id="user_id" name="user_id" value="<?= $user->user_id ?>" style="display: none">
                <div class="form-group">
                  <label for="group_id">Tutor:</label>
                  <select id="supervisor" class="form-control group_id_select" name="supervisor_id">
                    <option value="-" disabled selected>Selecciona un tutor</option>
                    <?php foreach ($tutors as $tutor): ?>
                      <option value="<?= $tutor->user_id ?>"><?= $tutor->name ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group">
                    <label for="rol">Asignar Rol: </label>
                    <select name="rol" class="col-lg-12 form-control" required>     
                        <option disabled value="" selected>Rol</option>
                        <option value="FAM">Familiar</option>
                        <option value="CHF">Apoyo Natural</option>
                    </select>                  
                </div>
                <div class="form-group">
                    <label for="company">Compañia: </label>
                    <select id="company" class="col-lg-12 form-control" name="company">
                        <option value="" selected disabled>Compañia</option>
                    </select>
                </div>
  		        <?= $this->Form->end() ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Agregar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm',
                	'id' => 'accept-button'
            	]) ?>
				<a href="<?= $this->Url->build('/', true) ?>person/edit/<?= $user->user_id ?>" class="btn btn-danger border-dark">Cancelar</a>  
            </div>
        </div>
    </div>
</div>
<script>
  // Select 2
  $(document).ready(function() {
    $('.group_id_select').select2();
  });

  $('#supervisor').on('change', function () {
        var id = $(this).val();
        $.ajax({
            type: 'GET',
            async: true,
            cache: false,
            url: '<?php echo $this->Url->build('/', true) ?>users/getusercompany/'+id,
            success: function (data) {
                $("#company").html(data);
            }
        });
    });
</script>