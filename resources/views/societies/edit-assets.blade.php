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
			<label for="">&nbsp;</label>
			<button type="button" class="btn btn-primary btn-sm btn-block" onclick="addAsset()">Agregar bien o servicio</button>
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
	<form class="row" id="frmEditAssets" method="POST" action="{{ route('societies.editmemberassets', $member->id) }}">
		@csrf
		@method('PUT')
		<input type="hidden" name="txtAssets" id="txtAssets">
		<div class="col-12 d-flex justify-content-end">
			<button type="submit" class="btn btn-primary">Guardar bienes y servicios</button>
		</div>
	</form>
</div>
<script>
	var member = {!! json_encode($member) !!};
	var assets = Array.isArray(JSON.parse(member.assets)) ? JSON.parse(member.assets) : [];

	$(function() {
		assets.forEach(function(asset) {
			appendAsset(asset);
		});

		$('#frmEditAssets').validate({
			submitHandler: function (form) {
				$('#txtAssets').val(JSON.stringify(assets));
				openFormConfirm(form.id)
			}
		});

	});

	function addAsset() {
		var description = $('#txtDescription').val();
		var unit = $('#txtUnit').val();
		var quantity = $('#txtQuantity').val();
		var receptionDate = $('#txtReceptionDate').val();
		var type = $('#txtType').val();

		if (description == '' || unit == '' || quantity == '' || receptionDate == '' || type == '') {
			toastr.error('Todos los campos son obligatorios');
			return;
		}

		var asset = {
			description: description,
			unit: unit,
			quantity: quantity,
			receptionDate: receptionDate,
			type: type
		};

		appendAsset(asset);
		assets.push(asset);
		updateAssets();
	}

	function appendAsset(asset) {
		var tr = $('<tr>');
		tr.append($('<td>').text(asset.description));
		tr.append($('<td>').text(asset.unit));
		tr.append($('<td>').text(asset.quantity));
		tr.append($('<td>').text(asset.receptionDate));
		tr.append($('<td>').text(asset.type));
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