<form id="frmAddProvince" action="{{ route('ubigeo.add-province') }}" method="POST">
	@csrf
	<div class="row">
		<div class="col-md-12 form-group">
			<label for="txtDepartmentId">Departamento *</label>
			<select name="txtDepartmentId" id="txtDepartmentId" class="form-control form-control-sm select2">
				<option value="">Seleccione departamento</option>
				@foreach ($departments as $item)
					<option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-md-12 form-group">
			<label for="txtProvince">Provincia *</label>
			<input id="txtProvince" name="txtProvince" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<hr>
	<div class="col-12 d-flex justify-content-end">
		<button type="submit" class="btn btn-primary">Registrar datos</button>
	</div>
</form>
<script>
	$(function() {
		$('#frmAddProvince').validate({
			rules: {
				'txtDepartmentId': {
					required: true
				},
				'txtProvince': {
					required: true
				}
			},
			messages: {
				'txtDepartmentId': {
					required: 'El campo departamento es obligatorio'
				},
				'txtProvince': {
					required: 'El campo provincia es obligatorio'
				}
			},
			...validationConfig,
			submitHandler: function (form) {
				openFormConfirm(form.id)
			}
		})
	})
</script>