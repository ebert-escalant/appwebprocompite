<form id="frmAddMember" method="POST" action="javascript:void()">
	@csrf
	<input type="hidden" id="txtProjectId" value="{{ $project_id }}">
	<div class="row">
		<div class="col-md-4 form-group">
			<label for="txtMemberDni">DNI *</label>
			<input id="txtMemberDni" name="txtMemberDni" type="text" class="form-control form-control-sm" maxlength="8" placeholder="Ingrese DNI (Enter)" onkeyup="keyUpTxtDni(event)" onblur="getParntnerByDni(event)">
		</div>
		<div class="col-md-8 form-group">
			<label for="txtFullName">Nombre completo *</label>
			<input id="txtFullName" name="txtFullName" type="text" class="form-control form-control-sm" readonly>
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