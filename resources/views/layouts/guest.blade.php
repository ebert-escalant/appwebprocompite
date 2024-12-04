<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Procompite</title>
    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('vendors/core/core.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendors/toastr/toastr.min.css') }}">
    <link rel="shortcut icon" href="{{asset('images/full_logo_without.webp')}}" type="image/x-icon">
</head>

<body class="sidebar-dark">
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                {{ $slot }}
            </div>
        </div>
    </div>

	<!-- scripts -->
    <script src="{{ asset('vendors/core/core.js') }}"></script>
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
	<script src="{{ asset('vendors/toastr/toastr.min.js') }}"></script>
	<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
	@stack('scripts')
	<script>
		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": true,
			"timeOut": "5000"
		}
	</script>
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
                    toastr['{{ Session::get('type') }}']('{{ Session::get('redirectMessages')[0] }}', 'Información');
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
