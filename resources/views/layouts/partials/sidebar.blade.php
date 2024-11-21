<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            Pro<span>Compite</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Administración</li>
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Inicio</span>
                </a>
            </li>
			<li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Usuarios</span>
                </a>
            </li>
            <li class="nav-item nav-category">Mantenimientos</li>
            <li class="nav-item {{ request()->routeIs('partners.*') ? 'active' : '' }}">
                <a href="{{ route('partners.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Socios</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <a href="{{ route('projects.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="file-text"></i>
                    <span class="link-title">Plan de negocio</span>
                </a>
            </li>
			<li class="nav-item {{ request()->routeIs('societies.*') ? 'active' : '' }}">
                <a href="{{ route('societies.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="layers"></i>
                    <span class="link-title">Organización</span>
                </a>
            </li>
			<li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="file-plus"></i>
                    <span class="link-title">Importar socios</span>
                </a>
            </li>
			<li class="nav-item nav-category">Consultas</li>
			<li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="link-icon" data-feather="pie-chart"></i>
                    <span class="link-title">Reportes</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
