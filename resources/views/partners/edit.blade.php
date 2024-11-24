<x-admin-layout>
	<h4 class="card-title">Editar socio</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmEditPartner" method="POST" action="{{ route('partners.edit', $partner->id) }}">
				@csrf
                @method('PUT')
				<div class="row">
					<div class="col-md-4 form-group">
						<label for="txtDni">DNI *</label>
						<input id="txtDni" name="txtDni" type="text" class="form-control form-control-sm" maxlength="8" value={{ $partner->dni}}>
					</div>
					<div class="col-md-8 form-group">
						<label for="txtFullName">Nombre completo *</label>
						<input id="txtFullName" name="txtFullName" type="text" class="form-control form-control-sm" value="{{$partner->full_name}}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 form-group">
						<label for="txtBirthDate">Fecha de nacimieto *</label>
						<input id="txtBirthDate" name="txtBirthDate" type="date" class="form-control form-control-sm" value="{{ $partner->birthdate }}">
					</div>
                    <div class="col-md-4 form-group">
                        <label for="txtPhone">Teléfono*</label>
                        <input id="txtPhone" name="txtPhone" type="text" class="form-control form-control-sm" maxlength="9" value="{{ $partner->phone }}">
                    </div>
					<div class="col-md-4 form-group">
                        <label for="txtEmail">Correo electrónico *</label>
                        <input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm" value="{{ $partner->email }}">
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="txtAddress">Dirección *</label>
                        <input id="txtAddress" name="txtAddress" type="text" class="form-control form-control-sm" value="{{ $partner->address }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="txtFamilyCharge">Carga familiar *</label>
                        <input id="txtFamilyCharge" name="txtFamilyCharge" type="text" class="form-control form-control-sm" value="{{ $partner->family_charge }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="txtCharge">Cargo *</label>
                        <select name="txtCharge" id="txtCharge" class="form-control select2" style="width: 100%;">
							<option></option>
                            <option value="Miembro"{{$partner->charge == 'Miembro' ? 'selected' : ''}}>Miembro</option>
                            <option value="Directivo" {{$partner->charge == 'Directivo' ? 'selected' : ''}}>Directivo</option>
                            <option value="Presidente"{{$partner->charge == 'Presidente' ? 'selected' : ''}}>Presidente</option>
                        </select>
                    </div>
                </div>
				<p class="h5 text-primary mb-3">Datos de la pareja</p>
				<div class="form-check form-check-flat form-check-primary">
					<label for="chkHasSpouse" class="form-check-label">
						<input type="checkbox" id="chkHasSpouse" name="chkHasSpouse" class="form-check-input" {{$partner->spouse ? 'checked' : ''}} onchange="changeChkHasSpouse(event)">
						¿Tiene pareja?
					</label>
				</div>
				<div class="row hasSpouseDiv" style="display: none;">
					<div class="col-md-4 form-group">
						<label for="txtSpouseDni">DNI *</label>
						<input id="txtSpouseDni" name="txtSpouseDni" type="text" class="form-control form-control-sm" maxlength="8" value="{{$partner->spouse ? $partner->spouse->dni: ''}}">
					</div>
					<div class="col-md-8 form-group">
						<label for="txtSpouseFullName">Nombre completo *</label>
						<input id="txtSpouseFullName" name="txtSpouseFullName" type="text" class="form-control form-control-sm" value="{{$partner->spouse ? $partner->spouse->full_name : ''}}">
					</div>
				</div>
				<div class="row hasSpouseDiv" style="display: none;">
					<div class="col-md-4 form-group">
						<label for="txtSpouseBirthDate">Fecha de nacimieto *</label>
						<input id="txtSpouseBirthDate" name="txtSpouseBirthDate" type="date" class="form-control form-control-sm" value="{{$partner->spouse ? $partner->spouse->birthdate : ''}}">
					</div>
					<div class="col-md-4 form-group">
						<label for="txtSpousePhone">Teléfono*</label>
						<input id="txtSpousePhone" name="txtSpousePhone" type="text" class="form-control form-control-sm" maxlength="9" value="{{$partner->spouse ? $partner->spouse->phone : ''}}">
					</div>
					<div class="col-md-4 form-group">
						<label for="txtSpouseEmail">Correlo electrónico *</label>
						<input id="txtSpouseEmail" name="txtSpouseEmail" type="email" class="form-control form-control-sm" value="{{$partner->spouse ? $partner->spouse->email : ''}}">
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
		<script src="{{ asset('resources/partners/edit.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>
