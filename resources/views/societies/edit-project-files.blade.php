<form class="row" id="frmEditProjectFiles" method="POST" action="{{ route('societies.editprojectfiles', $project->id) }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="col-md-8 form-group">
		<label for="fileUploadFile">Archivo *</label>
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
	<div class="col-md-4 form-group">
		<label for="">&nbsp;</label>
		<button type="submit" class="btn btn-primary btn-block">Subir archivo</button>
	</div>
</form>
<hr>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body shadow-sm table-responsive p-0">
				<table class="table table-striped text-nowrap" style="min-width: 600px;">
					<thead>
						<tr class="bg-slate-300">
							<th width="80%">Descripción</th>
							<th>Fecha de subida</th>
							<th width="10%"></th>
						</tr>
					</thead>
					<tbody>
						@if ($project->files)
							@foreach ($project->files as $item)
								<tr>
									<td>{{ $item['originalname'] }}</td>
									<td>{{ $item['created_at'] }}</td>
									<td>
										<a href="{{ route('societies.downloadprojectfile', [$project->id, $item['filename']]) }}" class="btn bg-default btn-sm px-1 py-0" title="Descargar archivo">
											<i class="fas fa-download text-primary"></i>
										</a>
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/file-upload.js') }}"></script>
<script>
	$(function() {
		$('#frmEditProjectFiles').validate({
			rules: {
				'fileUploadFile[]': {
					required: true
				}
			},
			messages: {
				'fileUploadFile[]': {
					required: 'Este campo es obligatorio'
				}
			},
			...validationConfig,
			submitHandler: function (form) {
				openFormConfirm(form.id)
			}
		})
	})
</script>