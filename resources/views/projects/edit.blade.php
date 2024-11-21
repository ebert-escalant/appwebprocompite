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
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtCategory">Categoría *</label>
							<input id="txtCategory" name="txtCategory" type="text" class="form-control form-control-sm" value="{{ $project->category }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="txtAmountInversment">Monto de inversión *</label>
							<input id="txtAmountInversment" name="txtAmountInversment" type="text" class="form-control form-control-sm" value="{{ $project->investment_amount }}">
						</div>
					</div>
                    <div class="col-md-4">
                        <label for="txtConfinanceAmount">Cofinanciamineto solicitado *</label>
                        <input id="txtConfinanceAmount" name="txtConfinanceAmount" type="text" class="form-control form-control-sm" value="{{ $project->cofinance_amount }}">
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
