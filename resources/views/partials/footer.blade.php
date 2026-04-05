<footer class="mt-12 border-t-4 border-[#dc2626] bg-[#111921] pb-8 pt-16 text-white">
    <div class="mx-auto max-w-[1280px] px-4 lg:px-10">
        <div class="mb-12 grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 shrink-0 rounded-md bg-white p-1">
                        <img
                            src="{{ asset('images/Icon.png') }}"
                            alt="Logo Kota Blitar"
                            class="h-full w-full object-contain"
                        >
                    </div>
                    <div>
                        <h3 class="text-lg font-bold leading-tight">Kota Blitar</h3>
                        <p class="text-xs text-slate-400">Dinas Kebudayaan &amp; Pariwisata</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Platform resmi penyewaan aset daerah Kota Blitar. Mewujudkan tata kelola aset yang transparan dan akuntabel.
                </p>
            </div>

            <div class="lg:justify-self-center">
                <h4 class="relative mb-6 inline-block text-lg font-bold after:absolute after:-bottom-2 after:left-0 after:h-0.5 after:w-8 after:bg-[#dc2626]">Menu Utama</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li class="flex items-center gap-2">
                        <span aria-hidden="true">›</span>
                        <a href="{{ route('home') }}" class="transition-colors hover:text-[#dc2626]">Beranda</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span aria-hidden="true">›</span>
                        <a href="{{ route('fasilitas.index') }}" class="transition-colors hover:text-[#dc2626]">Katalog Aset</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span aria-hidden="true">›</span>
                        <a href="{{ route('home') }}#jadwal" class="transition-colors hover:text-[#dc2626]">Cek Jadwal</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span aria-hidden="true">›</span>
                        <a href="{{ route('fasilitas.index') }}" class="transition-colors hover:text-[#dc2626]">Prosedur Sewa</a>
                    </li>
                </ul>
            </div>

            <div class="lg:justify-self-end lg:max-w-sm">
                <h4 class="relative mb-6 inline-block text-lg font-bold after:absolute after:-bottom-2 after:left-0 after:h-0.5 after:w-8 after:bg-[#dc2626]">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 shrink-0 text-[#dc2626]">●</span>
                        <span>Jl. Ir. Soekarno, Kepanjen Lor, Kec. Kepanjenkidul, Kota Blitar, Jawa Timur 66112</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="shrink-0 text-[#dc2626]">●</span>
                        <span>(0342) 801815</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="shrink-0 text-[#dc2626]">●</span>
                        <span>disparbud@blitarkota.go.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-800 pt-8 md:flex-row">
            <p class="text-sm text-slate-500">© {{ date('Y') }} Dinas Kebudayaan dan Pariwisata Kota Blitar. All rights reserved.</p>
            <div class="flex gap-6 text-sm text-slate-500">
                <a href="#" class="transition-colors hover:text-[#dc2626]">Kebijakan Privasi</a>
                <a href="#" class="transition-colors hover:text-[#dc2626]">Syarat &amp; Ketentuan</a>
            </div>
        </div>
    </div>
</footer>