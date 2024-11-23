<x-admin-layout>
	<h5 class="card-title text-primary">Socios de ({{ $society->social_razon }})</h5>
	<form class="card" id="frmAddMember" action="{{ route('societies.addmember', $society->id) }}" method="POST">
		@csrf
		<div class="card-body">
			<div class="row">
				<div class="col-md-4 form-group">
					<label for="txtYear">Año *</label>
					<select name="txtYear" id="txtYear" class="form-control select2" style="width: 100%;">
						<option></option>
						@foreach ($years as $i)
							<option value="{{ $i }}">{{ $i }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-4 form-group">
					<label for="txtDni">DNI *</label>
					<input id="txtDni" name="txtDni" type="text" class="form-control form-control-sm" maxlength="8" placeholder="Ingrese DNI (Enter)" onkeyup="keyUpTxtDni(event)" onblur="getParntnerByDni(event)">
				</div>
				<div class="col-md-4 form-group">
					<label for="txtFullName">Nombre completo *</label>
					<input id="txtFullName" name="txtFullName" type="text" class="form-control form-control-sm" readonly>
				</div>
			</div>
			<div class="row">
				<div class="col-12 d-flex justify-content-end">
					<button type="submit" class="btn btn-primary">
						Registrar socio
					</button>
				</div>
			</div>
		</div>
	</form>
	<form class="my-2 pt-2 px-2 card"  action="{{ route('societies.members', $society->id) }}" method="GET">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="search">Buscar socio</label>
					<input type="search" id="search" name="search" class="form-control form-control-sm" value="{{ $search }}" placeholder="Información para búsqueda (Enter)" autofocus autocomplete="off">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="year">Año *</label>
					<select name="year" id="year" class="form-control select2" style="width: 100%;">
						<option></option>
						@foreach ($years as $i)
							<option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="">&nbsp;</label>
					<button type="submit" class="btn btn-primary btn-block">Buscar</button>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-12 form-group">
			<div class="card">
				<div class="card-body shadow-sm table-responsive p-0">
					<table class="table table-striped text-nowrap" style="min-width: 600px;">
						<thead>
							<tr class="bg-slate-300">
								<th>Año</th>
								<th>DNI</th>
								<th>Nombre completo</th>
								<th width="10%">Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($members as $item)
								<tr>
									<td>{{ $item->year }}</td>
									<td>{{ $item->member->dni }}</td>
									<td>{{ $item->member->full_name }}</td>
									<td>
										<butoon class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="right" title="Bienes y servicios" onclick="openAjaxModal('modal-xl', 'Editar bienes y servicios ({{ $item->member->full_name.' - '.$item->year }})', null, '{{ route('societies.editmemberassets', $item->id) }}', 'GET');"">
											<i class="fas fa-layer-group text-primary"></i>
										</butoon>
										<button class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="right" title="Eliminar" onclick="openFormConfirm('delete{{ $item->id }}societymember')">
											<i class="fas fa-trash text-danger"></i>
										</button>
										<form id="delete{{ $item->id }}societymember"
											action="{{ route('societies.deletemember', $item->id) }}" method="POST" hidden>
											@csrf
											@method('DELETE')
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 d-flex justify-content-end">
			{{ $members->links() }}
		</div>
	</div>
	@push('scripts')
		<script>
			_baseAppUrl = "{{ url('') }}";
		</script>
		<script src="{{ asset('resources/societies/members.js') }}"></script>
	@endpush
</x-admin-layout>
