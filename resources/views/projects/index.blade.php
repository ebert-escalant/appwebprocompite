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
                <a href="{{ route('projects.insert') }}" class="btn btn-primary btn-sm elevation-3" data-toggle="tooltip" data-placement="right" title="Agregar">
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
								<th>Código</th>
                                <th>Nombre del plan de negocio</th>
								<th>Categoría</th>
								<th>Monto total de inversión</th>
								<th>Cofinanciamiento solicitado</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
								<tr>
									<td>{{ $item->code }}</td>
									<td>{{ $item->name }}</td>
									<td>{{ $item->category}}</td>
									<td>{{ $item->investment_amount }}</td>
									<td>{{ $item->cofinance_amount }}</td>
									<td align="right">
										<a class="btn bg-default btn-sm px-1 py-0" href="{{ route('projects.edit', $item->id) }}" data-toggle="tooltip" data-placement="right" title="Editar" >
											<i class="fas fa-edit text-success"></i>
										</a>
										<button class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip"
											data-placement="right" title="Eliminar"
											onclick="openFormConfirm('delete{{ $item->id }}project')">
											<i class="fas fa-trash text-danger"></i>
										</button>
										<form id="delete{{ $item->id }}project"
											action="{{ route('projects.delete', $item->id) }}" method="POST" hidden>
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