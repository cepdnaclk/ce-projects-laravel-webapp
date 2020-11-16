<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top_">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Projects - Department of Computer Engineering</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteNamed('home') ? 'active' : '' }}" href="/">Home</a>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed('about') ? 'active' : '' }}">
                    <a class="nav-link" href="/about/">About</a>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed('docs') ? 'active' : '' }}">
                    <a class="nav-link" href="/docs/">Documentation</a>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed('contact') ? 'active' : '' }}">
                    <a class="nav-link" href="/contact/">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
