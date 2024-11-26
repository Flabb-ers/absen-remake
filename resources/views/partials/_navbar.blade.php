<style>.nav-item .nav-profile p {
    margin-top: 5px; 
    margin-bottom: 0;
}.nav-item.nav-profile {
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo me-5" href="index.html">
            <img src="{{ asset('/images/logo.png') }}" class="me-2" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.html">
            <img src="{{ asset('/images/logomini.png') }}" alt="logo" style="padding-right: 2px;padding-left:2px" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right d-flex align-items-center"> 
            <li class="nav-item nav-profile dropdown">
                <p class="d-flex align-items-center mr-2 mb-0">{{ session()->get('user.nama') }}</p> 
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <img src="{{ asset('/images/faces/face28.jpg') }}" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item">
                        <i class="ti-settings text-primary"></i> Settings </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="ti-power-off text-primary"></i> Logout
                            </button>
                        </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
