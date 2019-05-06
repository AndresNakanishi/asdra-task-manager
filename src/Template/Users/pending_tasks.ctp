<?php $this->assign('title', 'Tareas Pendientes');?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Tareas Pendientes de <?= $user->name ?></b></h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">	
		        <table class="table text-center">
                    <thead>
                    	<tr>
                    		<th>Fecha</th>
                    		<th>Tarea</th>
                    		<th>Se trabó en el paso</th>
                    		<th>Cantidad de Pasos</th>
                    		<th>Hechos</th>
                    	</tr>
                    </thead>
                    <tbody>
						<?php foreach ($pendingTasks as $task): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($task[0]))?></td>
                                <td><?= $task[1]?></td>
                                <td><?= $task[5]?></td>
                                <td><?= $task[6]?></td>
                                <td><?= $task[7]?>/<?= $task[6]?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
				<a href="<?= $this->Url->build('/', true) ?>" class="btn btn-danger border-dark">Volver</a>  
            </div>
        </div>
    </div>
</div>