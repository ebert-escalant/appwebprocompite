<x-admin-layout>
	<h4 class="card-title">Registrar plan de negocio</h4>
	<div class="card">
		<div class="card-body">
			<form id="frmInsertProject" method="POST" action="{{ route('projects.insert') }}">
				@csrf
				<div class="form-group">
					<label for="txtPlanNegocio">Nombre plan de negocio *</label>
					<input id="txtPlanNegocio" name="txtPlanNegocio" type="text" class="form-control form-control-sm">
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtCode">Código *</label>
							<input id="txtCode" name="txtCode" type="text" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtCategory">Categoría *</label>
							<input id="txtCategory" name="txtCategory" type="text" class="form-control form-control-sm">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtAmountInversment">Monto de inversión *</label>
							<input id="txtAmountInversment" name="txtAmountInversment" type="text" class="form-control form-control-sm">
						</div>
					</div>
                    <div class="col-md-3">
                        <label for="txtConfinanceAmount">Cofinanciamineto solicitado *</label>
                        <input id="txtConfinanceAmount" name="txtConfinanceAmount" type="text" class="form-control form-control-sm">
                    </div>
				</div>
				<div class="d-flex align-items-center justify-content-end">
					<button class="btn btn-primary" type="submit">Registrar datos</button>
				</div>
			</form>
		</div>
	</div>
	@push('scripts')
		<script>
			var _baseAppUrl = "{{ url('') }}";
		</script>
		<script src="{{ asset('resources/projects/insert.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
	@endpush
</x-admin-layout>