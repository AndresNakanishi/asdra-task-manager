<?php $this->assign('title', 'Nuevo Paso');?>
<?= $this->Html->css(['/plugins/jcrop/jquery.Jcrop.min']) ?>
<?= $this->Html->script(['/plugins/jcrop/jquery.Jcrop.min']) ?>
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
	            <h3><b>Agregar Paso</b></h3>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">	
		        <?= $this->Form->create($step, ['class' => 'col-lg-9','id' => 'addForm', 'type' => 'file']) ?>
			        <input type="text" id="avatar-code" name="avatar-code" style="display: none">
					<input type="text" id="photo" name="photo" style="display: none">
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
			                'placeholder' => 'Orden (Cantidad de Pasos: '.($steps + 1).')',
			                'value' => $steps + 1,
                      'min' => 1,
			                'max' => $steps + 1,
			                'autocomplete' => 'off'
			            ]) ?>
			        </div>
		        <?= $this->Form->end() ?>
              <div class="row">
                <div class="col-lg-12" style="margin-top: 30px" id="avatar-div">
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
                        <div class="col-lg-12" id="views"></div>
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
            <div class="card-footer d-flex justify-content-end">
            	<?= $this->Form->button(__('Agregar'), [
                	'class' => 'btn btn-primary border-dark mr-3',
                	'form' => 'addForm',
                	'id' => 'accept-button'
            	]) ?>
				<a href="<?= $this->request->referer(); ?>" class="btn btn-danger border-dark">Cancelar</a>  
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
    var crop_max_width = 400;
    var crop_max_height = 400;
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
            $("#photo").val($("#title").val()+'.png');
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