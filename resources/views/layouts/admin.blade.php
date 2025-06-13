<!DOCTYPE html>
<html lang="en">
<head>
    <title> @stack('title')</title>
    <meta charset="utf-8" />
    <meta property="og:title" content="{{Config::get('global.SITE_NAME')}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:site_name" content="ResumeBuilderAdmin" />
    {{-- <link rel="shortcut icon" href="{{asset('/')}}assets/media/logos/favicon.png" /> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{asset('/')}}assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/themes')}}/{{Config::get('global.SITE_THEME_COLOR')}}?v={{time()}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}assets/css/custom.css?v={{time()}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('/')}}assets/js/custom.js?v={{time()}}"></script>
    <!-- <link href="{{asset('/')}}assets/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="{{asset('/')}}assets/css/style.css?v={{time()}}" rel="stylesheet">
    <script> var admin_url='{{url('/')}}'; </script>
    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
<style>


</style>
</head>
<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">

    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @yield('header')
                @yield('toolbar')
                @yield('content')
                @yield('models')
                @yield('footer')
            </div>
        </div>
    </div>
    @yield('script')
</body>
</html>
