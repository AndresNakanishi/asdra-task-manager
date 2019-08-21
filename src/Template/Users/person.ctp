<?php $this->assign('title', $user->name);?>

<?= $this->Html->css(['/plugins/jcrop/jquery.Jcrop.min']) ?>
<?= $this->Html->script(['/plugins/jcrop/jquery.Jcrop.min']) ?>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body profileContainer">
                <h4 class="profileContainer-title">Administración de Perfil</h4>
                <div class="profileContainer-content mt-3">
                    <div class="profileContainerEdit">
                        <div>     
                        <?= $this->Form->create($user, ['type' => 'file', 'id' => 'form', 'class' => 'profileContainer-form d-flex flex-wrap align-content-start', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->user_id]]) ?>
                            <input type="text" id="avatar-code" name="avatar-code" style="display: none">
                            <input type="text" id="photo" name="photo" style="display: none">                            
                            <?php if ($userProfileCode == 'ADM'): ?>
                                <!-- Nombre Completo -->
                                <input class="col-lg-8 form-control" required name="name" maxlength="50" type="text" placeholder="Nombre Completo" value="<?= $user->name ?>">
                                <!-- Teléfono -->
                                <input class="col-lg-4 form-control" required name="phone" maxlength="45" type="text" placeholder="Teléfono" value="<?= $user->phone ?>">
                                <!-- Dirección -->
                                <input class="col-lg-12 form-control mt-3" required name="address" maxlength="200" type="text" placeholder="Dirección" value="<?= $user->address ?>">
                            <?php else: ?>
                                <!-- Nombre Completo -->
                                <input class="col-lg-8 form-control" disabled="true" required name="name" type="text" placeholder="Nombre Completo" value="<?= $user->name ?>">
                                <!-- Teléfono -->
                                <input class="col-lg-4 form-control" disabled="true" required name="phone" type="text" placeholder="Teléfono" value="<?= $user->phone ?>">
                                <!-- Dirección -->
                                <input class="col-lg-12 form-control mt-3" disabled="true" required name="address" type="text" placeholder="Dirección" value="<?= $user->address ?>">
                            <?php endif ?>
                        <?= $this->Form->end() ?>
                            <div class="row mb-3 mt-3">
                                <div class="col-lg-12">
                                    Su código de activación de <?= $user->name ?> es: <strong><?= $user->token ?></strong>
                                </div>
                            </div>
                            <div class="tutorTable mt-2">    
                                <h4>Tutores a Cargo: </h4><br>                
                                <table class="table text-center">
                                    <tbody>
                                        <?php if (isset($supervisors)): ?>
                                            <?php foreach ($supervisors as $super): ?>
                                                <tr>
                                                    <td><?= $super['name'] ?></td>
                                                    <td><?= $super['phone'] ?></td>
                                                    <?php if ($super['role'] == 'TUT'): ?>
                                                        <td>Apoyo Profesional</td>
                                                    <?php elseif($super['role'] == 'CHF'): ?>
                                                        <td>Apoyo Natural</td>
                                                    <?php else: ?>
                                                        <td>Familiar</td>
                                                    <?php endif ?>
                                                    <?php if ($super['role'] == 'TUT'): ?>
                                                        <td>                    
                                                        </td>
                                                    <?php else: ?>
                                                        <td>
                                                            <?php if ($userProfileCode == 'ADM'): ?>         
                                                            <?= $this->Form->postLink(
                                                                'Eliminar', 
                                                                ['action' => 'deleteRelationUserTutor', $super['id'], $user->user_id], 
                                                                [
                                                                    'confirm' => __('¿Está seguro que eliminar a "{0}" como tutor?', $super['name']),
                                                                    'class' => 'btn btn-sm btn-danger border-dark'
                                                                ]
                                                            ) ?>  
                                                            <?php endif ?>                     
                                                        </td>
                                                    <?php endif ?>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">
                                                    No tiene tutores
                                                </td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($userProfileCode == 'ADM' && $checkIfNaturalSupport < 0 ): ?>
                            <div class="row">
                                <a href="<?= $this->Url->build('/', true) ?>users/assign-tutor/<?= $user->user_id?>" class="btn btn-primary border-dark ml-3"><i class="fas fa-plus-circle"></i> Agregar Tutores</a>
                            </div>
                            <?php endif ?>
                            <div class="profileContainerTasks mt-3">
                                <h6>Tipos de Tareas Asignadas</h6>
                                <div class="profileContainerTasks-tasks">
                                    <!-- Group Type 1 ==> Tareas del trabajo  -->
                                    <a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user->user_id?>/1" class="task text-center">
                                        <div class="icon rounded-circle">
                                            <i class="icon-fontawesome fas fa-wrench"></i>
                                        </div>
                                        <p class="mt-3">En el trabajo</p>
                                    </a>
                                    <?php if ($userProfileCode == 'ADM'): ?>
                                    <!-- Group Type 2  ==> Tareas de la casa -->
                                    <a href="<?= $this->Url->build('/', true) ?>group-users/view/<?= $user->user_id?>/2" class="task text-center">
                                        <div class="icon rounded-circle">
                                            <i class="icon-fontawesome fas fa-home"></i>
                                        </div>
                                        <p class="mt-3">En casa</p>
                                    </a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <!-- Imagen -->
                        <div class="d-flex flex-column align-items-center text-center">
                            <img class="rounded-circle" height="200" width="200" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                            <?php if ($userProfileCode == 'ADM'): ?>
                            <!-- Foto -->
                            <div class="col-lg-8 text-center" style="margin-top: 30px" id="avatar-div">
                                <div class="row text-center d-flex flex-column justify-content-center align-items-center" id="div-img-form">
                                    <div class="col-lg-12" style="overflow: hidden;">
                                        <!-- Foto -->
                                        <input type="file" class="custom-file-input" name="photo" id="file" style="display:none;">

                                        <label class="btn btn-primary" for="file">
                                          Seleccionar imagen
                                        </label>
                                        <br>
                                        <p class="m-b-10">Tamaño maximo de imagen: 3 MB.</p>
                                    </div>
                                    <div id="avatar-resize-info" class="col-lg-12 m-b-10" style="display: none">
                                      <hr class="m-t-0 m-b-10" style="border-color: #cfcfcf">
                                      Seleccione el area de la imagen que desea subir
                                    </div>
                                    <div class="col-lg-12 d-flex justify-content-center" id="views"></div>
                                    <div class="col-lg-12 m-t-10" id="avatar-size-error" style="color: #930000; display: none">
                                      La imagen seleccionada supera los 3 MB.
                                    </div>
                                </div>
                                <div class="row text-center m-b-15" id="div-img-loader" style="display: none">
                                    <div class="col-lg-12" style="overflow: hidden; margin-top: 30px">
                                        <?= $this->Html->image('img-spinner.gif', ["alt" => "Cargando...", "style" => "width: 100%; max-width: 120px;"]); ?>
                                    </div>
                                </div>
                            </div>                       
                            <?php endif ?> 
                        </div>
                    </div>
                </div>
                <!-- Foto -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <?php if ($userProfileCode == 'ADM'): ?>
                        <?= $this->Form->postLink(
                            "¿Desea eliminar a $user->name de la base de datos?", 
                            ['action' => 'delete', $user->user_id], 
                            [
                                'confirm' => __('¿Está seguro que eliminar a "{0}" de la base de datos?', $user->name),
                                'class' => 'link'
                            ]
                        ) ?> 
                    <?php endif ?>
                    <?php if ($userProfileCode == 'ADM'): ?>
                    <div class="d-flex">
                            <?= $this->Html->link(
                                'Generar Código de Activación Nuevo', 
                                ['action' => 'generateNewToken', $user->user_id], 
                                ['style' => 'padding-right: 10px;', 'class' => 'btn btn-info border-dark mr-3']
                            ) ?>   
                            <a href="<?= $this->Url->build('/', true) ?>in-charge" class="btn btn-danger border-dark mr-3" style="z-index:1;">Volver</a>    
                            <input form="form" type="submit" id="accept-button" class="btn btn-success border-dark" value="Guardar">    
                    </div>
                    <?php else: ?>
                    <div class="d-flex justify-content-end col-lg-12">
                        <a href="<?= $this->Url->build('/', true) ?>in-charge" class="btn btn-danger border-dark mr-3" style="z-index:1;">Volver</a>    
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Image Uploader con resize

    $("#edit-image").click(function(e){
        e.preventDefault();
        $("#edit-image-div").hide();
        $("#div-img-form").show();
    });

    var edited = 0;
    var crop_max_width = 250;
    var crop_max_height = 250;
    var jcrop_api;
    var canvas;
    var context;
    var image;

    var prefsize;

    $("#file").change(function() {
      loadImage(this);
      edited = 1;
      $("#avatar-resize-info").show();
    });

    function loadImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        canvas = null;
        reader.onload = function(e) {
          image = new Image();
          image.onload = validateImage;
          image.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function dataURLtoBlob(dataURL) {
      var BASE64_MARKER = ';base64,';
      if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        var raw = decodeURIComponent(parts[1]);

        return new Blob([raw], {
          type: contentType
        });
      }
      var parts = dataURL.split(BASE64_MARKER);
      var contentType = parts[0].split(':')[1];
      var raw = window.atob(parts[1]);
      var rawLength = raw.length;
      var uInt8Array = new Uint8Array(rawLength);
      for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
      }

      return new Blob([uInt8Array], {
        type: contentType
      });
    }

    function validateImage() {
      if (canvas != null) {
        image = new Image();
        image.onload = restartJcrop;
        image.src = canvas.toDataURL('image/png');
      } else restartJcrop();
    }

    function restartJcrop() {
      if (jcrop_api != null) {
        jcrop_api.destroy();
      }
      $("#views").empty();
      $("#views").append("<canvas id=\"canvas\">");
      canvas = $("#canvas")[0];
      context = canvas.getContext("2d");
      canvas.width = image.width;
      canvas.height = image.height;
      context.drawImage(image, 0, 0);
      $("#canvas").Jcrop({
        onSelect: selectcanvas,
        boxWidth: (document.getElementById("div-img-form").clientWidth)-40,
        boxHeight: crop_max_height,
        aspectRatio: 1/1,
        setSelect: [canvas.width/4, canvas.height/4, canvas.width/4+canvas.width/2, canvas.height/4+canvas.height/2]
      }, function() {
        jcrop_api = this;
      });
    }

    function selectcanvas(coords) {
      prefsize = {
        x: Math.round(coords.x),
        y: Math.round(coords.y),
        w: Math.round(coords.w),
        h: Math.round(coords.h)
      };
    }

    $("#accept-button").click(function(e) {
      if(edited == 1)
      {
        canvas.width = prefsize.w;
        canvas.height = prefsize.h;
        context.drawImage(image, prefsize.x, prefsize.y, prefsize.w, prefsize.h, 0, 0, canvas.width, canvas.height);
        var dataURL = canvas.toDataURL('image/png');
        // Error si la imagen es mayor a 3 MB
        if(dataURL.length<3145728)
        {
            $("#photo").val($("#name").val()+'.png');
            $("#avatar-code").val(dataURL);
            $("#avatar-div").hide();
            $("#div-img-loader").show();
        }
        else
        {
            $("#avatar-size-error").show();
        }
      }
      else
      {
        $("#avatar-div").hide();
        $("#div-img-loader").show();
      }
    });
</script>