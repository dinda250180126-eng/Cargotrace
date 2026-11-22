<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - CargoTrace</title>
    
    <!-- Script Ajaib Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'navy-dark': '#1e3a5f',    /* Warna kotak login */
                        'blue-accent': '#3a6ea5',  /* Warna input */
                    },
                    fontFamily: {
                        'sans': ['"Nunito"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen relative font-sans flex items-center justify-center p-4">

    <!-- Latar Belakang Foto Pesawat Nyata (Resolusi Tinggi) -->
    <img src="https://images.unsplash.com/photo-1542296332-2e4473faf563?q=80&w=2070&auto=format&fit=crop"
         alt="Real Airplane Flight"
         class="absolute inset-0 w-full h-full object-cover z-0">

    <!-- Overlay Gradien Biru Corporate (Memberi efek elegan & memudahkan teks dibaca) -->
    <div class="absolute inset-0 bg-gradient-to-br from-navy-dark/95 via-blue-900/80 to-blue-500/60 z-0"></div>

    <!-- Kartu Login Navy (Mengambang di Tengah) -->
    <div class="relative z-10 bg-navy-dark/95 backdrop-blur-md p-10 sm:p-12 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] w-full max-w-[400px] border border-white/10">
        
        <!-- Judul -->
        <h2 class="text-4xl font-black text-white text-center mb-10 tracking-wide">Login</h2>

        <!-- Pesan Error Bawaan Laravel -->
        <x-auth-session-status class="mb-4 text-white font-bold" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Input Username / Email -->
            <div>
                <div class="relative">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Username"
                           class="w-full bg-blue-accent text-white placeholder-blue-100 border-none rounded-full px-6 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-white transition-all shadow-inner">
                    <!-- Ikon User -->
                    <div class="absolute right-5 top-4 text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-yellow-300 ml-4" />
            </div>

            <!-- Input Password -->
            <div>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                           placeholder="Password"
                           class="w-full bg-blue-accent text-white placeholder-blue-100 border-none rounded-full px-6 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-white transition-all shadow-inner">
                    <!-- Ikon Gembok -->
                    <div class="absolute right-5 top-4 text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-yellow-300 ml-4" />
            </div>

            <!-- Lupa Password -->
            <div class="flex justify-start">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-white hover:text-sky-200 transition-colors">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <!-- Tombol Login -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-white text-navy-dark hover:bg-gray-100 font-black text-lg py-3.5 rounded-full shadow-xl transition-transform transform hover:-translate-y-1">
                    Login
                </button>
            </div>

        </form>
    </div>
    
</body>
</html>