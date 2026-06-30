@extends('layouts.app')
@section('title', 'Master Produk')
@section('page-title', 'Master Produk')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- ===== ALERT ===== --}}
    <div id="alert-box" class="hidden rounded-xl px-4 py-3 text-sm flex items-start gap-3"></div>

    {{-- ===== CARD UTAMA ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100">
            <span class="text-sm font-medium text-gray-800">Daftar Produk</span>
            <button id="btn-tambah"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                           px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Kode Produksi</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Konstruksi</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Panjang Default (cm)</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-body">
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Memuat...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== MODAL FORM (create & edit) ===== --}}
<div id="modal-overlay" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
        <h3 id="modal-title" class="text-base font-medium text-gray-800 mb-4">Tambah Produk Baru</h3>

        {{-- Simpan kode LAMA untuk keperluan update (PK bukan id, tapi kode_produksi) --}}
        <input type="hidden" id="form-kode-lama" value="" />

        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Kode produksi (maks 20 karakter) <span class="text-red-400">*</span></label>
                <input type="text" id="form-kode" maxlength="20"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="Contoh: P046" />
                <p id="err-kode_produksi" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Konstruksi (opsional)</label>
                <input type="text" id="form-konstruksi" maxlength="100"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="Contoh: Kain polos 40s/40s" />
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Panjang default (cm) <span class="text-red-400">*</span></label>
                <input type="number" id="form-panjang" min="0.01" step="0.01"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="215" />
                <p id="err-panjang_default" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6">
            <button id="btn-cancel"
                    class="border border-gray-200 hover:bg-gray-50 text-gray-600
                           font-medium px-4 py-2 rounded-lg text-sm transition">
                Batal
            </button>
            <button id="btn-simpan"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                           px-4 py-2 rounded-lg text-sm transition">
                Simpan
            </button>
        </div>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="modal-delete-overlay" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-sm p-6">
        <h3 class="text-base font-medium text-gray-800 mb-2">Hapus Produk?</h3>
        <p class="text-sm text-gray-500 mb-6">
            Produk <span id="delete-kode" class="font-medium text-gray-700"></span> akan dihapus permanen.
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex justify-end gap-2">
            <button id="btn-cancel-delete"
                    class="border border-gray-200 hover:bg-gray-50 text-gray-600
                           font-medium px-4 py-2 rounded-lg text-sm transition">
                Batal
            </button>
            <button id="btn-confirm-delete"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium
                           px-4 py-2 rounded-lg text-sm transition">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let deleteTargetKode = null;

    function apiHeaders(extra) {
        return Object.assign({
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        }, extra || {});
    }

    function showAlert(type, message) {
        const box = document.getElementById('alert-box');
        box.className = 'rounded-xl px-4 py-3 text-sm flex items-start gap-3 ' +
            (type === 'success'
                ? 'bg-emerald-50 border border-emerald-200 text-emerald-700'
                : 'bg-red-50 border border-red-200 text-red-700');
        box.innerHTML = '<span>' + message + '</span>';
        box.classList.remove('hidden');
        setTimeout(function () { box.classList.add('hidden'); }, 4000);
    }

    function clearFormErrors() {
        document.querySelectorAll('.field-error').forEach(function (el) {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('.field-input').forEach(function (el) {
            el.classList.remove('border-red-400', 'bg-red-50');
        });
    }

    function showFormErrors(errors) {
        Object.entries(errors).forEach(function ([key, msg]) {
            const fieldMap = { kode_produksi: 'form-kode', panjang_default: 'form-panjang' };
            const errEl   = document.getElementById('err-' + key);
            const inputEl = document.getElementById(fieldMap[key]);
            if (errEl) {
                errEl.textContent = Array.isArray(msg) ? msg[0] : msg;
                errEl.classList.remove('hidden');
            }
            if (inputEl) inputEl.classList.add('border-red-400', 'bg-red-50');
        });
    }

    // =========================================================
    // LOAD DAFTAR PRODUK
    // =========================================================
    async function fetchList() {
        const tbody = document.getElementById('tabel-body');
        try {
            const res  = await fetch('/api/master-crud/produk', { headers: apiHeaders() });
            const json = await res.json();
            renderTabel(json.data || []);
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-10 text-center text-red-400">Gagal memuat data.</td></tr>';
        }
    }

    function renderTabel(rows) {
        const tbody = document.getElementById('tabel-body');

        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Belum ada data produk.</td></tr>';
            return;
        }

        tbody.innerHTML = rows.map(function (p) {
            return '<tr class="border-b border-gray-100 hover:bg-gray-50 transition">' +
                '<td class="px-4 py-3"><span class="bg-indigo-50 text-indigo-700 text-xs font-mono font-medium px-2 py-1 rounded">' + p.kode_produksi + '</span></td>' +
                '<td class="px-4 py-3">' + (p.konstruksi || '—') + '</td>' +
                '<td class="px-4 py-3 text-right">' + parseFloat(p.panjang_default).toLocaleString('id-ID') + '</td>' +
                '<td class="px-4 py-3 text-center">' +
                    '<button class="btn-edit text-indigo-600 border border-indigo-200 rounded px-2 py-1 text-xs mr-1 hover:bg-indigo-50" ' +
                        'data-kode="' + p.kode_produksi + '" data-konstruksi="' + (p.konstruksi || '') + '" data-panjang="' + p.panjang_default + '">Edit</button>' +
                    '<button class="btn-delete text-red-600 border border-red-200 rounded px-2 py-1 text-xs hover:bg-red-50" ' +
                        'data-kode="' + p.kode_produksi + '">Hapus</button>' +
                '</td>' +
            '</tr>';
        }).join('');

        document.querySelectorAll('.btn-edit').forEach(function (btn) {
            btn.addEventListener('click', function () { openEditModal(this.dataset); });
        });
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () { openDeleteModal(this.dataset.kode); });
        });
    }

    // =========================================================
    // MODAL CREATE / EDIT
    // =========================================================
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Tambah Produk Baru';
        document.getElementById('form-kode-lama').value = '';
        document.getElementById('form-kode').value = '';
        document.getElementById('form-kode').disabled = false; // kode bisa diisi bebas saat create
        document.getElementById('form-konstruksi').value = '';
        document.getElementById('form-panjang').value = '';
        clearFormErrors();
        document.getElementById('modal-overlay').classList.remove('hidden');
    }

    function openEditModal(data) {
        document.getElementById('modal-title').textContent = 'Edit Produk';
        document.getElementById('form-kode-lama').value = data.kode;
        document.getElementById('form-kode').value = data.kode;
        document.getElementById('form-konstruksi').value = data.konstruksi;
        document.getElementById('form-panjang').value = data.panjang;
        clearFormErrors();
        document.getElementById('modal-overlay').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-overlay').classList.add('hidden');
    }

    document.getElementById('btn-tambah').addEventListener('click', openCreateModal);
    document.getElementById('btn-cancel').addEventListener('click', closeModal);

    // =========================================================
    // SIMPAN (CREATE / UPDATE)
    // =========================================================
    document.getElementById('btn-simpan').addEventListener('click', async function () {
        clearFormErrors();

        const kodeLama = document.getElementById('form-kode-lama').value;
        const payload  = {
            kode_produksi:   document.getElementById('form-kode').value,
            konstruksi:      document.getElementById('form-konstruksi').value || null,
            panjang_default: parseFloat(document.getElementById('form-panjang').value) || '',
        };

        const isEdit = !!kodeLama;
        const url    = isEdit ? '/api/master-crud/produk/' + kodeLama : '/api/master-crud/produk';
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const res  = await fetch(url, {
                method:  method,
                headers: apiHeaders({ 'Content-Type': 'application/json' }),
                body:    JSON.stringify(payload),
            });
            const json = await res.json();

            if (res.ok) {
                closeModal();
                showAlert('success', json.message);
                fetchList();
            } else if (res.status === 422) {
                showFormErrors(json.errors || {});
            } else {
                showAlert('error', json.message || 'Terjadi kesalahan.');
            }
        } catch (e) {
            showAlert('error', 'Gagal terhubung ke server.');
        }
    });

    // =========================================================
    // MODAL HAPUS
    // =========================================================
    function openDeleteModal(kode) {
        deleteTargetKode = kode;
        document.getElementById('delete-kode').textContent = kode;
        document.getElementById('modal-delete-overlay').classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteTargetKode = null;
        document.getElementById('modal-delete-overlay').classList.add('hidden');
    }

    document.getElementById('btn-cancel-delete').addEventListener('click', closeDeleteModal);

    document.getElementById('btn-confirm-delete').addEventListener('click', async function () {
        if (!deleteTargetKode) return;

        try {
            const res  = await fetch('/api/master-crud/produk/' + deleteTargetKode, {
                method:  'DELETE',
                headers: apiHeaders(),
            });
            const json = await res.json();

            closeDeleteModal();

            if (res.ok) {
                showAlert('success', json.message);
                fetchList();
            } else {
                showAlert('error', json.message);
            }
        } catch (e) {
            closeDeleteModal();
            showAlert('error', 'Gagal terhubung ke server.');
        }
    });

    // =========================================================
    // INIT
    // =========================================================
    fetchList();

});
</script>
@endsection