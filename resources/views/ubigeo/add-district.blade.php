<form id="frmAddDistrict" action="{{ route('ubigeo.add-district') }}" method="POST">
	@csrf
	<div class="row">
		<div class="col-md-12 form-group">
			<label for="txtDepartmentId">Departamento *</label>
			<select name="txtDepartmentId" id="txtDepartmentId" class="form-control form-control-sm select2" onchange="getProvinces(event)">
				<option value="">Seleccione departamento</option>
				@foreach ($departments as $item)
					<option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-md-12 form-group">
			<label for="txtProvinceId">Provincia *</label>
			<select name="txtProvinceId" id="txtProvinceId" class="form-control form-control-sm select2">
				<option></option>
			</select>
		</div>
		<div class="col-md-12 form-group">
			<label for="txtDistrict">distrito *</label>
			<input id="txtDistrict" name="txtDistrict" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<hr>
	<div class="col-12 d-flex justify-content-end">
		<button type="submit" class="btn btn-primary">Registrar datos</button>
	</div>
</form>
<script>
	var _baseAppUrl = '{{ url('/') }}'

	$(function() {
		$('#frmAddDistrict').validate({
			rules: {
				'txtDepartmentId': {
					required: true
				},
				'txtProvinceId': {
					required: true
				},
				'txtDistrict': {
					required: true
				}
			},
			messages: {
				'txtDepartmentId': {
					required: 'El campo departamento es obligatorio'
				},
				'txtProvinceId': {
					required: 'El campo provincia es obligatorio'
				},
				'txtDistrict': {
					required: 'El campo distrito es obligatorio'
				}
			},
			...validationConfig,
			submitHandler: function (form) {
				openFormConfirm(form.id)
			}
		})
	})

	function getProvinces(event) {
		$('#txtProvinceId').html('<option></option>')

		$.ajax({
			url: `${_baseAppUrl}/ubigeo/provinces/${event.target.value}`,
			type: 'GET',
			success: function (response) {
				const html = response.map(province => `<option value="${province.id}">${province.name}</option>`)

				$('#txtProvinceId').html(`<option></option>${html.join('')}`)
			}
		})
	}
</script>