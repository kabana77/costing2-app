@extends('layouts.app')
@section('title', 'Master Mesin')
@section('page-title', 'Master Mesin')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- ===== ALERT ===== --}}
    <div id="alert-box" class="hidden rounded-xl px-4 py-3 text-sm flex items-start gap-3"></div>

    {{-- ===== CARD UTAMA ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100">
            <span class="text-sm font-medium text-gray-800">Daftar Mesin</span>
            <button id="btn-tambah"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                           px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mesin
            </button>
        </div>

        {{-- Pagination --}}
        <div id="pagination-wrap"
             class="flex items-center justify-between px-4 py-3 border-t border-gray-100">
            <span id="pagination-info" class="text-xs text-gray-400"></span>
            <div id="pagination-btns" class="flex gap-1"></div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Kode</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Mesin</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">RPM Default</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Keterangan</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-body">
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Memuat...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== MODAL FORM (create & edit) ===== --}}
<div id="modal-overlay" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
        <h3 id="modal-title" class="text-base font-medium text-gray-800 mb-4">Tambah Mesin Baru</h3>

        <input type="hidden" id="form-id" value="" />

        <div class="space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Kode mesin (maks 10 karakter) <span class="text-red-400">*</span></label>
                <input type="text" id="form-kode" maxlength="10"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="Contoh: RPR" />
                <p id="err-kode_mesin" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Nama mesin <span class="text-red-400">*</span></label>
                <input type="text" id="form-nama" maxlength="50"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="Contoh: Rapier" />
                <p id="err-nama_mesin" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">RPM default <span class="text-red-400">*</span></label>
                <input type="number" id="form-rpm" min="0.01" step="0.01"
                       class="field-input w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="180" />
                <p id="err-rpm_default" class="field-error hidden text-xs text-red-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Keterangan (opsional)</label>
                <input type="text" id="form-keterangan" maxlength="255"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-300"
                       placeholder="Catatan tambahan" />
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
        <h3 class="text-base font-medium text-gray-800 mb-2">Hapus Mesin?</h3>
        <p class="text-sm text-gray-500 mb-6">
            Mesin <span id="delete-nama" class="font-medium text-gray-700"></span> akan dihapus permanen.
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
    let deleteTargetId = null;

    // pagination state
    let state = {
        page: 1,
        perPage: 20,
    };

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
            const fieldMap = { kode_mesin: 'form-kode', nama_mesin: 'form-nama', rpm_default: 'form-rpm' };
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
    // LOAD DAFTAR MESIN
    // =========================================================
    async function fetchList() {
        const tbody = document.getElementById('tabel-body');
        try {
            const params = new URLSearchParams();
            params.set('page', state.page);
            params.set('per_page', state.perPage);

            const res  = await fetch('/api/master-crud/mesin?' + params.toString(), { headers: apiHeaders() });
            const json = await res.json();

            renderTabel(json.data || []);
            renderPagination(json.meta || {});
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-10 text-center text-red-400">Gagal memuat data.</td></tr>';
        }
    }

    function renderTabel(rows) {
        const tbody = document.getElementById('tabel-body');

        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Belum ada data mesin.</td></tr>';
            return;
        }

        tbody.innerHTML = rows.map(function (m) {
            return '<tr class="border-b border-gray-100 hover:bg-gray-50 transition" data-id="' + m.id + '">' +
                '<td class="px-4 py-3"><span class="bg-indigo-50 text-indigo-700 text-xs font-mono font-medium px-2 py-1 rounded">' + m.kode_mesin + '</span></td>' +
                '<td class="px-4 py-3">' + m.nama_mesin + '</td>' +
                '<td class="px-4 py-3 text-right">' + parseFloat(m.rpm_default).toLocaleString('id-ID') + '</td>' +
                '<td class="px-4 py-3 text-gray-500">' + (m.keterangan || '—') + '</td>' +
                '<td class="px-4 py-3 text-center">' +
                    '<button class="btn-edit text-indigo-600 border border-indigo-200 rounded px-2 py-1 text-xs mr-1 hover:bg-indigo-50" ' +
                        'data-id="' + m.id + '" data-kode="' + m.kode_mesin + '" data-nama="' + m.nama_mesin + '" ' +
                        'data-rpm="' + m.rpm_default + '" data-keterangan="' + (m.keterangan || '') + '">Edit</button>' +
                    '<button class="btn-delete text-red-600 border border-red-200 rounded px-2 py-1 text-xs hover:bg-red-50" ' +
                        'data-id="' + m.id + '" data-nama="' + m.nama_mesin + '">Hapus</button>' +
                '</td>' +
            '</tr>';
        }).join('');

        // Re-attach event listener setiap kali tabel di-render ulang
        document.querySelectorAll('.btn-edit').forEach(function (btn) {
            btn.addEventListener('click', function () { openEditModal(this.dataset); });
        });
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () { openDeleteModal(this.dataset.id, this.dataset.nama); });
        });
    }

    // pagination renderer (copied/adapted from history page)
    function renderPagination(meta) {
        const info  = document.getElementById('pagination-info');
        const wrap  = document.getElementById('pagination-btns');
        const total = document.getElementById('pagination-info');

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
            if (state.page > 1) { state.page--; fetchList(); }
        });
        wrap.appendChild(prev);

        const start = Math.max(1, meta.current_page - 2);
        const end   = Math.min(meta.last_page, meta.current_page + 2);

        for (let i = start; i <= end; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className   = btnClass + (i === meta.current_page
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'hover:bg-gray-50 text-gray-600');
            btn.addEventListener('click', (function (page) {
                return function () { state.page = page; fetchList(); };
            }(i)));
            wrap.appendChild(btn);
        }

        // Next
        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.className   = btnClass + (meta.current_page >= meta.last_page ? 'opacity-40 cursor-not-allowed bg-gray-50 text-gray-400' : 'hover:bg-gray-50 text-gray-600');
        next.disabled    = meta.current_page >= meta.last_page;
        next.addEventListener('click', function () {
            if (state.page < meta.last_page) { state.page++; fetchList(); }
        });
        wrap.appendChild(next);
    }

    // =========================================================
    // MODAL CREATE / EDIT
    // =========================================================
    function openCreateModal() {
        document.getElementById('modal-title').textContent = 'Tambah Mesin Baru';
        document.getElementById('form-id').value = '';
        document.getElementById('form-kode').value = '';
        document.getElementById('form-nama').value = '';
        document.getElementById('form-rpm').value = '';
        document.getElementById('form-keterangan').value = '';
        clearFormErrors();
        document.getElementById('modal-overlay').classList.remove('hidden');
    }

    function openEditModal(data) {
        document.getElementById('modal-title').textContent = 'Edit Mesin';
        document.getElementById('form-id').value = data.id;
        document.getElementById('form-kode').value = data.kode;
        document.getElementById('form-nama').value = data.nama;
        document.getElementById('form-rpm').value = data.rpm;
        document.getElementById('form-keterangan').value = data.keterangan;
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

        const id      = document.getElementById('form-id').value;
        const payload = {
            kode_mesin:  document.getElementById('form-kode').value,
            nama_mesin:  document.getElementById('form-nama').value,
            rpm_default: parseFloat(document.getElementById('form-rpm').value) || '',
            keterangan:  document.getElementById('form-keterangan').value || null,
        };

        const isEdit = !!id;
        const url    = isEdit ? '/api/master-crud/mesin/' + id : '/api/master-crud/mesin';
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
    function openDeleteModal(id, nama) {
        deleteTargetId = id;
        document.getElementById('delete-nama').textContent = nama;
        document.getElementById('modal-delete-overlay').classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteTargetId = null;
        document.getElementById('modal-delete-overlay').classList.add('hidden');
    }

    document.getElementById('btn-cancel-delete').addEventListener('click', closeDeleteModal);

    document.getElementById('btn-confirm-delete').addEventListener('click', async function () {
        if (!deleteTargetId) return;

        try {
            const res  = await fetch('/api/master-crud/mesin/' + deleteTargetId, {
                method:  'DELETE',
                headers: apiHeaders(),
            });
            const json = await res.json();

            closeDeleteModal();

            if (res.ok) {
                showAlert('success', json.message);
                fetchList();
            } else {
                // 409 Conflict — mesin masih dipakai di transaksi
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