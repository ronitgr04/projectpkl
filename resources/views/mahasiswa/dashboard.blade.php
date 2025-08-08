{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Laravel</title>

    @vite('resources/css/app.css')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

</head>

<body class="">
    <div className="">

        @extends('layouts.contentMahasiswa')

        @section('content')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            <p class="text-gray-600">
                Selamat Datang di Aplikasi Absensi dan Kegiatan Harian Mahasiswa berbasis Web.
                Sebuah sistem yang memungkinkan para Mahasiswa PKL di Badan Kominfo Kabupaten Sidikalang
                melakukan absensi dan mencatat kegiatan harian dari website. Sistem ini diharapkan dapat memberi
                kemudahan setiap Mahasiswa PKL untuk melakukan absensi dan mencatat kegiatan harian.
            </p>
        </div>
        @endsection

    </div>
</body>

</html> --}}

{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite('resources/css/app.css')

    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fbfd;
            color: #333;
        }

        main {
            padding: 15px 20px 0 20px;
            min-height: 300px;
        }

        h1 {
            font-weight: 700;
            font-size: 18px;
            margin: 0 0 15px 0;
            color: #222;
        }

        .breadcrumb {
            font-size: 13px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .breadcrumb span {
            color: #6c757d;
        }

        .breadcrumb a {
            color: #007bff;
        }

        .cards {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .card {
            border-radius: 3px;
            color: white;
            flex: 1 1 180px;
            max-width: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 90px;
        }

        .card .content {
            padding: 15px 15px 0 15px;
        }

        .card .content .number {
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .card .content .desc {
            font-weight: 400;
            font-size: 13px;
        }

        .card-footer {
            background-color: rgba(0, 0, 0, 0.15);
            padding: 7px 15px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 0 0 3px 3px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-footer i {
            margin-left: 5px;
            font-size: 12px;
        }

        .card-1 {
            background-color: #17a2b8;
        }

        .card-1 .card-footer {
            background-color: #138496;
        }

        .card-2 {
            background-color: #28a745;
        }

        .card-2 .card-footer {
            background-color: #1e7e34;
        }

        .card-3 {
            background-color: #ffc107;
            color: #212529;
        }

        .card-3 .card-footer {
            background-color: #d39e00;
            color: #212529;
        }

        .card-4 {
            background-color: #dc3545;
        }

        .card-4 .card-footer {
            background-color: #bd2130;
        }

        @media (max-width: 600px) {
            .cards {
                flex-direction: column;
                max-width: 320px;
                margin: 0 auto;
            }

            .card {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="">
    <div className="">

        @extends('layouts.contentMahasiswa')

        @section('content')
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            <p class="text-gray-600">
                Selamat Datang di Aplikasi Absensi dan Kegiatan Harian Mahasiswa berbasis Web.
                Sebuah sistem yang memungkinkan para Mahasiswa PKL di Badan Kominfo Kabupaten Sidikalang
                melakukan absensi dan mencatat kegiatan harian dari website. Sistem ini diharapkan dapat memberi
                kemudahan setiap Mahasiswa PKL untuk melakukan absensi dan mencatat kegiatan harian.
            </p><br><br><br>
                   
        <main>
          

            <div class="cards">
                <div class="card card-1">
                    <div class="content">
                        <div class="number">150</div>
                        <div class="desc">Jumlah Mahasiswa</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-2">
                    <div class="content">
                        <div class="number">53<span style="font-weight: 700;">%</span></div>
                        <div class="desc">Bounce Rate</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-3">
                    <div class="content">
                        <div class="number">44</div>
                        <div class="desc">User Registrations</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-4">
                    <div class="content">
                        <div class="number">65</div>
                        <div class="desc">Unique Visitors</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
            </div>
        </main>
        @endsection

    </div>
        </div>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite('resources/css/app.css')

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fbfd;
            color: #333;
        }

        main {
            padding: 15px 20px 0 20px;
            min-height: 300px;
        }

        .breadcrumb {
            font-size: 13px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .breadcrumb span {
            color: #6c757d;
        }

        .cards {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .card {
            border-radius: 3px;
            color: white;
            flex: 1 1 180px;
            max-width: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 90px;
        }

        .card .content {
            padding: 15px 15px 0 15px;
        }

        .card .content .number {
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .card .content .desc {
            font-weight: 400;
            font-size: 13px;
        }

        .card-footer {
            background-color: rgba(0, 0, 0, 0.15);
            padding: 7px 15px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 0 0 3px 3px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-footer i {
            margin-left: 5px;
            font-size: 12px;
        }

        .card-1 {
            background-color: #17a2b8;
        }

        .card-1 .card-footer {
            background-color: #138496;
        }

        .card-2 {
            background-color: #28a745;
        }

        .card-2 .card-footer {
            background-color: #1e7e34;
        }

        .card-3 {
            background-color: #ffc107;
            color: #212529;
        }

        .card-3 .card-footer {
            background-color: #d39e00;
            color: #212529;
        }

        .card-4 {
            background-color: #dc3545;
        }

        .card-4 .card-footer {
            background-color: #bd2130;
        }

        .alert-reminder {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffeeba;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .clock-box {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        @media (max-width: 600px) {
            .cards {
                flex-direction: column;
                max-width: 320px;
                margin: 0 auto;
            }

            .card {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div className="">

        @extends('layouts.contentMahasiswa')

        @section('content')
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-2">Selamat Datang, {{ auth()->user()->username }}.</h2>
            {{-- <p class="text-gray-600">
                Selamat Datang di Aplikasi Absensi dan Kegiatan Harian Mahasiswa berbasis Web.
                Sebuah sistem yang memungkinkan para Mahasiswa PKL di Badan Kominfo Kabupaten Sidikalang
                melakukan absensi dan mencatat kegiatan harian dari website. Sistem ini diharapkan dapat memberi
                kemudahan setiap Mahasiswa PKL untuk melakukan absensi dan mencatat kegiatan harian.
            </p> --}}
           <br>
             <main>

            <!-- Real-Time Clock -->
            <div class="clock-box" id="clock">Memuat waktu...</div>

            <!-- Reminder Notification -->
            <div class="alert-reminder">
                <strong>Reminder:</strong> Jangan lupa isi absen anda pada jam <strong>09.00 WIB</strong> hari ini.
            </div>
            <br><br>
            <!-- Dashboard Cards -->
            {{-- <div class="cards">
                <div class="card card-1">
                    <div class="content">
                        <div class="number">0</div>
                        <div class="desc">Jumlah Mahasiswa</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-2">
                    <div class="content">
                        <div class="number">0<span style="font-weight: 700;">%</span></div>
                        <div class="desc">#########</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-3">
                    <div class="content">
                        <div class="number">0</div>
                        <div class="desc">Mahasiswa Aktif</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
                <div class="card card-4">
                    <div class="content">
                        <div class="number">0</div>
                        <div class="desc">Mahasiswa Tidak Aktif</div>
                    </div>
                    <div class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></div>
                </div>
            </div> --}}
        </main>
        @endsection

    </div>

    <!-- Real-time Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById("clock").innerText = now.toLocaleString('id-ID', options);
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
        </div>

       
</body>

</html>

