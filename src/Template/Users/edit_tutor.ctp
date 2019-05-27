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
                        <?= $this->Form->create($user, ['type' => 'file', 'id' => 'form', 'autocomplete' => 'off', 'class' => 'profileContainer-form d-flex flex-wrap align-content-start', 'url' => ['controller' => 'Users', 'action' => 'edit', $user->user_id]]) ?>
                            <input type="text" id="avatar-code" name="avatar-code" style="display: none">
                            <input type="text" id="photo" name="photo" style="display: none">                            
                            <!-- Nombre Completo -->
                            <div class="form-group col-lg-12 d-flex justify-content-between">
                              <input class="col-lg-7 form-control" required name="name" id="name" type="text" value="<?= $user->name ?>" placeholder="Nombre Completo (Requerido)">
                            <!-- Teléfono -->
                              <input class="col-lg-5 form-control" required name="phone" value="<?= $user->phone ?>" type="text" placeholder="Teléfono (Requerido)">
                            </div>
                            <!-- Dirección -->
                            <div class="form-group col-lg-12">
                              <input class="col-lg-12 form-control mt-3" required name="address" type="text" value="<?= $user->address ?>" placeholder="Dirección (Requerido)">
                            </div>
                            <!-- Usuarios -->
                            <div class="form-group col-lg-12">
                              <input class="col-lg-12 form-control mt-3" required autocomplete="off" name="user" value="<?= $user->user ?>" type="text" placeholder="Usuario (Requerido)">
                            </div>
                            <!-- Password -->
                            <div class="form-group col-lg-12">
                              <input class="col-lg-12 form-control mt-3" name="password" type="password" placeholder="Cambiar Contraseña">
                            </div>
                            <div class="form-group col-lg-12">
                              <label for="company_id">Compañía:</label>
                              <?= $this->Form->select('company_id', $companies, ['default' => '', 'class' => 'form-control']);?>
                            </div>
                        <?= $this->Form->end() ?>
                        </div>
                        <!-- Imagen -->
                        <div class="d-flex flex-column align-items-center text-center">
                            <?php if (strlen($user['photo']) > 80): ?>
                                  <img class="rounded-circle" height="200" width="200" src="<?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                            <?php else: ?>
                                <img class="rounded-circle" height="200" width="200" src="<?= $this->Url->build('/', true) ?><?= $user['photo'] ?>" alt="<?= $user['name'] ?>">
                            <?php endif ?> 
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
                            </div>                       
                            <div class="row text-center m-b-15" id="div-img-loader" style="display: none">
                                <div class="col-lg-12" style="overflow: hidden; margin-top: 30px">
                                    <?= $this->Html->image('img-spinner.gif', ["alt" => "Cargando...", "style" => "width: 100%; max-width: 120px;"]); ?>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                <!-- Foto -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <?= $this->Form->postLink(
                        "¿Desea eliminar a $user->name de la base de datos?", 
                        ['action' => 'deleteTutor', $user->user_id], 
                        [
                            'confirm' => __('¿Está seguro que eliminar a "{0}" de la base de datos?', $user->name),
                            'class' => 'link'
                        ]
                    ) ?> 
                    <div class="d-flex">
                        <a href="<?= $this->Url->build('/', true) ?>users/tutors" class="btn btn-danger border-dark mr-3">Volver</a>    
                        <input form="form" type="submit" id="accept-button" class="btn btn-success border-dark" value="Guardar">    
                    </div>
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