@push('head')
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
	<style>
		.material-symbols-outlined {
			font-variation-settings: 'FILL' 0, 'wght' 450, 'GRAD' 0, 'opsz' 24;
		}
	</style>
@endpush

<x-app-layout>
	<x-slot name="header">
		<div>
			<h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Daftar Aset Tersedia</h2>
			<p class="mt-1 text-sm text-slate-600">Pilih fasilitas untuk melanjutkan proses booking.</p>
		</div>
	</x-slot>

	<section class="min-h-screen bg-slate-50 py-10">
		<div class="mx-auto w-full max-w-[1280px] px-4 sm:px-6 lg:px-8">
			<div class="mb-10 rounded-2xl bg-gradient-to-r from-[#c62828] to-[#8e1717] px-6 py-8 text-white shadow-xl md:px-10">
				<p class="text-xs font-semibold uppercase tracking-[0.25em] text-yellow-200">Portal Penyewaan Aset</p>
				<h1 class="mt-3 text-3xl font-black leading-tight md:text-5xl">Temukan Fasilitas untuk Acara Anda</h1>
				<p class="mt-4 max-w-3xl text-sm leading-relaxed text-white/90 md:text-base">
					Jelajahi gedung dan ruangan yang tersedia, lalu pilih aset untuk langsung lanjut ke form booking.
				</p>
			</div>

			<form method="GET" action="{{ route('fasilitas.index') }}" class="sticky top-24 z-30 mb-10 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm backdrop-blur md:p-5">
				<div class="flex flex-col gap-4 lg:flex-row lg:items-center">
					<div class="relative flex-1">
						<span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
						<input
							type="text"
							name="q"
							value="{{ $filters['q'] }}"
							placeholder="Cari nama fasilitas atau kategori..."
							class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-4 text-sm text-slate-800 focus:border-[#c62828] focus:ring-[#c62828]"
						>
					</div>

					<div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:w-auto">
						<select
							name="kategori"
							class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-[#c62828] focus:ring-[#c62828]"
						>
							<option value="">Semua Kategori</option>
							@foreach ($kategoriList as $kategori)
								<option value="{{ $kategori }}" @selected($filters['kategori'] === $kategori)>{{ $kategori }}</option>
							@endforeach
						</select>

						<select
							name="sort"
							class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-[#c62828] focus:ring-[#c62828]"
						>
							<option value="name_asc" @selected($filters['sort'] === 'name_asc')>Nama A-Z</option>
							<option value="price_asc" @selected($filters['sort'] === 'price_asc')>Harga Termurah</option>
							<option value="price_desc" @selected($filters['sort'] === 'price_desc')>Harga Tertinggi</option>
						</select>

						<button
							type="submit"
							class="inline-flex items-center justify-center rounded-xl bg-[#c62828] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#b71c1c]"
						>
							Terapkan Filter
						</button>
					</div>
				</div>
			</form>

			@if ($fasilitas->count() === 0)
				<div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900">
					<p class="text-sm font-semibold">Data fasilitas tidak ditemukan untuk filter yang dipilih.</p>
				</div>
			@else
				<div class="grid grid-cols-1 gap-7 sm:grid-cols-2 xl:grid-cols-4">
					@foreach ($fasilitas as $item)
						@php
							$statusMap = [
								'available' => ['label' => 'Tersedia', 'badge' => 'bg-emerald-500'],
								'rented' => ['label' => 'Terbooking', 'badge' => 'bg-orange-500'],
								'maintenance' => ['label' => 'Perawatan', 'badge' => 'bg-slate-500'],
							];
							$status = $statusMap[$item->status_fasilitas] ?? ['label' => 'Tidak Diketahui', 'badge' => 'bg-slate-500'];
							$image = $item->gambar_fasilitas ? asset($item->gambar_fasilitas) : 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=900&q=80';
						@endphp

						<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
							<div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
								<img src="{{ $image }}" alt="{{ $item->nama_fasilitas }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
								<div class="absolute left-4 top-4">
									<span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-white {{ $status['badge'] }}">
										{{ $status['label'] }}
									</span>
								</div>
							</div>

							<div class="flex flex-1 flex-col p-5">
								<p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $item->nama_kategori ?? 'Tanpa Kategori' }}</p>
								<h3 class="mb-3 text-lg font-extrabold leading-tight text-slate-900 transition group-hover:text-[#c62828]">{{ $item->nama_fasilitas }}</h3>

								<div class="mb-4 space-y-2 text-sm text-slate-600">
									<div class="flex items-center gap-2">
										<span class="material-symbols-outlined text-[19px] text-[#c62828]">groups</span>
										<span>Kapasitas {{ $item->kapasitas }}</span>
									</div>
									<p class="line-clamp-2 text-slate-500">{{ $item->deskripsi ?: 'Belum ada deskripsi fasilitas.' }}</p>
								</div>

								<div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
									<div>
										<p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Mulai dari</p>
										<p class="text-lg font-black text-[#c62828]">
											Rp {{ number_format((float) $item->harga_mulai, 0, ',', '.') }}
											<span class="text-xs font-normal text-slate-400">/hari</span>
										</p>
									</div>

									<a
										href="{{ route('booking.create', ['fasilitas_id' => $item->id]) }}"
										class="inline-flex items-center justify-center rounded-full bg-slate-100 p-2.5 text-[#c62828] transition hover:bg-[#c62828] hover:text-white"
										title="Lanjut booking"
									>
										<span class="material-symbols-outlined">arrow_forward</span>
									</a>
								</div>
							</div>
						</article>
					@endforeach
				</div>

				<div class="mt-10">
					{{ $fasilitas->links() }}
				</div>
			@endif
		</div>
	</section>
</x-app-layout>
