<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class=" flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <h1 style="font-size: 3rem; margin-bottom:.5rem">GENERAR REPORTE</h1>
        <div class="flex items-center justify-center w-full ">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row ">
            <form method="POST" action="{{ route('general.report.post') }}" class="flex flex-col gap-4 w-full " onsubmit="showLoader()">
                @csrf
                <label class="flex flex-col gap-1">
                    <span class="text-sm font-medium ">Desde</span>
                    <input type="date" name="start" class="border  rounded-sm px-5 py-2 text-sm" required
                    value="{{ now()->format('Y-m-d') }}"
                    >
                </label>
                <label class="flex flex-col gap-1">
                    <span class="text-sm font-medium ">Hasta</span>
                    <input type="date" name="end" class="border  rounded-sm px-5 py-2 text-sm" required
                    value="{{ now()->format('Y-m-d') }}"
                    >
                </label>
                <button type="submit" class="bg-[#1b1b18] text-white rounded-sm py-2 px-5 font-medium hover:bg-black transition-all">
                        Generar
                </button>
                <x-loading  />
            </form>
        </main>
    </div>
        

        <script>
            const loader = document.getElementById('loader')
            const submit = document.querySelector('button[type="submit"]')
            
            function showLoader() {
                loader.style.display = 'flex';
                submit.style.display = 'none';
                

                window.onblur = function () {
                    loader.style.display = 'none';
                    submit.style.display = 'block';
                };
            }
        </script>
    </body>
</html>
