<form id="frmAddMember" method="POST" action="javascript:void()">
	@csrf
	<input type="hidden" id="txtProjectId" value="{{ $project_id }}">
	<div class="row">
		<div class="col-md-4 form-group">
			<label for="txtDni">DNI *</label>
			<input id="txtDni" name="txtDni" type="text" class="form-control form-control-sm" maxlength="8"  placeholder="Ingrese DNI (Enter)" onkeyup="keyUpTxtDni(event)" onblur="getParntnerByDni(event)">

		</div>
		<div class="col-md-8 form-group">
			<label for="txtFullName">Nombre completo *</label>
			<input id="txtFullName" name="txtFullName" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 form-group">
			<label for="txtBirthDate">Fecha de nacimieto *</label>
			<input id="txtBirthDate" name="txtBirthDate" type="date" class="form-control form-control-sm">
		</div>
		<div class="col-md-4 form-group">
			<label for="txtPhone">Teléfono</label>
			<input id="txtPhone" name="txtPhone" type="text" class="form-control form-control-sm" maxlength="9">
		</div>
		<div class="col-md-4 form-group">
			<label for="txtEmail">Correlo electrónico</label>
			<input id="txtEmail" name="txtEmail" type="email" class="form-control form-control-sm">
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 form-group">
			<label for="txtAddress">Dirección</label>
			<input id="txtAddress" name="txtAddress" type="text" class="form-control form-control-sm">
		</div>
		<div class="col-md-4 form-group">
			<label for="txtCharge">Cargo *</label>
			<select name="txtCharge" id="txtCharge" class="form-control select2" style="width: 100%;">
				<option></option>
				<option value="Miembro">Miembro</option>
				<option value="Directivo">Directivo</option>
				<option value="Presidente">Presidente</option>
			</select>
		</div>
	</div>
	<p class="h5 text-primary mb-3">Datos de la pareja</p>
	<div class="form-check form-check-flat form-check-primary">
		<label for="chkHasSpouse" class="form-check-label">
			<input type="checkbox" id="chkHasSpouse" name="chkHasSpouse" class="form-check-input" onchange="changeChkHasSpouse(event)">
			¿Tiene pareja?
		</label>
	</div>
	<div class="row hasSpouseDiv" style="display: none;">
		<div class="col-md-4 form-group">
			<label for="txtSpouseDni">DNI *</label>
			<input id="txtSpouseDni" name="txtSpouseDni" type="text" class="form-control form-control-sm" maxlength="8">
		</div>
		<div class="col-md-8 form-group">
			<label for="txtSpouseFullName">Nombre completo *</label>
			<input id="txtSpouseFullName" name="txtSpouseFullName" type="text" class="form-control form-control-sm">
		</div>
	</div>
	<div class="row hasSpouseDiv" style="display: none;">
		<div class="col-md-4 form-group">
			<label for="txtSpouseBirthDate">Fecha de nacimieto *</label>
			<input id="txtSpouseBirthDate" name="txtSpouseBirthDate" type="date" class="form-control form-control-sm">
		</div>
		<div class="col-md-4 form-group">
			<label for="txtSpousePhone">Celular</label>
			<input id="txtSpousePhone" name="txtSpousePhone" type="text" class="form-control form-control-sm" maxlength="9">
		</div>
		<div class="col-md-4 form-group">
			<label for="txtSpouseEmail">Correlo electrónico</label>
			<input id="txtSpouseEmail" name="txtSpouseEmail" type="email" class="form-control form-control-sm">
		</div>
	</div>
	<div class="form-group">
		<label for="txtObservation">Observación</label>
		<textarea id="txtObservation" name="txtObservation" class="form-control form-control-sm" rows="2" placeholder="Ingrese observación"></textarea>
	</div>
	<div class="row">
		<div class="col-12 d-flex justify-content-end">
			<button type="submit" class="btn btn-primary">
				Registrar socio
			</button>
		</div>
	</div>
</form>
<hr>
<div class="row">
	<div class="col-12 form-group">
		<div class="card">
			<div class="card-body shadow-sm table-responsive p-0">
				<table id="tblMembers" class="table table-striped text-nowrap" style="min-width: 600px;">
					<thead>
						<tr class="bg-slate-300">
							<th width="10%">DNI</th>
							<th>Nombre completo</th>
							<th>Observación</th>
							<th width="10%">Acciones</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($members as $item)
							<tr data-member-id="{{ $item->id }}">
								<td>{{ $item->member->dni }}</td>
								<td>{{ $item->member->full_name }}</td>
								<td>{{ $item->observation }}</td>
								<td>
									<button class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="right" title="Eliminar" onclick="openConfirmModal(function() {deleteMemberAjax('{{ $item->id }}')})">
										<i class="fas fa-trash text-danger"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	var _csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('resources/projects/members.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>