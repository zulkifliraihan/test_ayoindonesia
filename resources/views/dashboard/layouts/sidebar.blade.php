    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="javascript:void(0)">
                        <h2 class="brand-text">Nutech</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.index') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboard">
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.teams.index') }}">
                        <i data-feather="framer"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboard">
                            Teams
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.players.index') }}">
                        <i data-feather="life-buoy"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboard">
                            Players
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->
