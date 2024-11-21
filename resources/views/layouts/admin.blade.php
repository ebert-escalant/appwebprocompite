<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Procompite</title>
    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('vendors/core/core.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/jquery-tags-input/jquery.tagsinput.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/dropzone/dropzone.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/dropify/dist/dropify.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}">
    <!-- end plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css?x='.env('CACHE_UPDATE_DATE')) }}">
    <link rel="stylesheet" href="{{ asset('vendors/toastr/toastr.min.css') }}">
    <!-- Styles / Scripts -->
    {{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif --}}
	<style>
		.form-group label {
			font-size: 13px;
			margin-bottom: .2rem;
			font-weight: 500;
		}
	</style>
	@stack('styles')
</head>

<body class="sidebar-dark">
    <!-- Spinner for lazyload modules -->
    <div class="spinner-wrapper" style="display: none;" id="appGlobalLoader">
        <div class="spinner"></div>
    </div>

    <div class="main-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('layouts.partials.sidebar')
        <!-- partial -->

        <div class="page-wrapper">

            <!-- partial:partials/_navbar.html -->
            @include('layouts.partials.navbar')
            <!-- partial -->

            <div class="page-content">
				{{ $slot }}
            </div>
			<div id="divGlobalContent"></div>
            <!-- partial:partials/_footer.html -->
            @include('layouts.partials.footer')
            <!-- partial -->
        </div>
    </div>

	<!-- jQuery -->
	<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('vendors/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- core:js -->
    <script src="{{ asset('vendors/core/core.js') }}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="{{ asset('vendors/jquery-validation/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
	<script src="{{ asset('vendors/inputmask/jquery.inputmask.min.js') }}"></script>
	<script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
	<script src="{{ asset('vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
	<script src="{{ asset('vendors/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
	<script src="{{ asset('vendors/dropzone/dropzone.min.js') }}"></script>
	<script src="{{ asset('vendors/dropify/dist/dropify.min.js') }}"></script>
	<script src="{{ asset('vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
	<script src="{{ asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('vendors/moment/moment.min.js') }}"></script>
	<script src="{{ asset('vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
	<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- end plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendors/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <!-- endinject -->
	<!-- custom js for this page -->
	<script src="{{ asset('js/form-validation.js') }}"></script>
	<script src="{{ asset('js/bootstrap-maxlength.js') }}"></script>
	<script src="{{ asset('js/inputmask.js') }}"></script>
	<script src="{{ asset('js/typeahead.js') }}"></script>
	<script src="{{ asset('js/tags-input.js') }}"></script>
	<script src="{{ asset('js/dropzone.js') }}"></script>
	<script src="{{ asset('js/dropify.js') }}"></script>
	<script src="{{ asset('js/bootstrap-colorpicker.js') }}"></script>
	<script src="{{ asset('js/datepicker.js') }}"></script>
	<script src="{{ asset('js/timepicker.js') }}"></script>
	<!-- end custom js for this page -->
    <!-- custom js for this page -->
	<script src="{{ asset('js/app.js?x='.env('CACHE_UPDATE_DATE')) }}"></script>
    <!-- end custom js for this page -->
    @stack('scripts')
    <script>
        $(function() {
            @if (Session::has('redirectMessages'))
                @if (Session::get('type') == 'error' || Session::get('type') == 'exception')
                    @foreach (Session::get('redirectMessages') as $value)
                        @if (trim($value) != '')
                            toastr.error('{{ $value }}', 'No se pudo proceder');
                        @endif
                    @endforeach
                @elseif (Session::get('type') == 'info' || Session::get('type') == 'warning')
                    toastr['{{ Session::get('type') }}']('{{ Session::get('redirectMessages')[0] }}',
                        'Información');
                @else
                    swal.fire({
                        title: '{{ Session::get('type') == 'success' ? 'Correcto' : 'Alerta' }}',
                        text: '{!! Session::get('redirectMessages')[0] !!}',
                        icon: '{{ Session::get('type') }}',
                        timer: '{{ Session::get('type') == 'success' ? '3000' : '8000' }}',
                    });
                @endif
            @endif
        });
    </script>
</body>

</html>
