<x-admin-layout>
	<h4 class="card-title">Registrar organización</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmEditSociety" method="POST" action="{{ route('societies.edit', $society->id) }}">
				@csrf
				@method('PUT')
				<div class="form-group">
					<label for="txtSocialRazon">Razón social *</label>
					<input id="txtSocialRazon" name="txtSocialRazon" type="text" class="form-control form-control-sm" value="{{ $society->social_razon }}">
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtRuc">RUC *</label>
							<input id="txtRuc" name="txtRuc" type="text" class="form-control form-control-sm" value="{{ $society->ruc }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtConstitutionDate">Fecha de constitución *</label>
							<input id="txtConstitutionDate" name="txtConstitutionDate" type="date" class="form-control form-control-sm" value="{{ $society->constitution_date }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtPartNumber">Número de partida *</label>
							<input id="txtPartNumber" name="txtPartNumber" type="text" class="form-control form-control-sm" value="{{ $society->part_number }}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtDepartment">Departamento * {{ $society->department.'xd' }}</label>
							<select id="txtDepartment" name="txtDepartment" class="form-control select2" style="width: 100%" onchange="getProvinces(event)">
								@foreach ($departments as $item)
									<option value="{{ $item['name'] }}" {{ strtolower($item['name']) == strtolower($society->department) ? 'selected' : '' }}>{{ $item['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtProvince">Provincia *</label>
							<select id="txtProvince" name="txtProvince" class="form-control select2" style="width: 100%" onchange="getDistricts(event)">
								@foreach ($provinces as $item)
									<option value="{{ $item['name'] }}" {{ $item['name'] == $society->province ? 'selected' : '' }}>{{ $item['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtDistrict">Distrito *</label>
							<select id="txtDistrict" name="txtDistrict" class="form-control select2" style="width: 100%">
								@foreach ($districts as $item)
									<option value="{{ $item['name'] }}" {{ $item['name'] == $society->district ? 'selected' : '' }}>{{ $item['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="txtComunity">Comunidad *</label>
					<input id="txtComunity" name="txtComunity" type="text" class="form-control form-control-sm" value="{{ $society->comunity }}">
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label for="txtAddress">Dirección *</label>
							<input id="txtAddress" name="txtAddress" type="text" class="form-control form-control-sm" value="{{ $society->address }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtPhone">Teléfono *</label>
							<input id="txtPhone" name="txtPhone" type="text" class="form-control form-control-sm" value="{{ $society->phone }}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtType">Tipo *</label>
							<select id="txtType" name="txtType" class="form-control select2" style="width: 100%">
								<option></option>
								<option value="Asociación" {{ $society->type == 'Asociación' ? 'selected' : '' }}>Asociación</option>
								<option value="Empresa" {{ $society->type == 'Empresa' ? 'selected' : '' }}>Empresa</option>
								<option value="Persona jurídica" {{ $society->type == 'Persona jurídica' ? 'selected' : '' }}>Persona jurídica</option>
								<option value="Otro" {{ $society->type == 'Otro' ? 'selected' : '' }}>Otro</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="txtEmail">Correo electrónico *</label>
							<input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm" value="{{ $society->email }}">
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center justify-content-end">
					<button class="btn btn-primary" type="submit">Guardar cambios</button>
				</div>
			</form>
		</div>
	</div>
	@push('scripts')
		<script>
			var _baseAppUrl = "{{ url('') }}";
		</script>
		<script src="{{ asset('resources/societies/edit.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>
