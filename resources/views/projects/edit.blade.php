<x-admin-layout>
	<h4 class="card-title">Editar Plan de negocio</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmEditProject" method="POST" action="{{ route('projects.edit',$project->id) }}">
				@csrf
				@method('PUT')
				<div class="form-group">
					<label for="txtPlanNegocio">Nombre del plan de negocio *</label>
					<input id="txtPlanNegocio" name="txtPlanNegocio" type="text" class="form-control form-control-sm" value="{{ $project->name }}">
				</div>
				<div class="row">
					<div class="col-md-4 form-group">
						<label for="txtCategory">Categoría *</label>
						<select name="txtCategory" id="txtCategory" class="form-control select2">
							<option></option>
							<option value="Categoría A" {{$project->category == 'Categoría A' ? 'selected' : ''}}>Categoría A</option>
							<option value="Categoría B" {{$project->category == 'Categoría B' ? 'selected' : ''}}>Categoría B</option>
							<option value="Categoría C" {{$project->category == 'Cateogría C' ? 'selected' : ''}}>Categoría C</option>
						</select>
					</div>
					<div class="col-md-4 form-group">
						<label for="txtAmountInversment">Monto de inversión *</label>
						<input id="txtAmountInversment" name="txtAmountInversment" type="text" class="form-control form-control-sm" value="{{ $project->investment_amount }}">
					</div>
                    <div class="col-md-4 form-group">
                        <label for="txtConfinanceAmount">Cofinanciamineto solicitado *</label>
                        <input id="txtConfinanceAmount" name="txtConfinanceAmount" type="text" class="form-control form-control-sm" value="{{ $project->cofinance_amount }}">
                    </div>
				</div>
				<div class="row">
					<div class="col-md-4 form-group">
						<label for="txtYear">Año *</label>
						<select name="txtYear" id="txtYear" class="form-control select2" style="width: 100%;">
							<option></option>
							@foreach ($years as $year)
								<option value="{{ $year }}" {{$project->year == $year ? 'selected' : ''}}>{{ $year }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-8 form-group">
						<label for="txtSociety">Organización *</label>
						<select name="txtSociety" id="txtSociety" class="form-control select2" style="width: 100%;">
							<option></option>
							@foreach ($societies as $item)
								<option value="{{ $item->id }}" {{$project->society_id == $item->id ? 'selected' : ''}}>{{ $item->social_razon }}</option>
							@endforeach
						</select>
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
		<script src="{{ asset('resources/projects/edit.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>
