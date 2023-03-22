<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>

<body>
    <nav class="flex justify-between items-center h-20 w-full bg-indigo-100 px-12 shadow-xl">
        <div>
            <a href="/">
                <img src='{{ URL::asset('images/logo.png') }}' alt='logo' class="h-16" />
            </a>
        </div>
        <div class="flex gap-5">
            <a href='' class="font-semibold text-xl">Login</a>
            <a href='' class="font-semibold text-xl">Register</a>
        </div>
    </nav>
    <div class="mt-12 bg-gray-200 w-full">
        <div class="w-1/2 mx-auto py-12">
            <h1 class="text-2xl font-bold text-center">{{ $listing->title }}</h1>
            <h2 class="text-xl font-semibold text-center pt-8">{{ $listing->company }}</h2>
            <div
                class="w-3/4 mx-auto bg-orange-400 font-bold text-white text-xl text-center pt-2 h-12 rounded-3xl mt-6">
                <p class="capitalize">{{ $listing->tags }}</p>
            </div>
            <div class="flex gap-6 w-3/4 mx-auto justify-center items-center mt-6">
                <img src='{{ URL::asset('images/location.png') }}' alt='' class="h-8" />
                <h4 class="text-xl font-bold">{{ $listing->company }}</h4>
            </div>
            <div class="w-3/4 mx-auto mt-10">
                <h2 class="text-center text-2xl text-black font-bold underline">Job description</h2>
                <p class="text-center text-xl font-semibold pt-6">{{ $listing->description }}</p>
            </div>
            <div class="mt-8 gap-8 w-1/2 mx-auto">
                <button
                    class="bg-[#eb4432] h-12 w-full rounded-full text-white font-semibold text-xl capitalize">Contact
                    the employee</button>
                <button
                    class="bg-black h-12 w-full rounded-full text-white font-semibold text-xl capitalize mt-4">visite
                    website</button>
            </div>
        </div>
    </div>
    <div class="h-16 bg-[#eb4432] w-full bottom-0 flex justify-center items-center">
        <h5 class="text-xl text-white font-bold">Made by <a class=""
                href="https://wa.me/2349036228765">Kaytwolea</a></h5>
    </div>
</body>

</html>
