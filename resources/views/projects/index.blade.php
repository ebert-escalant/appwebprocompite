<x-admin-layout>
    <div class="row">
        <div class="form-group col-md-8 order-1 order-md-0">
            <form id="divSearch" method="get" class="row">
                <div class="col-md-8">
					<div class="input-group input-group-sm">
						<div class="input-group-append">
							<span class="input-group-text">
								<i class="fas fa-search"></i>
							</span>
						</div>
						<input type="search" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Información para búsqueda (Enter)" autofocus autocomplete="off">
					</div>
				</div>
				<div class="col-md-4">
					<select name="year" id="year" class="form-control form-control-sm select2" onchange="this.form.submit()">
						<option value="all" {{ $year == 'all' ? 'selected' : '' }}>Todos</option>
						@foreach ($years as $i)
							<option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
						@endforeach
					</select>
				</div>
            </form>
        </div>
        <div class="form-group col-md-4 order-0 order-md-1 d-flex justify-content-end align-items-center">
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
                                <th>Nombre del plan de negocio</th>
								<th>Categoría</th>
								<th>Monto inversión</th>
								<th>Cofinanciamiento</th>
								<th>Liquidación</th>
								<th>Calificación</th>
								<th>Año</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
								<tr>
									<td style="word-wrap: break-word; white-space: normal; max-width: 200px;">{{ $item->name }}</td>
									<td>{{ $item->category}}</td>
									<td>{{ $item->investment_amount }}</td>
									<td>{{ $item->cofinance_amount }}</td>
									<td>{{ $item->liquidation == 1 ? 'Sí': 'No'}}</td>
									<td>
										@if($item->qualification == 1)
											<i style="font-size:1.5rem" class="fas fa-grin text-danger"></i>
										@elseif($item->qualification == 2)
											<i style="font-size:1.5rem" class="fas fa-smile text-success"></i>
										@elseif($item->qualification == 3)
											<i style="font-size:1.5rem" class="fas fa-meh text-warning"></i>
										@elseif($item->qualification == 4)
											<i style="font-size:1.5rem" class="fas fa-frown-open text-danger"></i>
										@elseif($item->qualification == 5)
											<i style="font-size:1.5rem" class="fas fa-frown text-danger"></i>
										@else
											<i style="font-size:1.5rem" class="fas fa-question-circle text-muted"></i> <!-- Calificación no válida -->
										@endif
									</td>
									<td>{{ $item->year }}</td>
									<td align="right">
										<butoon class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="left" title="Socios" onclick="openAjaxModal('modal-xl', 'Editar socios', null, '{{ route('projects.members', $item->id) }}', 'GET');">
											<i class="fas fa-users text-info"></i>
										</butoon>
										<butoon class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="left" title="Bienes y servicios" onclick="openAjaxModal('modal-xl', 'Editar bienes y servicios ({{ $item->name.' - '.$item->year }})', null, '{{ route('projects.editassets', $item->id) }}', 'GET');">
											<i class="fas fa-layer-group text-primary"></i>
										</butoon>
										<butoon class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="left" title="Liquidar plan de negocio" onclick="openAjaxModal('modal-lg', 'Datos de ({{ $item->name.' - '.$item->year }})', null, '{{ route('projects.editqualification', $item->id) }}', 'GET');">
											<i class="fas fa-star text-warning"></i>
										</butoon>
										<button class="btn bg-default btn-sm px-1 py-0" data-toggle="tooltip" data-placement="left" title="Descargar archivo" onclick="window.open('{{ route('projects.downloadfile', $item->id) }}');" @disabled(!$item->file)>
											<i class="fas fa-download text-primary"></i>
										</button>
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
	{{-- @push('scripts')
		<script src="{{ asset('resources/projects/members.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush --}}
</x-admin-layout>