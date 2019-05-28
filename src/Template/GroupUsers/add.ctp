<?php 
$this->assign('title', 'Nuevo Grupo de Tareas');

$repetition = [
	"SEM" => "Repetición Semanal",
	"MES" => "Repetición Mensual"
];

$days = [
	'TODOS' => 'TODOS',
	'LU' => 'Lunes',
	'MA' => 'Martes',
	'MI' => 'Miércoles',
	'JU' => 'Jueves',
	'VI' => 'Viernes'
];
?>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Agregar Grupo de Tareas</b></h3>
            </div>
            <div class="card-body d-flex justify-content-center">	
		        <?= $this->Form->create($groupUser, ['autocomplete' => 'off','class' => 'col-lg-9','id' => 'addForm']) ?>
			        <div class="form-group">
			        	<label for="group_id">Grupo de Tareas:</label>
						<select class="form-control group_id_select" name="group_id">
							<option value="-" disabled selected>Selecciona un grupo de tareas</option>
							<?php foreach ($groups as $value => $group): ?>
								<option value="<?= $value ?>"><?= $group ?></option>
							<?php endforeach ?>
						</select>
			        </div>
			        <div class="form-group">
			        	<label for="repetition">Repetición:</label>
						<?= $this->Form->select('repetition', $repetition, ['default' => '', 'class' => 'form-control']);?>
			        </div>
			        <div class="form-group">
			        	<label for="rep_days">Días:</label>
						<?= $this->Form->select('rep_days[]', $days, ['multiple' ,'class' => 'form-control rep-days']);?>
			        </div>
			        <div class="row">
			        	<div class="col-lg-12">
			        		<p><strong>Rango horario de ejecución del grupo de tareas: </strong></p>
			        	</div>
				        <div class="form-group col-lg-6">
				        	<label for="start-time">Desde:</label>
			                <div class="input-group date" id="start-time" data-target-input="nearest">
			                    <input type="text" name="start_time" class="form-control datetimepicker-input" data-target="#start-time" placeholder="HH:MM" required/>
			                    <div class="input-group-append" data-target="#start-time" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="far fa-clock"></i></div>
			                    </div>
			                </div>
				        </div>
				        <div class="form-group col-lg-6">
				        	<label for="end-time">Hasta:</label>
			                <div class="input-group date" id="end-time" data-target-input="nearest">
			                    <input type="text" name="end_time" class="form-control datetimepicker-input" data-target="#end-time" placeholder="HH:MM" required/>
			                    <div class="input-group-append" data-target="#end-time" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="far fa-clock"></i></div>
			                    </div>
			                </div>
				        </div>
			        </div>
			        <div class="row">
			        	<div class="col-lg-12">
			        		<p><strong>Fechas entre las cuales la tarea es válida: </strong></p>
			        	</div>
				        <div class="form-group col-lg-6">
				        	<label for="start-date">Desde:</label>
				        	<div class="input-group date" id="start-date" data-target-input="nearest">
			                    <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start-date" placeholder="DD/MM/AAAA" />
			                    <div class="input-group-append" data-target="#start-date" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                    </div>
			                </div>
				        </div>
				        <div class="form-group col-lg-6">
				        	<label for="end-date">Hasta:</label>
				        	<div class="input-group date" id="end-date" data-target-input="nearest">
			                    <input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#end-date" placeholder="DD/MM/AAAA"/>
			                    <div class="input-group-append" data-target="#end-date" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                    </div>
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
				<a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user_id ?>/<?= $group_type ?>" class="btn btn-danger border-dark">Cancelar</a>  
            </div>
        </div>
    </div>
</div>
<script>
	// Select 2
	$(document).ready(function() {
	    $('.group_id_select').select2();
		
		$(".rep-days").select2({
		    multiple: true
		});
	});


	// Time

		// Start Time
		$(function () {
	        $('#start-time').datetimepicker({
	            format:'HH:mm'
	        });
	    });
	    $("#start-time").on("change.datetimepicker", function (e) {
	        $('#end-time').datetimepicker('minDate', e.date);
	    });

	    // End Time
		$(function () {
	        $('#end-time').datetimepicker({
	            format:'HH:mm'
	        });
	    });
	    $("#end-time").on("change.datetimepicker", function (e) {
	        $('#start-time').datetimepicker('maxDate', e.date);
	    });

	// End Time

	// Date

		// Start Time
		$(function () {
	        $('#start-date').datetimepicker({
	        	format: 'DD/MM/YYYY'
	        });
	    });

	    // End Time
		$(function () {
	        $('#end-date').datetimepicker({
	            format: 'DD/MM/YYYY'
	        });
	    });
	    $("#end-date").on("change.datetimepicker", function (e) {
	        $('#start-date').datetimepicker('maxDate', e.date);
	    });

</script>