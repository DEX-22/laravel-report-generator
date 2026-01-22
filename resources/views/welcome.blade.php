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
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class=" flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <h1 style="font-size: 3rem; margin-bottom:.5rem">GENERAR REPORTE</h1>
        <div class="flex items-center justify-center w-full ">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row ">
                <form class="flex flex-col gap-4 w-full " id="reportForm">
                    @csrf
                    <label class="flex flex-col gap-1">
                        <span class="text-sm font-medium ">Desde</span>
                        <input type="date" id="start" name="start" class="border  rounded-sm px-5 py-2 text-sm" required
                        value="{{ now()->format('Y-m-d') }}"
                        >
                    </label>
                    <label class="flex flex-col gap-1">
                        <span class="text-sm font-medium ">Hasta</span>
                        <input type="date" id="end" name="end" class="border  rounded-sm px-5 py-2 text-sm" required
                        value="{{ now()->format('Y-m-d') }}"
                        >
                    </label>
                    <button type="submit" id="generateReportBtn" class="bg-[#1b1b18] text-white rounded-sm py-2 px-5 font-medium hover:bg-black transition-all">
                            Generar
                    </button>
                    <x-loading  />
                </form>
            </main>
        </div>
        
        <div id="reportResult" class="mt-6 w-full max-w-2xl mx-auto"></div>
        <script>
            const $token = document.querySelector('meta[name="csrf-token"]')
            const loader = document.getElementById('loader') 
            const startInput = document.querySelector('#start')
            const endInput = document.querySelector('#end')
            const generateReportBtn = document.querySelector("#generateReportBtn")
            const reportForm = document.getElementById('reportForm')
            const reportResult = document.getElementById('reportResult')

            reportForm.onsubmit = generateReport

            

            async function generateReport(e){
                e.preventDefault()
                reportResult.innerHTML = ""
                reportResult.style.display = "block"
                showLoader()
                const url = "{{ route('general.report.post') }}"
                const start = startInput.value
                const end = endInput.value

                try {
                    const response = await fetch(url,{
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": $token.content
                        },
                        body: JSON.stringify({
                            start,
                            end
                        })
                    })

                    if(!response.ok) {
                        reportResult.innerHTML = `<div class='text-red-600'>Error al generar el reporte. Código: ${response.status}</div>`
                        hideLoader()
                    }
                } catch (err) {
                    reportResult.innerHTML = `<div class='text-red-600'>Error de red o del servidor.</div>`
                } 
            }

            function showLoader() {
                if(loader) loader.style.display = 'flex';
                if(generateReportBtn) generateReportBtn.style.display = 'none';
            }
            function hideLoader(url = null) {
                if(loader) loader.style.display = 'none';
                if(generateReportBtn) generateReportBtn.style.display = 'block';

                if(!!url)
                    showLink(url)
            }
            function showLink(url){

                reportResult.innerHTML = `<a href="${url}" style="color: blue;" onclick="hideLink()"><u>Descargar reporte</u></a>`
            }
            function hideLink(){
                reportResult.style.display = "none"
            }
            function showError(error){
                reportResult.innerHTML = error
            }
        </script>
    </body>
</html>
