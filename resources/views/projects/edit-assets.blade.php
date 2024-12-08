<div>
	<div class="row">
		<div class="col-lg-9 form-group">
			<label for="txtDescription">Descripción *</label>
			<input id="txtDescription" name="txtDescription" type="text" class="form-control form-control-sm">
		</div>
		<div class="col-lg-3 form-group">
			<label for="txtUnit">Unidad de medida *</label>
			<input id="txtUnit" name="txtUnit" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 form-group">
			<label for="txtQuantity">Cantidad *</label>
			<input id="txtQuantity" name="txtQuantity" type="number" class="form-control form-control-sm" min="1">
		</div>
		<div class="col-lg-3 form-group">
			<label for="txtReceptionDate">Fecha de recepción *</label>
			<input id="txtReceptionDate" name="txtReceptionDate" type="date" class="form-control form-control-sm">
		</div>
		<div class="col-lg-3 form-group">
			<label for="txtType">Tipo *</label>
			<select name="txtType" id="txtType" class="form-control form-control-sm">
				<option value="Bien" selected>Bien</option>
				<option value="Servicio">Servicio</option>
			</select>
		</div>
		<div class="col-lg-3 form-group">
			<label for="txtStatus">Estado *</label>
			<select name="txtStatus" id="txtStatus" class="form-control form-control-sm">
				<option value="Habido/Buen estado" >Habido/Buen estado</option>
				<option value="Habido/Mal estado">Habido/Mal estado</option>
				<option value="No habido">No habido</option>
				<option value="No ejecutado">No ejecutado</option>
				<option value="Ejecutado">Ejecutado</option>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-9">
			<label for="txtObservation">Observaciones</label>
			<input id="txtObservation" name="txtObservation" type="text" class="form-control form-control-sm">
		</div>
		<div class="col-lg-3 form-group">
			<label for="">&nbsp;</label>
			<button type="button" class="btn btn-primary btn-sm btn-block" onclick="addAsset()">Agregar bien o servicio</button>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-8 form-group">
			<label for="fileUploadFileAssets">Añadir un archivo adicional - Archivo (20 MB) *</label>
			<input type="file" name="fileUploadFileAssets" class="file-upload-default" accept=".pdf">
			<div class="input-group">
				<input type="text" name="fileUploadFileAssetsTxt" id="fileUploadFileAssetsTxt" value="{{$project->assets_file ? json_decode($project->assets_file)->originalname :''}}" disabled class="form-control form-control-sm file-upload-info">
				<span class="input-group-append">
					<button class="file-upload-browse btn btn-primary" type="button">Seleccionar</button>
				</span>
			</div>
			<small class="form-text text-muted">
				Optimize el archivo antes de subirlo. puede comprimirlo en: <a href="https://ilovepdf.com/compress_pdf" rel="noopener noreferrer" target="_blank">ilovepdf.com</a>
			</small>
		</div>
		<div class="col-md-4 form-group">
			<label for="descagarArchivo">Descargar archivo</label>
			<a href="{{ route('projects.downloadfileasset', $project->id) }}" style="{{$project->assets_file ? '': 'pointer-events: none; cursor: not-allowed; color: red; text-decoration: none;'}}"  class="btn btn-success btn-sm btn-block" id="descagarArchivo">Descargar</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body shadow-sm table-responsive p-0">
					<table class="table table-striped text-nowrap" style="min-width: 600px;">
						<thead>
							<tr class="bg-slate-300">
								<th>Descripción</th>
								<th>Unidad de medida</th>
								<th>Cantidad</th>
								<th>Fecha de recepción</th>
								<th>Tipo</th>
								<th>Estado</th>
								<th>Observación</th>
								<th width="10%"></th>
							</tr>
						</thead>
						<tbody id="tbodyAssets">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<form class="row" id="frmEditProjectAssets" method="POST" action="{{ route('projects.editassets', $project->id) }}">
		@csrf
		@method('PUT')
		<input type="hidden" name="txtAssets" id="txtAssets">
		<div class="col-12 d-flex justify-content-end">
			<button type="submit" class="btn btn-primary">Guardar bienes y servicios</button>
		</div>
	</form>
</div>
<script src="{{ asset('js/file-upload.js') }}"></script>
<script>
	var member = {!! json_encode($project) !!};

	var assets = typeof member.assets === 'string' ? (Array.isArray(JSON.parse(member.assets)) ? JSON.parse(member.assets) : []) : Array.isArray(member.assets) ? member.assets : [];

	$(function() {
		assets.forEach(function(asset) {
			appendAsset(asset);
		});

		$('#frmEditProjectAssets').validate({
			submitHandler: function (form) {
			// Capturamos los datos del formulario
			const formData = new FormData(form);
			
			// Agregar el JSON de assets al form data
			formData.set('txtAssets', JSON.stringify(assets));
			
			// Capturamos el archivo del input
			const fileInput = $('input[name="fileUploadFileAssets"]')[0];
			if (fileInput.files.length > 0) {
				formData.append('fileUploadFileAssets', fileInput.files[0]);
			}

			// Realizamos la petición AJAX
			$.ajax({
				url: form.action, // URL del formulario
				type: form.method, // Método definido (POST, PUT, etc.)
				data: formData,
				processData: false, // Evitar procesamiento automático de datos
				contentType: false, // Permitir que el navegador gestione el Content-Type
				success: function (response) {
					console.log('Guardado exitosamente', response);
					toastr.success('Se guardaron los datos y el archivo correctamente');
					if (response.data.assets_file) {
						// $('#fileUploadFileAssetsTxt').val(response.data.assets_file.originalname);//xd
						const link = document.getElementById('descagarArchivo');
						link.style.pointerEvents = 'auto';
						link.style.cursor = 'pointer';
						link.style.color = '';
						link.style.textDecoration = '';
					}
				},
				error: function (error) {
					console.error('Error al guardar', error);
					toastr.error('Hubo un error al guardar los datos o el archivo',error);
				}
			});
		}
		});

	});

	function clearForm() {
		$('#txtDescription').val('');
		$('#txtUnit').val('');
		$('#txtQuantity').val('');
		$('#txtReceptionDate').val('');
		$('#txtType').val('Bien');
		$('#txtStatus').val('');
		$('#txtObservation').val('');
	}

	function addAsset() {
		var description = $('#txtDescription').val();
		var unit = $('#txtUnit').val();
		var quantity = $('#txtQuantity').val();
		var receptionDate = $('#txtReceptionDate').val();
		var type = $('#txtType').val();
		var status = $('#txtStatus').val();
		var observation = $('#txtObservation').val();

		if (description == '' || unit == '' || quantity == '' || receptionDate == '' || type == '' || status == '') {
			toastr.error('Todos los campos son obligatorios');
			return;
		}

		var asset = {
			description: description,
			unit: unit,
			quantity: quantity,
			receptionDate: receptionDate,
			type: type,
			status: status,
			observation: observation
		};

		appendAsset(asset);
		assets.push(asset);
		clearForm();
		updateAssets();
	}

	function appendAsset(asset) {
		var tr = $('<tr>');
		tr.append($('<td>').text(asset.description));
		tr.append($('<td>').text(asset.unit));
		tr.append($('<td>').text(asset.quantity));
		tr.append($('<td>').text(asset.receptionDate));
		tr.append($('<td>').text(asset.type));
		tr.append($('<td>').text(asset.status));
		tr.append($('<td>').text(asset.observation));
		tr.append($('<td>').append($('<button>').addClass('btn bg-default btn-sm px-1 py-0').attr('type', 'button').attr('onclick', 'removeAsset(this)').append($('<i>').addClass('fas fa-trash text-danger'))));
		$('#tbodyAssets').append(tr);
	}

	function removeAsset(button) {
		var tr = $(button).closest('tr');
		var index = tr.index();
		assets.splice(index, 1);
		tr.remove();
		updateAssets();
	}

	function updateAssets() {
		$('#txtAssets').val(JSON.stringify(assets));
	}
</script>