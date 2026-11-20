<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Administrator - SIMJ GBI Shekinah Glory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#001428",
                        "primary-container": "#0f2942",
                        secondary: "#775a19",
                        "secondary-container": "#fed488",
                        "secondary-fixed": "#ffdea5",
                        surface: "#faf9f6",
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-primary min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Ambient Glows -->
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-secondary-fixed/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-primary-container blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl relative z-10 border border-white/20">
        <!-- Church Branding -->
        <div class="flex flex-col items-center text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center text-secondary-fixed shadow-lg mb-4">
                <span class="material-symbols-outlined text-[32px]">church</span>
            </div>
            <h1 class="font-serif-display text-2xl font-bold text-primary">GBI Shekinah Glory</h1>
            <p class="text-xs uppercase tracking-widest text-secondary font-bold mt-1">Sistem Informasi Manajemen Jemaat</p>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2.5 mb-6">
            <span class="material-symbols-outlined text-red-600 text-[18px] shrink-0 mt-0.5">error</span>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs flex items-start gap-2.5 mb-6">
            <span class="material-symbols-outlined text-green-600 text-[18px] shrink-0 mt-0.5">check_circle</span>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-primary mb-1.5">Email Administrator</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[20px]">mail</span>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="admin@gbishekinahglory.org" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-secondary transition bg-gray-50/50 focus:bg-white">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-primary mb-1.5">Kata Sandi</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[20px]">lock</span>
                    <input type="password" name="password" id="password" required placeholder="••••••••••••" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-secondary transition bg-gray-50/50 focus:bg-white">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                    <input type="checkbox" name="remember" class="rounded text-secondary focus:ring-0">
                    <span>Ingat Sesi Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-primary hover:bg-primary-container text-white font-bold text-sm shadow-xl transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">login</span>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center gap-2 text-xs text-gray-500 text-center">
            <a href="{{ route('public.beranda') }}" class="hover:text-primary transition flex items-center gap-1 font-medium">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Website Profil</span>
            </a>
            <p class="text-[11px] text-gray-400 mt-2">Area Terbatas • Hanya untuk pengurus gereja berwenang</p>
        </div>
    </div>
</body>
</html>
