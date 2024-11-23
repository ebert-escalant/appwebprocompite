<x-admin-layout>
	<h5 class="mb-2">Perfil</h5>
	<div class="card">
		<div class="card-body">
			<form id="frmProfile" action="{{ route('profile') }}" method="POST">
				@csrf
				@method('PUT')
				<div class="row">
					<div class="col-md-6 form-group">
						<label for="txtName">Nombre *</label>
						<input id="txtName" name="txtName" type="text" class="form-control form-control-sm" value="{{ auth()->user()->name }}">
					</div>
					<div class="col-md-6 form-group">
						<label for="txtEmail">Correo electrónico *</label>
						<input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm" value="{{ auth()->user()->email }}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<label for="txtPassword">Nueva contraseña *</label>
						<input id="txtPassword" name="txtPassword" type="password" class="form-control form-control-sm">
					</div>
					<div class="col-md-6 form-group">
						<label for="txtPasswordConfirmation">Confirmar contraseña *</label>
						<input id="txtPasswordConfirmation" name="txtPasswordConfirmation" type="password" class="form-control form-control-sm">
					</div>
				</div>
				<div class="row">
					<div class="col-12 d-flex justify-content-end">
						<button type="submit" class="btn btn-primary">
							Actualizar perfil
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	@push('scripts')
		<script>
			$(function() {
				$('#frmProfile').validate({
					rules: {
						txtName: {
							required: true
						},
						txtEmail: {
							required: true,
							email: true
						},
						txtPassword: {
							required: false,
							minlength: 8
						},
						txtPasswordConfirm: {
							required: {
								depends: function(element) {
									return $('#txtPassword').val().length > 0;
								}
							},
							equalTo: '#txtPassword'
						}
					},
					messages: {
						txtName: {
							required: 'El nombre es obligatorio'
						},
						txtEmail: {
							required: 'El correo electrónico es obligatorio',
							email: 'El correo electrónico no es válido'
						},
						txtPassword: {
							required: 'La contraseña es obligatoria',
							minlength: 'La contraseña debe tener al menos 8 caracteres'
						},
						txtPasswordConfirm: {
							required: 'La confirmación de la contraseña es obligatoria',
							equalTo: 'Las contraseñas no coinciden'
						}
					},
					...validationConfig,
					submitHandler: function (form) {
						openFormConfirm(form.id)
					}
				});
			});
		</script>
	@endpush
</x-admin-layout>