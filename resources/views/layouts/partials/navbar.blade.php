<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <ul class="navbar-nav">
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i data-feather="user"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="figure mb-3">
                            <i data-feather="user" width="36" height="36"></i>
                        </div>
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ auth()->user()->name }}</p>
                            <p class="email text-muted mb-3">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            <li class="nav-item">
                                <a href="/" class="nav-link">
                                    <i data-feather="user"></i>
                                    <span>Perfil</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" onclick="document.getElementById('frmLogout').submit()">
                                    <i data-feather="log-out"></i>
                                    <span>Cerrar sesión</span>
                                </a>
								<form id="frmLogout" action="{{ route('logout') }}" method="POST">
									@csrf
								</form>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
