@extends('layouts.app')
@section('title', 'Riwayat Costing')
@section('page-title', 'Riwayat Costing Produksi')

@section('content')
<div class="max-w-full mx-auto space-y-4">

    {{-- ===== ALERT ERROR EXPORT (session flash) ===== --}}
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-start gap-3">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">

        <div class="flex flex-wrap -mx-3 mb-2">
            <div class="w-full md:w-1/6 md:mb-0">
                <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-city">
                    Tanggal Awal
                    <span id="rpm-auto-label" class="ml-1 hidden text-xs text-indigo-500">(dari master)</span></label>
                <input type="date" id="filter-tgl-start"
                        min="0.01" step="0.01" placeholder="Opsional"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-city" />
            </div>
            <div class="w-full md:w-1/6 px-3 d:mb-0">
                <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">Tanggal Akhir</label>
                <input type="date" id="filter-tgl-end" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                <p id="err-jumlah_mesin" class="field-error mt-1 hidden text-xs text-red-500"></p>
            </div>
            <div class="w-full md:w-1/6 px-3 md:mb-0">
                <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-city">Kode Produksi</label>
                <select id="filter-kode"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">Semua</option>
                </select>
            </div>
            <div class="w-full md:w-1/6 px-3 md:mb-0">
                <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">Mesin</label>
                <select id="filter-mesin"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">Semua</option>
                </select>
            </div>
        </div>

        

        <div class="flex flex-wrap gap-3 items-end">

            

            <button id="btn-filter" class="mt-4 border border-gray-200 hover:bg-gray-50 text-gray-600 font-medium px-4 py-2 rounded-lg text-sm transition">
                Filter
            </button>

            <button id="btn-reset" class="mt-4 border border-gray-200 hover:bg-gray-50 text-gray-600 font-medium px-4 py-2 rounded-lg text-sm transition">
                Reset
            </button>

            {{-- Tombol export — selalu di paling kanan, terpisah dari grup filter --}}
            <button id="btn-export"
                    class="mt-4 border border-gray-200 hover:bg-gray-50 text-gray-600
                           font-medium px-4 py-2 ml-auto bg-emerald-600 hover:bg-emerald-700 font-medium
                           px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                Export Excel
            </button>

           <a href="{{ route('costing.input') }}" id="btn-export"
                    class="mt-4 border border-gray-200 hover:bg-gray-50 text-gray-600
                           font-medium px-4 py-2 ml-auto bg-emerald-600 hover:bg-emerald-700 font-medium
                           px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                    <svg fill="#000000" width="15px" height="15px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.238 1l-.353.36-8.969 9.095-.03.045c-.061.099-.27.45-.583 1.088-.314.638-.7 1.51-1.012 2.492L1 15l.92-.291a18.163 18.163 0 0 0 2.492-1.012c.638-.314.987-.52 1.088-.584l.045-.029L15 3.762zM12 9v1h3V9zm-8.11 1.89l1.22 1.22-.178.175c.007-.005-.379.227-.961.514-.214.105-.536.222-.834.338l-.274-.274c.116-.298.233-.62.338-.834.287-.582.518-.966.514-.96zM10 11v1h5v-1zm-2 2v1h7v-1z" fill="gray" font-family="Ubuntu" font-size="15" font-weight="400" letter-spacing="0" style="line-height:125%;-inkscape-font-specification:Ubuntu;text-align:center" text-anchor="middle" word-spacing="0"/>
                    </svg>
                Input
            </a>
        </div>
    </div>

    {{-- ===== TABEL RIWAYAT ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- Info bar --}}
        <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100 bg-gray-50">
            <span id="info-total" class="text-xs text-gray-500">Memuat data...</span>
            <span class="text-xs text-gray-400">Klik header kolom untuk mengurutkan</span>
        </div>

        {{-- Tabel scroll horizontal --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="sort-header px-3 py-3 text-left font-medium text-gray-500 whitespace-nowrap cursor-pointer hover:text-gray-800 select-none"
                            data-col="tanggal">
                            Tanggal <span class="sort-icon text-gray-300">↕</span>
                        </th>
                        <th class="px-3 py-3 text-left font-medium text-gray-500 whitespace-nowrap">Kode</th>
                        <th class="px-3 py-3 text-left font-medium text-gray-500 whitespace-nowrap">Mesin</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Pick</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Panjang (cm)</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">RPM</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Efisiensi</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Jam</th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Jml</th>
                        <th class="sort-header px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap cursor-pointer hover:text-gray-800 select-none"
                            data-col="output_riil_pcs">
                            Output Pcs <span class="sort-icon text-gray-300">↕</span>
                        </th>
                        <th class="sort-header px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap cursor-pointer hover:text-gray-800 select-none"
                            data-col="total_biaya_hari">
                            Total Biaya <span class="sort-icon text-gray-300">↕</span>
                        </th>
                        <th class="sort-header px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap cursor-pointer hover:text-gray-800 select-none"
                            data-col="cost_per_pcs">
                            Cost/Pcs <span class="sort-icon text-gray-300">↕</span>
                        </th>
                        <th class="px-3 py-3 text-right font-medium text-gray-500 whitespace-nowrap">Cost/Kodi</th>
                    </tr>
                </thead>
                <tbody id="tabel-body">
                    <tr>
                        <td colspan="13" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div id="pagination-wrap"
             class="flex items-center justify-between px-4 py-3 border-t border-gray-100">
            <span id="pagination-info" class="text-xs text-gray-400"></span>
            <div id="pagination-btns" class="flex gap-1"></div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // STATE
    // =========================================================
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let state = {
        page:     1,
        sortBy:   'tanggal',
        sortDir:  'desc',
        tglStart: '',
        tglEnd:   '',
        kode:     '',
        idMesin:  '',
    };

    // =========================================================
    // HELPERS
    // =========================================================
    function rupiah(val) {
        if (val === null || val === undefined) return '—';
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    function angka(val, dec) {
        if (val === null || val === undefined) return '—';
        return parseFloat(val).toLocaleString('id-ID', { maximumFractionDigits: dec ?? 2 });
    }

    function tanggalIndo(str) {
        if (!str) return '—';
        const [y, m, d] = str.split('-');
        return d + '/' + m + '/' + y;
    }

    // =========================================================
    // LOAD DROPDOWN FILTER (mesin & produk)
    // =========================================================
    async function loadDropdowns() {
        try {
            const [resMesin, resProduk] = await Promise.all([
                fetch('/api/master/mesin',  { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
                fetch('/api/master/produk', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } }),
            ]);

            const mesinData  = await resMesin.json();
            const produkData = await resProduk.json();

            const selMesin = document.getElementById('filter-mesin');
            (mesinData.data || []).forEach(function (m) {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nama_mesin + ' (' + m.kode_mesin + ')';
                selMesin.appendChild(opt);
            });

            const selKode = document.getElementById('filter-kode');
            (produkData.data || []).forEach(function (p) {
                const opt = document.createElement('option');
                opt.value = p.kode_produksi;
                opt.textContent = p.kode_produksi + (p.konstruksi ? ' — ' + p.konstruksi : '');
                selKode.appendChild(opt);
            });

        } catch (e) {
            console.error('Gagal load dropdown filter:', e);
        }
    }

    // =========================================================
    // SET TANGGAL DEFAULT = BULAN BERJALAN
    // =========================================================
    function setDefaultTanggal() {
        const now   = new Date();
        const y     = now.getFullYear();
        const m     = String(now.getMonth() + 1).padStart(2, '0');
        const akhir = new Date(y, now.getMonth() + 1, 0).getDate();

        document.getElementById('filter-tgl-start').value = y + '-' + m + '-01';
        document.getElementById('filter-tgl-end').value   = y + '-' + m + '-' + String(akhir).padStart(2, '0');

        state.tglStart = y + '-' + m + '-01';
        state.tglEnd   = y + '-' + m + '-' + String(akhir).padStart(2, '0');
    }

    // =========================================================
    // FETCH DATA RIWAYAT
    // =========================================================
    async function fetchRiwayat() {
        const tbody = document.getElementById('tabel-body');
        tbody.innerHTML = '<tr><td colspan="13" class="px-4 py-10 text-center text-gray-400">Memuat...</td></tr>';

        const params = new URLSearchParams();
        if (state.tglStart) params.set('tanggal_start', state.tglStart);
        if (state.tglEnd)   params.set('tanggal_end',   state.tglEnd);
        if (state.kode)     params.set('kode_produksi', state.kode);
        if (state.idMesin)  params.set('id_mesin',      state.idMesin);
        params.set('sort_by',  state.sortBy);
        params.set('sort_dir', state.sortDir);
        params.set('page',     state.page);
        params.set('per_page', 20);

        try {
            const res  = await fetch('/api/costing/history?' + params.toString(), {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const json = await res.json();

            if (!res.ok) {
                tbody.innerHTML = '<tr><td colspan="13" class="px-4 py-10 text-center text-red-400">Gagal memuat data.</td></tr>';
                return;
            }

            renderTabel(json.data || []);
            renderPagination(json.meta || {});

        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="13" class="px-4 py-10 text-center text-red-400">Gagal terhubung ke server.</td></tr>';
        }
    }

    // =========================================================
    // RENDER TABEL
    // =========================================================
    function renderTabel(rows) {
        const tbody = document.getElementById('tabel-body');
        const info  = document.getElementById('info-total');

        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="13" class="px-4 py-10 text-center text-gray-400">Belum ada data untuk filter ini.</td></tr>';
            info.textContent = 'Tidak ada data';
            return;
        }

        tbody.innerHTML = rows.map(function (r) {
            return '<tr class="border-b border-gray-100 hover:bg-gray-50 transition">' +
                '<td class="px-3 py-2.5 whitespace-nowrap">' + tanggalIndo(r.tanggal) + '</td>' +
                '<td class="px-3 py-2.5"><span class="bg-indigo-50 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded">' + r.kode_produksi + '</span></td>' +
                '<td class="px-3 py-2.5 whitespace-nowrap">' + (r.mesin || '—') + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + (r.pick || '—') + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + angka(r.panjang_pcs, 0) + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + angka(r.rpm, 0) + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + (r.efisiensi_pct !== null ? r.efisiensi_pct + '%' : '—') + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + angka(r.jam_kerja, 0) + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + (r.jumlah_mesin || '—') + '</td>' +
                '<td class="px-3 py-2.5 text-right font-medium">' + angka(r.output_riil_pcs, 2) + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + rupiah(r.total_biaya_hari) + '</td>' +
                '<td class="px-3 py-2.5 text-right font-semibold text-emerald-600">' + rupiah(r.cost_per_pcs) + '</td>' +
                '<td class="px-3 py-2.5 text-right font-semibold text-emerald-600">' + rupiah(r.cost_per_kodi) + '</td>' +
            '</tr>';
        }).join('');
    }

    // =========================================================
    // RENDER PAGINATION
    // =========================================================
    function renderPagination(meta) {
        const info  = document.getElementById('pagination-info');
        const wrap  = document.getElementById('pagination-btns');
        const total = document.getElementById('info-total');

        total.textContent = 'Total ' + (meta.total || 0) + ' data';
        info.textContent  = 'Halaman ' + (meta.current_page || 1) + ' dari ' + (meta.last_page || 1);

        wrap.innerHTML = '';
        if (!meta.last_page || meta.last_page <= 1) return;

        const btnClass = 'border border-gray-200 rounded-lg px-3 py-1 text-xs cursor-pointer transition ';

        // Prev
        const prev = document.createElement('button');
        prev.textContent = '← Prev';
        prev.className   = btnClass + (meta.current_page <= 1 ? 'opacity-40 cursor-not-allowed bg-gray-50 text-gray-400' : 'hover:bg-gray-50 text-gray-600');
        prev.disabled    = meta.current_page <= 1;
        prev.addEventListener('click', function () {
            if (state.page > 1) { state.page--; fetchRiwayat(); }
        });
        wrap.appendChild(prev);

        // Nomor halaman — tampilkan max 5 di sekitar current
        const start = Math.max(1, meta.current_page - 2);
        const end   = Math.min(meta.last_page, meta.current_page + 2);

        for (let i = start; i <= end; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className   = btnClass + (i === meta.current_page
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'hover:bg-gray-50 text-gray-600');
            btn.addEventListener('click', (function (page) {
                return function () { state.page = page; fetchRiwayat(); };
            }(i)));
            wrap.appendChild(btn);
        }

        // Next
        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.className   = btnClass + (meta.current_page >= meta.last_page ? 'opacity-40 cursor-not-allowed bg-gray-50 text-gray-400' : 'hover:bg-gray-50 text-gray-600');
        next.disabled    = meta.current_page >= meta.last_page;
        next.addEventListener('click', function () {
            if (state.page < meta.last_page) { state.page++; fetchRiwayat(); }
        });
        wrap.appendChild(next);
    }

    // =========================================================
    // SORT — klik header kolom
    // =========================================================
    document.querySelectorAll('.sort-header').forEach(function (th) {
        th.addEventListener('click', function () {
            const col = this.dataset.col;

            // Toggle arah sort jika kolom sama
            if (state.sortBy === col) {
                state.sortDir = state.sortDir === 'desc' ? 'asc' : 'desc';
            } else {
                state.sortBy  = col;
                state.sortDir = 'desc';
            }
            state.page = 1;

            // Update ikon sort di semua header
            document.querySelectorAll('.sort-header').forEach(function (el) {
                const icon = el.querySelector('.sort-icon');
                if (el.dataset.col === state.sortBy) {
                    icon.textContent = state.sortDir === 'desc' ? ' ↓' : ' ↑';
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-indigo-500');
                } else {
                    icon.textContent = ' ↕';
                    icon.classList.add('text-gray-300');
                    icon.classList.remove('text-indigo-500');
                }
            });

            fetchRiwayat();
        });
    });

    // =========================================================
    // TOMBOL FILTER & RESET
    // =========================================================
    document.getElementById('btn-filter').addEventListener('click', function () {
        state.page     = 1;
        state.tglStart = document.getElementById('filter-tgl-start').value;
        state.tglEnd   = document.getElementById('filter-tgl-end').value;
        state.kode     = document.getElementById('filter-kode').value;
        state.idMesin  = document.getElementById('filter-mesin').value;
        fetchRiwayat();
    });

    document.getElementById('btn-reset').addEventListener('click', function () {
        state.page     = 1;
        state.kode     = '';
        state.idMesin  = '';
        state.sortBy   = 'tanggal';
        state.sortDir  = 'desc';

        document.getElementById('filter-kode').value  = '';
        document.getElementById('filter-mesin').value = '';

        // Reset tanggal ke bulan berjalan
        setDefaultTanggal();

        // Reset ikon sort
        document.querySelectorAll('.sort-header .sort-icon').forEach(function (icon) {
            icon.textContent = ' ↕';
            icon.classList.add('text-gray-300');
            icon.classList.remove('text-indigo-500');
        });

        fetchRiwayat();
    });

    // =========================================================
    // EXPORT EXCEL — redirect browser (bukan fetch) agar trigger download
    // =========================================================
    document.getElementById('btn-export').addEventListener('click', function () {
        const params = new URLSearchParams();
        if (state.tglStart) params.set('tanggal_start', state.tglStart);
        if (state.tglEnd)   params.set('tanggal_end',   state.tglEnd);
        if (state.kode)     params.set('kode_produksi', state.kode);
        if (state.idMesin)  params.set('id_mesin',      state.idMesin);

        window.location.href = '/costing/export?' + params.toString();
    });

    // =========================================================
    // INIT
    // =========================================================
    setDefaultTanggal();
    loadDropdowns();
    fetchRiwayat();

});
</script>
@endsection