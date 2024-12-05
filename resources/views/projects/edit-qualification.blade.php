<link rel="stylesheet" href="{{asset('resources/projects/projectEdit.css')}}">
<form id="frmEditProjectFiles" method="POST" action="{{ route('projects.editqualification', $project->id) }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="row">
		<div class="col-md-12 form-group">
			<label for="txtLiquidation">Liquidación *</label>
			<select name="txtLiquidation" id="txtLiquidation" class="form-control form-control-sm select2">
				<option value="0" @selected(!$project->liquidation)>No</option>
				<option value="1" @selected($project->liquidation)>Sí</option>
			</select>
		</div>
		<div class="col-md-12">
			<label for="txtCalification">Calificación *</label>
			<div class="emoji-rating my-4">
				<input type="radio" id="emoji1" name="rating" value="1" @checked($project->qualification == 1)>
				<label for="emoji1" class="fas fa-grin text-success" data-toggle="tooltip" data-placement="left" title="EXCELENTE"></label>
				<input type="radio" id="emoji2" name="rating" value="2" @checked($project->qualification == 2)>
				<label for="emoji2" class="fas fa-smile text-success" data-toggle="tooltip" data-placement="left" title="BUENO"></label>
				<input type="radio" id="emoji3" name="rating" value="3" @checked($project->qualification == 3)>
				<label for="emoji3" class="fas fa-meh text-warning" data-toggle="tooltip" data-placement="left" title="REGULAR"></label>
				<input type="radio" id="emoji4" name="rating" value="4" @checked($project->qualification == 4)>
				<label for="emoji4" class="fas fa-frown-open text-danger" data-toggle="tooltip" data-placement="left" title="MALO"></label>
				<input type="radio" id="emoji5" name="rating" value="5" @checked($project->qualification == 5)>
				<label for="emoji5" class="fas fa-frown text-danger" data-toggle="tooltip" data-placement="left" title="MALO O INSUFICIENTE"></label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 form-group">
			<label for="fileUploadFile">Archivo (20 MB) *</label>
			<input type="file" name="fileUploadFile" class="file-upload-default" accept=".pdf">
			<div class="input-group">
				<input type="text" name="fileUploadFileTxt" id="fileUploadFileTxt" disabled class="form-control form-control-sm file-upload-info">
				<span class="input-group-append">
					<button class="file-upload-browse btn btn-primary" type="button">Seleccionar</button>
				</span>
			</div>
			<small class="form-text text-muted">
				Optimize el archivo antes de subirlo. puede comprimirlo en: <a href="https://ilovepdf.com/compress_pdf" rel="noopener noreferrer" target="_blank">ilovepdf.com</a>
			</small>
		</div>
	</div>
	<p class="text-danger">(*) Al guardar los cambios, se cambiará a liquidado el proyecto</p>
	<hr>
	<div class="row">
		<div class="col-md-12 d-flex justify-content-between form-group">
			<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary">Guardar cambios</button>
		</div>
	</div>
</form>
<script src="{{ asset('js/file-upload.js') }}"></script>
<script>
	$(function() {

		$('#frmEditProjectFiles').validate({
			rules: {

			},
			messages: {
			},
			...validationConfig,
			submitHandler: function (form) {
				openFormConfirm(form.id)
			}
		})
	})
</script>