<x-admin-layout>
    <div class="row">
        <div class="form-group col-md-7 order-1 order-md-0">
            <form id="divSearch" method="get">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="search" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Información para búsqueda (Enter)" autofocus autocomplete="off">
                </div>
            </form>
        </div>
        <div class="form-group col-md-5 order-0 order-md-1 d-flex justify-content-end align-items-center">
            <div class="d-flex justify-content-end align-items-center" style="gap: 6px;">
                <a href="{{ route('societies.insert') }}" class="btn btn-primary btn-sm elevation-3" data-toggle="tooltip" data-placement="right" title="Agregar">
                    <i class="fas fa-plus fa-lg"></i> AGREGAR
                </a>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-12 d-flex justify-content-end">
			{{ $data->links() }}
		</div>
	</div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body shadow-lg table-responsive p-0">
                    <table class="table table-striped text-nowrap" style="min-width: 600px;">
                        <thead>
                            <tr class="bg-slate-300">
								<th>RUC</th>
                                <th>Razón social</th>
								<th>Fecha de constitución</th>
								<th>Número de partida</th>
								<th>Distrito</th>
								<th>Provincia</th>
								<th>Departamento</th>
								<th>Comunidad</th>
								<th>Dirección</th>
								<th>Teléfono</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
								<tr>
									<td>{{ $item->ruc }}</td>
									<td>{{ $item->social_razon }}</td>
									<td>{{ date('d-m-Y', strtotime($item->constitution_date)) }}</td>
									<td>{{ $item->part_number }}</td>
									<td>{{ $item->district }}</td>
									<td>{{ $item->province }}</td>
									<td>{{ $item->department }}</td>
									<td>{{ $item->comunity }}</td>
									<td>{{ $item->address }}</td>
									<td>{{ $item->phone }}</td>
									<td align="right">
										<a class="btn bg-default btn-sm px-1 py-0" href="{{ route('societies.members', $item->id) }}" data-toggle="tooltip" data-placement="right" title="Socios" >
											<i class="fas fa-users text-primary"></i>
										</a>
										<a class="btn bg-default btn-sm px-1 py-0" href="{{ route('societies.edit', $item->id) }}" data-toggle="tooltip" data-placement="right" title="Editar" >
											<i class="fas fa-edit text-success"></i>
										</a>
										<button class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip"
											data-placement="right" title="Eliminar"
											onclick="openFormConfirm('delete{{ $item->id }}society')">
											<i class="fas fa-trash text-danger"></i>
										</button>
										<form id="delete{{ $item->id }}society"
											action="{{ route('societies.delete', $item->id) }}" method="POST" hidden>
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
    </div>
</x-admin-layout>