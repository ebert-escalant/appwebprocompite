<form id="frmEditProjectFiles" method="POST" action="{{ route('societies.editprojectall', $project->id) }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="row">
		<div class="col-md-6 form group">
			<label for="txtLiquidation">Liquidación</label>
			<select name="txtLiquidation" id="txtLiquidation" class="select2">
				<option value="1" {{$project->liquidation == 1 ? 'selected' : ''}}>Sí</option>
				<option value="0" {{$project->liquidation == 0 ? 'selected' : ''}}>No</option>
			</select>
		</div>
		<div class="col-md-6 form group hLiquidation">
			<label for="txtCalification">Calificación</label>
			<div class="emoji-rating">
				<input type="radio" id="emoji1" name="rating" value="1" @checked($project->qualification == 1)>
				<label for="emoji1" class="fas fa-grin text-success"></label>
				<input type="radio" id="emoji2" name="rating" value="2" @checked($project->qualification == 2)>
				<label for="emoji2" class="fas fa-smile text-success"></label>
				<input type="radio" id="emoji3" name="rating" value="3" @checked($project->qualification == 3)>
				<label for="emoji3" class="fas fa-meh text-warning"></label>
				<input type="radio" id="emoji4" name="rating" value="4" @checked($project->qualification == 4)>
				<label for="emoji4" class="fas fa-frown-open text-danger"></label>
				<input type="radio" id="emoji5" name="rating" value="5" @checked($project->qualification == 5)>
				<label for="emoji5" class="fas fa-frown text-danger"></label>
			</div>
		</div>
		<div class="col-md-4 form-group sLiquidations">
			<label for="">&nbsp;</label>
			<button type="submit" class="btn btn-primary btn-block">Guardar cambios</button>
		</div>
	</div>
	<div class="row hLiquidation">
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
			<button type="submit" class="btn btn-primary btn-block">Guardar cambios</button>
		</div>
	</div>
</form>
<hr>
<div class="row hLiquidation">
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
<link rel="stylesheet" href="{{asset('resources/societies/projectEdit.css')}}">
<script>
	$(function() {
		$('#frmEditProjectFiles').validate({
			rules: {
				// 'fileUploadFile[]': {
				// 	required: true
				// },
				'txtLiquidation': {
					required: true
				},
			},
			messages: {
				// 'fileUploadFile[]': {
				// 	required: 'Este campo es obligatorio'
				// },
				'txtLiquidation': {
					required: 'Este campo es obligatorio'
				},
			},
			...validationConfig,
			submitHandler: function (form) {
				openFormConfirm(form.id)
			}
		})
		let select = document.querySelectorAll('.emoji-rating input').value 
		document.querySelectorAll('.emoji-rating input').forEach(input => {
		input.addEventListener('change', (event) => {
				const rating = event.target.value;
				console.log(`Calificación seleccionada: ${rating}`);
				// Aquí puedes enviar el valor a tu backend o realizar alguna acción
			});
		});
		let liquidation = {{$project->liquidation}};
		liquidation == 0 ? $('.hLiquidation').hide() : $('.hLiquidation').show()
		liquidation == 1 ? $('.sLiquidations').hide() : $('.sLiquidations').show()

		document.getElementById('txtLiquidation').addEventListener('change', (event) => {
			liquidation = event.target.value;
			liquidation == 0 ? $('.hLiquidation').hide() : $('.hLiquidation').show()
			liquidation == 1 ? $('.sLiquidations').hide() : $('.sLiquidations').show()
		});

	})
</script>