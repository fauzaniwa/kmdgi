<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KMDGI 16')</title>

    <!-- Preconnect Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fallback Font dari Google Fonts jika file SN Pro belum ada -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Konfigurasi Font SN Pro Custom (Hilangkan komentar jika kamu punya file .woff2 nya di folder public/fonts) -->
    <style>
        /*
        @font-face {
            font-family: 'SN Pro'; 
            src: url('/fonts/SNPro-Regular.woff2') format('woff2');
            font-weight: 400;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('/fonts/SNPro-Bold.woff2') format('woff2');
            font-weight: 700;
        }
        */
    </style>

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Master Style Guide (Tailwind Config) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        /* Mengatur SN Pro sebagai font utama. Jika gagal, akan menggunakan Source Sans 3 */
                        sans: ['"SN Pro"', '"Source Sans 3"', 'sans-serif'],
                    },
                    colors: {
                        kmdgi: {
                            primary: '#126CFD',
                            hover: '#0D55D0',
                            bgRight: '#F0F6FF',
                            50: '#F0F6FF',
                            100: '#E1EDFF',
                            200: '#BDDAFF',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen">
    
    <!-- Konten dari file-file lain akan di-render di sini -->
    @yield('content')

</body>
</html>