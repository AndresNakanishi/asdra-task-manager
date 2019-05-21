<?php $this->assign('title', 'Grupo de Tareas');?>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            stateSave: true,
            "columnDefs": [
                {"orderable": false, "targets": 3},
            ],
        });
    });
</script>
<style>
    tr td:last-child{
        width:1%;
        white-space:nowrap;
    }
</style>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3><b>Consulta de Grupos de Tareas</b></h3>
            </div>
            <div class="card-body">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th>Compañía</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                        <tr>
                            <td><?= $group->company->company_name ?></td>
                            <td><?= $group->title ?></td>
                            <td><?= $group->description ?></td>
                            <td>
                                <?= $this->Html->link(
                                    'Ver', 
                                    ['action' => 'view', $group->group_id], 
                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
                                ) ?>
                                <?= $this->Html->link(
                                    'Editar', 
                                    ['action' => 'edit', $group->group_id], 
                                    ['style' => 'padding-right: 10px;', 'class' => 'btn btn-sm btn-info border-dark']
                                ) ?>
                                <?= $this->Form->postLink(
                                    'Eliminar', 
                                    ['action' => 'delete', $group->group_id], 
                                    [
                                        'confirm' => __('¿Está seguro que desea eliminar el grupo de tareas "{0}"?', $group->title),
                                        'style' => 'padding-right: 10px;',
                                        'class' => 'btn btn-sm btn-danger border-dark'
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a href="<?= $this->Url->build('/', true) ?>groups/add" class="btn btn-primary border-dark"><i class="fas fa-plus-circle"></i> Agregar Grupo de Tareas</a>
            </div>
        </div>
    </div>
</div>
<br><br>