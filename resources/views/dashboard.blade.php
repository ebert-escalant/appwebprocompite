<x-admin-layout>
    <div class="d-flex justify-content-between" style="flex-wrap: wrap;">
		<h4 class="mb-3">
			¡Bienvenido, {{ auth()->user()->name }}!
		</h4>
		<div class="dropdown">
			<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Configuración
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<button type="button" class="dropdown-item" onclick="openAjaxModal('modal-xl', 'Agregar provincia', null, '{{ route('ubigeo.add-province') }}', 'GET');">Agregar provincia</button>
				<button type="button" class="dropdown-item" onclick="openAjaxModal('modal-xl', 'Agregar distrito', null, '{{ route('ubigeo.add-district') }}', 'GET');">Agregar distrito</button>
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Organizaciones</h6>
					</div>
					<div class="d-flex justify-content-between">
						<h3 class="text-success">{{ $quantities['societies'] }}</h3>
						<a href="{{ route('societies.index') }}"><i data-feather="layers" class="text-primary icon-xxl"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Planes de negocio</h6>
					</div>
					<div class="d-flex justify-content-between">
						<h3 class="text-success">{{ $quantities['projects'] }}</h3>
						<a href="{{ route('projects.index') }}"><i data-feather="file-text" class="text-primary icon-xxl"></i></a>
					</div>
				</div>
			</div>
		</div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Socios</h6>
                    </div>
					<div class="d-flex justify-content-between">
						<h3 class="text-success">{{ $quantities['partners'] }}</h3>
						<a href="{{ route('partners.index') }}"><i data-feather="users" class="text-primary icon-xxl"></i></a>
					</div>
                </div>
            </div>
        </div>
    </div>
	<form class="card my-2">
		<div class="card-body p-2">
			<div class="row">
				<div class="col-md-4">
					<label for="Año">Año *</label>
					<select name="year" id="year" class="form-control select2" style="width: 100%;">
						<option></option>
						@foreach ($years as $i)
							<option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label for="">&nbsp;</label>
					<button type="submit" class="btn btn-primary btn-block">Buscar</button>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body table-responsive p-0">
					<table class="table table-striped table-valign-middle">
						<thead>
							<tr>
								<th>RUC</th>
								<th>Nombre</th>
								<th>Representante legal</th>
								<th>Plan de negocio</th>
								<th>Total de socios</th>
								<th>Fecha de registro</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($projects as $item)
								<tr>
									<td>{{ $item->society->ruc }}</td>
									<td>{{ $item->society->social_razon }}</td>
									<td>{{ $item->society->representative?->full_name }}</td>
									<td>{{ $item->name }}</td>
									<td>{{ $item->project_members_count }}</td>
									<td>{{ $item->society->created_at->format('d/m/Y H:i:s') }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</x-admin-layout>
