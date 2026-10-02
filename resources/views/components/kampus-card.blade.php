@php 
    // Logic untuk merapikan URL Instagram menjadi @username
    $usernameKampus = '';
    if (!empty($kampus->medsos_kampus)) {
        $parsedUrl = parse_url($kampus->medsos_kampus, PHP_URL_PATH);
        $username = $parsedUrl ? basename($parsedUrl) : $kampus->medsos_kampus;
        $usernameKampus = '@' . str_replace('@', '', $username);
    }

    $usernameProdi = '';
    if (!empty($kampus->ig_prodi)) {
        $parsedUrl = parse_url($kampus->ig_prodi, PHP_URL_PATH);
        $username = $parsedUrl ? basename($parsedUrl) : $kampus->ig_prodi;
        $usernameProdi = '@' . str_replace('@', '', $username);
    }
@endphp

<li class="flex flex-col p-4 md:p-5 border border-slate-100 rounded-2xl bg-white shadow-sm hover:shadow-md transition-all group">
    <div class="flex items-start gap-4">
        <!-- Logo Kampus -->
        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-slate-50 border border-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
            @if(!empty($kampus->logo_institusi))
                <img src="{{ asset('storage/'.$kampus->logo_institusi) }}" alt="Logo {{ $kampus->nama_institusi }}" class="w-full h-full object-cover">
            @else
                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            @endif
        </div>
        
        <!-- Info Kampus -->
        <div class="flex-grow overflow-hidden">
            <div class="flex justify-between items-start gap-2 mb-1">
                <h3 class="text-sm md:text-base font-bold text-slate-800 leading-snug group-hover:text-[#1A68FF] transition-colors truncate">
                    {{ $kampus->nama_institusi }}
                </h3>
                <span class="text-[9px] md:text-[10px] font-bold px-2 py-1 rounded-md border border-slate-200 text-slate-500 whitespace-nowrap bg-slate-50">
                    {{ $kampus->pivot->status_keanggotaan ?? $kampus->status_keanggotaan ?? 'Anggota' }}
                </span>
            </div>

            <!-- Media Sosial (Hanya Username) -->
            <div class="flex flex-wrap gap-2 mt-3">
                @if($usernameKampus)
                    <a href="{{ $kampus->medsos_kampus }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-[#FF6B9E]/10 text-slate-600 hover:text-[#FF6B9E] text-[11px] font-semibold transition-colors border border-slate-100 hover:border-[#FF6B9E]/30 max-w-full">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" clip-rule="evenodd"/></svg>
                        <span class="truncate">{{ $usernameKampus }}</span>
                    </a>
                @endif
                
                @if($usernameProdi)
                    <a href="{{ $kampus->ig_prodi }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-[#1A68FF]/10 text-slate-600 hover:text-[#1A68FF] text-[11px] font-semibold transition-colors border border-slate-100 hover:border-[#1A68FF]/30 max-w-full">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" clip-rule="evenodd"/></svg>
                        <span class="truncate">{{ $usernameProdi }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</li>