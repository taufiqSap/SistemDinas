<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-slate-900">Pengaturan Profil</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola informasi akun dan keamanan Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-white py-12">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 sm:px-8">
                    <h3 class="flex items-center gap-3 text-lg font-bold text-slate-900">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#c62828] text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        Informasi Profil
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 sm:px-8">
                    <h3 class="flex items-center gap-3 text-lg font-bold text-slate-900">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        Keamanan Akun
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-red-200 bg-red-50">
                <div class="border-b border-red-200 bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 sm:px-8">
                    <h3 class="flex items-center gap-3 text-lg font-bold text-red-900">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-600 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </span>
                        Zona Berbahaya
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
