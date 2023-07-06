<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>

<body class="flex">
    {{-- Sidebar start --}}

    <section class="h-screen bg-gradient-to-r from-orange-500 to-[#eb4432] w-[20%] pt-24">
        <div class="flex justify-center">
            <img src='{{ URL::asset('images/logo.png') }}' alt='logo' class="h-16" />
        </div>
        <div class="mt-20 w-3/4 mx-auto">
            <div class="space-y-10">
                <div>
                    <a href="/"
                        class="text-[#eb4432] text-xl font-semibold px-6 py-3 bg-white rounded-3xl">Dashboard</a>
                </div>
                <div>
                    <a href="/" class="text-white text-xl font-semibold  bg-transparent rounded-3xl">Listings</a>
                </div>
                <div>
                    <a href="/" class="text-white text-xl font-semibold  bg-transparent rounded-3xl">Users</a>
                </div>
                <div>
                    <a href="/" class="text-white text-xl font-semibold  bg-transparent rounded-3xl">Settings</a>
                </div>
            </div>
        </div>
    </section>
    <div class="w-[80%] h-screen bg-gray-100">
        @yield('content')
    </div>
    <script>
        const navlink = [{
                name: 'Dashboard',
                path: '/'
            },
            {
                name: 'Listings',
                path: '/'
            },
            {
                name: 'Users',
                path: '/'
            },
            {
                name: 'Settings',
                path: '/'
            },
        ];
        // const mapped = navlink.map(item =>  )
    </script>
</body>

</html>
