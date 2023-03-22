<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>

<body class="bg-gray-100">
    <nav class="flex justify-between items-center h-20 w-full bg-indigo-100 px-12 shadow-xl">
        <div>
            <img src='{{ URL::asset('images/logo.png') }}' alt='logo' class="h-16" />
        </div>
        <div class="flex gap-5">
            <a href='' class="font-semibold text-xl">Login</a>
            <a href='' class="font-semibold text-xl">Register</a>
        </div>
    </nav>
    <div class="w-full h-72 bg-gradient-to-r from-orange-500 to-[#eb4432] flex justify-center items-center">
        <div>
            <h1 class="text-6xl text-white font-bold uppercase text-center">lara<span class="text-black">job</span></h1>
            <h2 class="text-center font-semibold text-white pt-4 text-base capitalize">Find or Post Laravel Job or
                project</h2>
            <div
                class="mt-4 w-56 h-10 border-4 rounded-full mx-auto text-white text-base items-center flex justify-center font-semibold">
                <a>Sign up to list a gig</a>
            </div>
        </div>
    </div>
    <div class="w-full flex justify-center my-8 gap-4">
        <input type="text" placeholder="Search Laravel Gigs"
            class="border w-56 rounded-full focus:border-orange-400 focus:border-2 text-center" />
        <button class="bg-orange-400 w-20 rounded-full text-white font-semibold h-8 capitalize ">search</button>
    </div>
    <div class="mt-8 w-5/6 mx-auto mb-12">
        <div class="w-full grid lg:grid-cols-3 gap-6">
            @foreach ($listings as $listing)
                <div class="rounded-3xl h-80 bg-gray-100 border-4 border-orange-400 px-6 pt-4 hover:shadow-2xl">
                    <img src="{{ URL::asset('images/laravel.png') }}" alt="" class="h-12" />
                    <h1 class="text-base font-semibold text-gray-900">{{ $listing->title }}</h1>
                    <h2 class="pt-6 text-base font-bold">{{ $listing->company }}</h2>
                    <p class="capitalize mt-4 text-sm font-semibold text-orange-600">{{ $listing->tags }}</p>
                    <p class="font-bold pt-4">Location: {{ $listing->location }}</p>
                    <a href="/listings/{{ $listing['id'] }}"
                        class="text-orange-400 font-bold text-sm font-italic pt-8">View description</a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="h-16 bg-[#eb4432] w-full bottom-0 flex justify-center items-center">
        <h5 class="text-xl text-white font-bold">Made by <a class=""
                href="https://wa.me/2349036228765">Kaytwolea</a></h5>
    </div>
</body>

</html>
