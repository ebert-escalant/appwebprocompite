<x-guest-layout>
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 pr-md-0">
                        <div style="width: 100%;height: 100%;background-size: cover;background-repeat: no-repeat;background-image: url('{{ asset('images/signinimg.webp') }}');">
                        </div>
                    </div>
                    <div class="col-md-8 pl-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
							<figure style="width: 100%; display: flex; justify-content:center;">
								<img src="{{ asset('images/full_logo.webp') }}" height="120" alt="">
							</figure>
                            <h5 class="text-muted font-weight-normal my-3">Ingresa tus credenciales para acceder a la plataforma.</h5>
                            <form class="forms-sample" method="POST" action="{{ route('login') }}">
								@csrf
                                <div class="form-group">
                                    <label for="email">Usuario</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password">
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="remember_me">
                                        Mantener sesión iniciada
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-block text-white">
										Login
									</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
