<x-admin-layout>
	<h4 class="card-title">Registrar organización</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmInsertSociety" method="POST" action="{{ route('societies.insert') }}">
				@csrf
				<div class="form-group">
					<label for="txtSocialRazon">Razón social *</label>
					<input id="txtSocialRazon" name="txtSocialRazon" type="text" class="form-control form-control-sm">
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtRuc">RUC *</label>
							<input id="txtRuc" name="txtRuc" type="text" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtConstitutionDate">Fecha de constitución *</label>
							<input id="txtConstitutionDate" name="txtConstitutionDate" type="date" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtPartNumber">Número de partida *</label>
							<input id="txtPartNumber" name="txtPartNumber" type="text" class="form-control form-control-sm">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtDepartment">Departamento *</label>
							<select id="txtDepartment" name="txtDepartment" class="form-control select2" style="width: 100%" onchange="getProvinces(event)">
								<option></option>
								@foreach ($departments as $item)
									<option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtProvince">Provincia *</label>
							<select id="txtProvince" name="txtProvince" class="form-control select2" style="width: 100%" onchange="getDistricts(event)">
								<option></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtDistrict">Distrito *</label>
							<select id="txtDistrict" name="txtDistrict" class="form-control select2" style="width: 100%"></select>
								<option></option>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="txtComunity">Comunidad *</label>
					<input id="txtComunity" name="txtComunity" type="text" class="form-control form-control-sm">
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="txtAddress">Dirección *</label>
							<input id="txtAddress" name="txtAddress" type="text" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtPhone">Teléfono *</label>
							<input id="txtPhone" name="txtPhone" type="text" class="form-control form-control-sm">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtLatitude">Latitud *</label>
							<input id="txtLatitude" name="txtLatitude" type="text" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtLongitude">Longitud *</label>
							<input id="txtLongitude" name="txtLongitude" type="text" class="form-control form-control-sm">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtType">Tipo *</label>
							<select id="txtType" name="txtType" class="form-control select2" style="width: 100%">
								<option></option>
								<option value="Asociación">Asociación</option>
								<option value="Empresa">Empresa</option>
								<option value="Persona jurídica">Persona jurídica</option>
								<option value="Otro">Otro</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtEmail">Correo electrónico *</label>
							<input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm">
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center justify-content-end">
					<button class="btn btn-primary" type="submit">Registrar datos</button>
				</div>
			</form>
		</div>
	</div>
	@push('scripts')
		<script>
			var _baseAppUrl = "{{ url('') }}";
		</script>
		<script src="{{ asset('resources/societies/insert.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>