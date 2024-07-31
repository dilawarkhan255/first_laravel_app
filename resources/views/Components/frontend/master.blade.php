<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

        <style>
            .carousel-item img {
            height: 740px;
            width: 100%;
            object-fit: cover;
            }

            .header_back {
                /* background-color: #cccccc; */
                opacity: .85;
                color: white;
            }

            h3.card-logo-dark {
                color: white;
            }

            #w_word {
                color: #2a6ba3;
            }
            .navbar-collapse {
            flex-grow: 0 !important;
                }
            .homecard {
            transition:  0.3s ease-in;
            cursor: pointer;
            }
            .homecard:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
            }

            .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #030303;
            }

            .overlay h1,
            .overlay p {
                margin-bottom: 0;
            }
            .text-left a{
                text-decoration: none;
                font: bold;
                color: #030303;
            }

            .banner-image {
                height: 200px;
                object-fit: cover;
            }

            .overlay {
                background: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                text-align: center;
                position: absolute;
                display: flex;
                bottom: 0;
                right: 0;
                left: 0;
                top: 0;
            }
            .card-header a{
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        <nav class="navbar navbar-expand-lg fixed-top {{ Request::is('/') ? '' : 'navbar-light bg-light border-bottom' }}" style="opacity: 0.97;" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="/">
                    @if(Request::is('/'))
                        <h3 class="card-logo card-logo-light text-white">
                            <span id="w_word">J</span>OBSPORTAL
                        </h3>
                    @else
                        <h3 class="card-logo card-logo-light" style="color: #000000;">
                            <span id="w_word" style="color: #2f6293;">J</span>OBSPORTAL
                        </h3>
                    @endif
                </a>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <!-- Search Bar -->
                <form action="{{ route('view_job') }}" method="GET" class="d-flex mx-auto mt-3">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search Jobs" aria-label="Search" value="{{ isset($search_job) ? $search_job : '' }}" required>
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/" style="color: #000000">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('view_job') ? 'active' : '' }}" href="/view_job" style="color: #000000;">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="/login" style="color: #000000;">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('register') ? 'active' : '' }}" href="/register" style="color: #181818;">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        @yield('content')


        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>



