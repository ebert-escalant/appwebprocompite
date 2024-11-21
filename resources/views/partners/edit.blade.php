<x-admin-layout>
	<h4 class="card-title">Editar socio</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmEditPartner" method="POST" action="{{ route('partners.edit', $partner->id) }}">
				@csrf
                @method('PUT')
				<div class="form-group">
					<label for="txtDni">DNI *</label>
					<input id="txtDni" name="txtDni" type="text" class="form-control form-control-sm" value={{ $partner->dni}}>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtFullName">Nombre completo *</label>
							<input id="txtFullName" name="txtFullName" type="text" class="form-control form-control-sm" value="{{$partner->full_name}}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtBirthdate">Fecha de nacimieto *</label>
							<input id="txtBirthdate" name="txtBirthdate" type="date" class="form-control form-control-sm" value="{{ $partner->birthdate }}">
						</div>
					</div>
                    <div class="col-md-4">
                        <label for="txtPhone">Teléfono*</label>
                        <input id="txtPhone" name="txtPhone" type="text" class="form-control form-control-sm" value="{{ $partner->phone }}">
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="txtAddress">Dirección *</label>
                        <input id="txtAddress" name="txtAddress" type="text" class="form-control form-control-sm" value="{{ $partner->address }}">
                    </div>
                    <div class="col-md-3">
                        <label for="txtEmail">Correlo electrónico *</label>
                        <input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm" value="{{ $partner->email }}">
                    </div>
                    <div class="col-md-3">
                        <label for="txtFamilyCharge">Carga familiar *</label>
                        <input id="txtFamilyCharge" name="txtFamilyCharge" type="text" class="form-control form-control-sm" value="{{ $partner->family_charge }}">
                    </div>
                    <div class="col-md-3">
                        <label for="txtCharge">Cargo *</label>
                        <select name="txtCharge" id="txtCharge">
							<option></option>
                            <option value="Miembro"{{$partner->charge == 'Miembro' ? 'selected' : ''}}>Miembro</option>
                            <option value="Directivo" {{$partner->charge == 'Directivo' ? 'selected' : ''}}>Directivo</option>
                            <option value="Presidente"{{$partner->charge == 'Presidente' ? 'selected' : ''}}>Presidente</option>
                        </select>
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
		<script src="{{ asset('resources/partners/edit.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>
