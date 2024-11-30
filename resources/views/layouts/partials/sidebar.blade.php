<nav class="sidebar">
    <div class="sidebar-header" style="padding: 0 20px;">
        <a href="/" class="sidebar-brand" style="display: inline-block;">
			<svg width="240" height="50" viewBox="0 0 240 50" xmlns="http://www.w3.org/2000/svg">
				<defs>
					<filter id="outline">
						<feMorphology in="SourceAlpha" result="DILATED" operator="dilate" radius="2"></feMorphology>
						<feFlood flood-color="white" flood-o25pacity="1" result="WHITE"></feFlood>
						<feComposite in="WHITE" in2="DILATED" operator="in" result="OUTLINE"></feComposite>
						<feMerge>
							<feMergeNode in="OUTLINE"></feMergeNode>
							<feMergeNode in="SourceGraphic"></feMergeNode>
						</feMerge>
					</filter>
				</defs>
				<text x="10" y="35" font-family="Arial, sans-serif" font-size="28" font-weight="900" fill="#ea2225" filter="url(#outline)">Pro</text>
				<text x="63" y="35" font-family="Arial, sans-serif" font-size="28" font-weight="900" fill="#00a54f" filter="url(#outline)">Compite</text>
			</svg>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body" style="">
        <div style="background-image: url('{{asset('images/procompite_aside.jpg')}}'); background-size: cover; height:100%;width:auto;position:absolute;bottom:0;left:0;right:0;top:0;opacity:.2"></div>
        <ul class="nav">
            <li class="nav-item nav-category">Administración</li>
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Inicio</span>
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
        </ul>
    </div>
</nav>
