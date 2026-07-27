<nav class="navbar navbar-expand-lg navbar-custom px-4">

    <div class="container-fluid">

        {{-- Left Side --}}
        <div class="d-flex align-items-center">

            <button class="btn btn-light me-3 d-lg-none" id="toggleSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <h5 class="mb-0 fw-bold">
                @yield('title', 'Dashboard')
            </h5>

        </div>

        {{-- Right Side --}}
        <div class="d-flex align-items-center gap-3">

            {{-- Language --}}
            @php
                $currentLocale = app()->getLocale();
                $newLocale = $currentLocale == 'ar' ? 'en' : 'ar';
            @endphp

            <a href="{{ LaravelLocalization::getLocalizedURL($newLocale) }}"
               class="btn btn-light rounded-pill px-3">

                <i class="fa-solid fa-language me-2"></i>

                {{ strtoupper($newLocale) }}

            </a>

            {{-- Notifications --}}
            <button class="btn btn-light position-relative rounded-circle">

                <i class="fa-regular fa-bell"></i>

                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                    3

                </span>

            </button>

            {{-- Profile --}}
            <div class="dropdown">

                <a href="#"
                   class="dropdown-toggle d-flex align-items-center text-decoration-none text-dark"
                   data-bs-toggle="dropdown">

                    <img src="https://ui-avatars.com/api/?background=4f46e5&color=fff&name={{ urlencode(auth()->user()->name) }}"
                        width="42"
                        height="42"
                        class="rounded-circle shadow-sm">

                    <div class="ms-2 text-start">

                        <div class="fw-semibold">

                            {{ auth()->user()->name }}

                        </div>

                        <small class="text-muted">

                            {{ ucfirst(auth()->user()->role) }}

                        </small>

                    </div>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4">

                    <li>

                        <a class="dropdown-item py-2" href="#">

                            <i class="fa-regular fa-user me-2"></i>

                            Profile

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item py-2" href="#">

                            <i class="fa-solid fa-gear me-2"></i>

                            Settings

                        </a>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button class="dropdown-item text-danger py-2">

                                <i class="fa-solid fa-right-from-bracket me-2"></i>

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>