<x-admin-layout>
    <h4 class="mb-3">
		¡Bienvenido, {{ auth()->user()->name }}!
	</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Socios</h6>
                    </div>
					<div class="d-flex justify-content-between">
						<h3 class="text-success">{{ $quantities['partners'] }}</h3>
						<i data-feather="users" class="text-primary icon-xxl"></i>
					</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-baseline">
						<h6 class="card-title mb-0">Sociedades</h6>
					</div>
					<div class="d-flex justify-content-between">
						<h3 class="text-success">{{ $quantities['societies'] }}</h3>
						<i data-feather="layers" class="text-primary icon-xxl"></i>
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
						<i data-feather="file-text" class="text-primary icon-xxl"></i>
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
								<th>Total de socios</th>
								<th>Fecha de registro</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($societies as $item)
								<tr>
									<td>{{ $item->ruc }}</td>
									<td>{{ $item->social_razon }}</td>
									<td>{{ $item->representative?->full_name }}</td>
									<td>{{ $item->society_members_count }}</td>
									<td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</x-admin-layout>
