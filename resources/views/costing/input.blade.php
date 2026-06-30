@extends('layouts.app')
@section('title', 'Input Produksi')
@section('page-title', 'Input Produksi Harian')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- ===== ALERT ERROR GLOBAL ===== --}}
    <div id="alert-global" class="hidden bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-start gap-3">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span id="alert-global-text"></span>
    </div>

    {{-- ===== CARD 1: IDENTITAS PRODUKSI ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Identitas Produksi</h2>
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block text-xs text-gray-500 mb-1">
                    Tanggal produksi <span class="text-red-400">*</span>
                </label>
                <input type="date" id="tanggal" name="tanggal"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-tanggal" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">
                    Kode produksi <span class="text-red-400">*</span>
                </label>
                <select id="kode_produksi" name="kode_produksi"
                        class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">— Pilih kode produksi —</option>
                    @foreach($produkList as $produk)
                        <option value="{{ $produk->kode_produksi }}">
                            {{ $produk->kode_produksi }} — {{ $produk->konstruksi }}
                        </option>
                    @endforeach
                </select>
                <p id="err-kode_produksi" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

        </div>
    </div>

    {{-- ===== CARD 2: PARAMETER MESIN ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Parameter Mesin</h2>

        <div class="grid grid-cols-3 gap-4 mb-4">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Mesin <span class="text-red-400">*</span></label>
                <select id="id_mesin" name="id_mesin"
                        class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">— Memuat mesin... —</option>
                </select>
                <p id="err-id_mesin" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">
                    RPM aktual
                    <span id="rpm-auto-label" class="hidden text-indigo-500 text-xs ml-1">(dari master)</span>
                </label>
                <input type="number" id="rpm_aktual" name="rpm_aktual"
                       min="0.01" step="0.01" placeholder="Opsional"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p class="text-xs text-gray-400 mt-1">Kosongkan untuk pakai rpm default</p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Jumlah mesin <span class="text-red-400">*</span></label>
                <input type="number" id="jumlah_mesin" name="jumlah_mesin"
                       min="1" step="1" value="1"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-jumlah_mesin" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

        </div>

        <div class="grid grid-cols-3 gap-4">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Jam kerja <span class="text-red-400">*</span></label>
                <input type="number" id="jam_kerja" name="jam_kerja"
                       min="0.01" max="24" step="0.01" value="24"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-jam_kerja" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Efisiensi (%) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="number" id="efisiensi" name="efisiensi"
                           min="0" max="100" step="0.01" value="80"
                           class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 pr-8 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    <span class="absolute right-3 top-2.5 text-xs text-gray-400">%</span>
                </div>
                <p id="err-efisiensi" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

        </div>
    </div>

    {{-- ===== CARD 3: PARAMETER KAIN ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Parameter Kain</h2>
        <div class="grid grid-cols-3 gap-4">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Slah / sisir (helai/inch) <span class="text-red-400">*</span></label>
                <input type="number" id="slah_sisir" name="slah_sisir"
                       min="1" step="1"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-slah_sisir" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Pick / pakan (helai/inch) <span class="text-red-400">*</span></label>
                <input type="number" id="pick" name="pick"
                       min="1" step="1"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-pick" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Panjang pcs (cm) <span class="text-red-400">*</span></label>
                <input type="number" id="panjang_pcs" name="panjang_pcs"
                       min="0.01" step="0.01"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <p id="err-panjang_pcs" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

        </div>
    </div>

    {{-- ===== CARD 4: KOMPONEN BIAYA ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Komponen Biaya Harian (Rp)</h2>
        <div class="grid grid-cols-5 gap-3">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Upah operator</label>
                <input type="number" id="biaya_upah_operator" data-biaya="upah_operator"
                       min="0" step="1000" value="0"
                       class="biaya-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Listrik mesin</label>
                <input type="number" id="biaya_listrik_mesin" data-biaya="listrik_mesin"
                       min="0" step="1000" value="0"
                       class="biaya-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Maintenance</label>
                <input type="number" id="biaya_maintenance" data-biaya="maintenance"
                       min="0" step="1000" value="0"
                       class="biaya-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Penyusutan</label>
                <input type="number" id="biaya_penyusutan_mesin" data-biaya="penyusutan_mesin"
                       min="0" step="1000" value="0"
                       class="biaya-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Biaya lain</label>
                <input type="number" id="biaya_biaya_lain" data-biaya="biaya_lain"
                       min="0" step="1000" value="0"
                       class="biaya-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

        </div>

        <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-500">Total biaya harian</span>
            <span id="total-biaya" class="text-sm font-semibold text-gray-800">Rp 0</span>
        </div>
    </div>

    {{-- ===== TOMBOL SUBMIT ===== --}}
    <button id="btn-submit" type="button"
            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-300 text-white
                   font-medium py-3 rounded-xl text-sm transition flex items-center justify-center gap-2">
        <svg id="btn-icon-calc" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01
                     M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <svg id="btn-icon-spin" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
        </svg>
        <span id="btn-label">Hitung &amp; Simpan</span>
    </button>

    {{-- ===== CARD HASIL KALKULASI ===== --}}
    <div id="card-hasil" class="hidden bg-emerald-50 border border-emerald-200 rounded-xl p-5">
        <h2 class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-4">Hasil Kalkulasi</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-xs text-gray-500 mb-1">Output riil</p>
                <p id="hasil-output-pcs" class="text-2xl font-semibold text-emerald-700">—</p>
                <p class="text-xs text-gray-400">pcs / hari</p>
            </div>
            <div class="text-center border-x border-emerald-200">
                <p class="text-xs text-gray-500 mb-1">Cost per pcs</p>
                <p id="hasil-cost-pcs" class="text-2xl font-semibold text-emerald-700">—</p>
                <p class="text-xs text-gray-400">per lembar</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-gray-500 mb-1">Cost per kodi</p>
                <p id="hasil-cost-kodi" class="text-2xl font-semibold text-emerald-700">—</p>
                <p class="text-xs text-gray-400">per 20 pcs</p>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-4 text-center">
            ID Transaksi: <span id="hasil-id" class="font-mono"></span>
            —
            <a href="{{ route('costing.history') }}" class="text-indigo-500 underline">Lihat di riwayat</a>
        </p>
    </div>

</div>

{{-- ===== SCRIPT — langsung di dalam section, bukan @push ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // HELPERS
    // =========================================================
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function rupiah(val) {
        return 'Rp ' + Math.round(val || 0).toLocaleString('id-ID');
    }

    function angka(val) {
        return parseFloat(val || 0).toLocaleString('id-ID', { maximumFractionDigits: 2 });
    }

    function getVal(id) {
        return document.getElementById(id).value;
    }

    // Header standar untuk semua fetch ke endpoint JSON
    function apiHeaders(extra) {
        return Object.assign({
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        }, extra || {});
    }

    // =========================================================
    // LOAD DROPDOWN MESIN DARI API
    // =========================================================
    async function fetchMesin() {
        try {
            const res  = await fetch('/api/master/mesin', {
                headers:     apiHeaders(),
            });
            const json = await res.json();
            const sel  = document.getElementById('id_mesin');

            sel.innerHTML = '<option value="">— Pilih mesin —</option>';
            (json.data || []).forEach(function (m) {
                const opt       = document.createElement('option');
                opt.value       = m.id;
                opt.dataset.rpm = m.rpm_default;
                opt.textContent = m.nama_mesin + ' (' + m.kode_mesin + ')';
                sel.appendChild(opt);
            });
        } catch (e) {
            console.error('Gagal load mesin:', e);
        }
    }

    fetchMesin();

    // =========================================================
    // AUTO-FILL RPM SAAT MESIN DIPILIH
    // =========================================================
    document.getElementById('id_mesin').addEventListener('change', function () {
        const rpmInput     = document.getElementById('rpm_aktual');
        const rpmAutoLabel = document.getElementById('rpm-auto-label');
        const selectedOpt  = this.options[this.selectedIndex];

        if (this.value && selectedOpt.dataset.rpm) {
            rpmInput.value = selectedOpt.dataset.rpm;
            rpmInput.classList.add('text-indigo-600');
            rpmAutoLabel.classList.remove('hidden');
        } else {
            rpmInput.value = '';
            rpmInput.classList.remove('text-indigo-600');
            rpmAutoLabel.classList.add('hidden');
        }
    });

    // =========================================================
    // HITUNG TOTAL BIAYA REALTIME
    // =========================================================
    function hitungTotalBiaya() {
        let total = 0;
        document.querySelectorAll('.biaya-input').forEach(function (inp) {
            total += parseFloat(inp.value) || 0;
        });
        document.getElementById('total-biaya').textContent = rupiah(total);
    }

    document.querySelectorAll('.biaya-input').forEach(function (inp) {
        inp.addEventListener('input', hitungTotalBiaya);
    });

    // Set tanggal default = hari ini
    document.getElementById('tanggal').value = new Date().toISOString().slice(0, 10);

    // =========================================================
    // TAMPILKAN / SEMBUNYIKAN ERROR PER FIELD
    // =========================================================
    function clearErrors() {
        document.querySelectorAll('.field-error').forEach(function (el) {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('.field-input').forEach(function (el) {
            el.classList.remove('border-red-400', 'bg-red-50');
        });
        document.getElementById('alert-global').classList.add('hidden');
    }

    function showErrors(errors) {
        Object.entries(errors).forEach(function ([key, msg]) {
            // 'biaya.upah_operator' → id 'err-biaya_upah_operator'
            const safeKey = key.replace('.', '_');
            const errorEl = document.getElementById('err-' + safeKey);
            const inputEl = document.getElementById(safeKey);

            if (errorEl) {
                errorEl.textContent = Array.isArray(msg) ? msg[0] : msg;
                errorEl.classList.remove('hidden');
            }
            if (inputEl) {
                inputEl.classList.add('border-red-400', 'bg-red-50');
            }
        });
    }

    function showGlobalError(msg) {
        const el = document.getElementById('alert-global');
        document.getElementById('alert-global-text').textContent = msg;
        el.classList.remove('hidden');
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // =========================================================
    // TAMPILKAN HASIL KALKULASI
    // =========================================================
    function showHasil(data) {
        document.getElementById('hasil-output-pcs').textContent = angka(data.output_riil_pcs);
        document.getElementById('hasil-cost-pcs').textContent   = rupiah(data.cost_per_pcs);
        document.getElementById('hasil-cost-kodi').textContent  = rupiah(data.cost_per_kodi);
        document.getElementById('hasil-id').textContent         = '#' + data.id_transaksi;

        const cardHasil = document.getElementById('card-hasil');
        cardHasil.classList.remove('hidden');
        cardHasil.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // =========================================================
    // LOADING STATE TOMBOL
    // =========================================================
    function setLoading(isLoading) {
        const btn  = document.getElementById('btn-submit');
        const calc = document.getElementById('btn-icon-calc');
        const spin = document.getElementById('btn-icon-spin');
        const lbl  = document.getElementById('btn-label');

        btn.disabled = isLoading;
        calc.classList.toggle('hidden', isLoading);
        spin.classList.toggle('hidden', !isLoading);
        lbl.textContent = isLoading ? 'Menghitung...' : 'Hitung & Simpan';
    }

    // =========================================================
    // SUBMIT FORM
    // =========================================================
    document.getElementById('btn-submit').addEventListener('click', async function () {

        clearErrors();

        const payload = {
            tanggal:       getVal('tanggal'),
            id_mesin:      parseInt(getVal('id_mesin'))      || '',
            kode_produksi: getVal('kode_produksi'),
            slah_sisir:    parseInt(getVal('slah_sisir'))    || '',
            pick:          parseInt(getVal('pick'))          || '',
            panjang_pcs:   parseFloat(getVal('panjang_pcs')) || '',
            jam_kerja:     parseFloat(getVal('jam_kerja'))   || '',
            efisiensi:     parseFloat(getVal('efisiensi'))   || '',
            rpm_aktual:    parseFloat(getVal('rpm_aktual'))  || null,
            jumlah_mesin:  parseInt(getVal('jumlah_mesin'))  || 1,
            biaya: {
                upah_operator:    parseFloat(document.getElementById('biaya_upah_operator').value)    || 0,
                listrik_mesin:    parseFloat(document.getElementById('biaya_listrik_mesin').value)    || 0,
                maintenance:      parseFloat(document.getElementById('biaya_maintenance').value)      || 0,
                penyusutan_mesin: parseFloat(document.getElementById('biaya_penyusutan_mesin').value) || 0,
                biaya_lain:       parseFloat(document.getElementById('biaya_biaya_lain').value)       || 0,
            },
        };

        if (!payload.rpm_aktual) delete payload.rpm_aktual;

        setLoading(true);

        try {
            const res  = await fetch('/api/costing/calculate', {
                method:      'POST',
                headers:     apiHeaders({ 'Content-Type': 'application/json' }),
                body:        JSON.stringify(payload),
            });

            const json = await res.json();

            if (res.ok) {
                showHasil(json.data);
            } else if (res.status === 422) {
                showErrors(json.errors || {});
            } else {
                showGlobalError(json.message || 'Terjadi kesalahan server. Silakan coba lagi.');
            }

        } catch (e) {
            showGlobalError('Gagal terhubung ke server. Periksa koneksi Anda.');
        } finally {
            setLoading(false);
        }
    });

});
</script>
@endsection