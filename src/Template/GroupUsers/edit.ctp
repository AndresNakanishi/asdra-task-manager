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
	'VI' => 'Viernes',
	'SA' => 'Sábado',
	'DO' => 'Domingo'
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
			        <div class="row">
			        	<div class="col-lg-12">
			        		<p><strong>Rango horario de ejecución del grupo de tareas: </strong></p>
			        	</div>
				        <div class="form-group col-lg-12">
				        	<label for="repetition">Repetición:</label>
							<?= $this->Form->select('repetition', $repetition, ['default' => '', 'class' => 'form-control', 'id' => 'repetition']);?>
				        </div>
				        <div class="form-group col-lg-12" id="alert">
					        <div class="alert alert-success" role="alert">
							  Las tareas mensuales se ejecutan sobre la <b>fecha desde</b>.
							</div>
				        </div>
				        <div class="form-group col-lg-12">
				        	<label for="rep_days">Días:</label>
							<?= $this->Form->select('rep_days[]', $days, [
								'multiple',
								'class' => 'form-control rep-days',
								'value' => $selected
							]);?>
				        </div>
				        <div class="form-group col-lg-6">
				        	<label for="start-time">Desde:</label>
			                <div class="input-group date" id="start-time" data-target-input="nearest">
			                    <input type="text" name="start_time" class="form-control datetimepicker-input" data-target="#start-time" placeholder="HH:MM" value="<?= date('H:i', strtotime($groupUser->start_time)); ?>" required/>
			                    <div class="input-group-append" data-target="#start-time" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="far fa-clock"></i></div>
			                    </div>
			                </div>
				        </div>
				        <div class="form-group col-lg-6">
				        	<label for="end-time">Hasta:</label>
			                <div class="input-group date" id="end-time" data-target-input="nearest">
			                    <input type="text" name="end_time" class="form-control datetimepicker-input" data-target="#end-time" placeholder="HH:MM" value="<?= date('H:i', strtotime($groupUser->end_time)); ?>" required/>
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
			                    <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start-date" value="<?= date('d/m/Y', strtotime($groupUser->date_from)); ?>" placeholder="DD/MM/AAAA" required/>
			                    <div class="input-group-append" data-target="#start-date" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                    </div>
			                </div>
				        </div>
				        <div class="form-group col-lg-6">
				        	<label for="end-date">Hasta:</label>
				        	<div class="input-group date" id="end-date" data-target-input="nearest">
			                    	<input type="text" name="end_date" class="form-control datetimepicker-input" data-target="#end-date" value="<?php if($groupUser->date_to !== null){ echo date('d/m/Y', strtotime($groupUser->date_to)); }?>" placeholder="DD/MM/AAAA"/>
				        	
			                    <div class="input-group-append" data-target="#end-date" data-toggle="datetimepicker">
			                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
			                    </div>
			                </div>
				        </div>
			        </div>
		        <?= $this->Form->end() ?>
            </div>
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Guardar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm'
            	]) ?>
				<a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user_id ?>/<?= $groupUser->group_type_id?>" class="btn btn-danger border-dark">Cancelar</a>  
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

	let repetition = document.querySelector("#repetition");
	let alert = document.querySelector("#alert");

	let index = document.querySelector("#repetition").selectedIndex; 
    let value = document.querySelector("#repetition").options.item(index).text;
    checkstatus(value);
    
    repetition.addEventListener("change", () => {
        index = document.querySelector("#repetition").selectedIndex; 
        value = document.querySelector("#repetition").options.item(index).text;
        checkstatus(value);
    });

    function checkstatus(value){
        switch(value) {
            case 'Repetición Semanal':
                alert.style.display = 'none';
                break;
            case 'Repetición Mensual':
                alert.style.display = 'block';
                break;
            default:
                alert.style.display = 'block';
        }
    }

	$(function () {
		// Time
		// Start Time
        $('#start-time').datetimepicker({
            format:'HH:mm'
        });
    	// End Time
	    $('#end-time').datetimepicker({
            format:'HH:mm'
        });
		// End Time
		// Date
		// Start Time
        $('#start-date').datetimepicker({
        	default: false,
        	locale: 'es',
            format: 'DD/MM/YYYY'
        });
		$('#end-date').datetimepicker({
        	default: false,
        	useCurrent: false,
        	locale: 'es',
            format: 'DD/MM/YYYY'
        });
    });
</script>