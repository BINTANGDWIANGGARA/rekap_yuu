/* ═══════════════════════════════════════════════════════════════
   REKAP GAWAI — App Logic (CRUD + UI)
   ═══════════════════════════════════════════════════════════════ */

const API = 'api.php';

// ── DOM References ─────────────────────────────────────────────
const $ = (s) => document.querySelector(s);
const tableBody      = $('#tableBody');
const emptyState     = $('#emptyState');
const tableEl        = $('#dataTable');
const modalOverlay   = $('#modalOverlay');
const confirmOverlay = $('#confirmOverlay');
const rekapForm      = $('#rekapForm');
const modalTitle     = $('#modalTitle');
const searchInput    = $('#searchInput');
const dateFrom       = $('#dateFrom');
const dateTo         = $('#dateTo');
const toastContainer = $('#toastContainer');

let deleteTargetId = null;
let debounceTimer  = null;

// ── Init ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    setCurrentDate();
    loadData();
    bindEvents();
});

function setCurrentDate() {
    const now = new Date();
    const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
    $('#currentDate').textContent = now.toLocaleDateString('id-ID', opts);
}

// ── Event Bindings ─────────────────────────────────────────────
function bindEvents() {
    // Sidebar toggle
    $('#sidebarToggle').addEventListener('click', () => {
        $('#sidebar').classList.toggle('open');
    });

    // Open modal — Tambah
    $('#btnTambah').addEventListener('click', () => openModal());

    // Cetak Laporan
    $('#btnCetak').addEventListener('click', () => {
        const params = new URLSearchParams();
        if (dateFrom.value) params.set('date_from', dateFrom.value);
        if (dateTo.value) params.set('date_to', dateTo.value);
        window.open(`laporan.php?${params.toString()}`, '_blank');
    });

    // Close modal
    $('#modalClose').addEventListener('click', closeModal);
    $('#btnBatal').addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });

    // Submit form
    rekapForm.addEventListener('submit', handleSubmit);

    // Delete confirm
    $('#btnConfirmCancel').addEventListener('click', closeConfirm);
    $('#btnConfirmDelete').addEventListener('click', handleDelete);
    confirmOverlay.addEventListener('click', (e) => {
        if (e.target === confirmOverlay) closeConfirm();
    });

    // Search
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadData, 350);
    });

    // Date filters
    dateFrom.addEventListener('change', loadData);
    dateTo.addEventListener('change', loadData);

    // Reset filter
    $('#btnResetFilter').addEventListener('click', () => {
        searchInput.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        loadData();
    });

    // Auto-fill hari when tanggal changes
    $('#inputTanggal').addEventListener('change', (e) => {
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const d = new Date(e.target.value);
        if (!isNaN(d)) {
            $('#inputHari').value = days[d.getDay()];
        }
    });

    // Keyboard
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (confirmOverlay.classList.contains('active')) closeConfirm();
            else if (modalOverlay.classList.contains('active')) closeModal();
        }
    });
}

// ── Load Data ──────────────────────────────────────────────────
async function loadData() {
    showLoading();

    const params = new URLSearchParams();
    if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
    if (dateFrom.value) params.set('date_from', dateFrom.value);
    if (dateTo.value) params.set('date_to', dateTo.value);

    try {
        const res = await fetch(`${API}?${params.toString()}`);
        const json = await res.json();

        if (json.success) {
            renderTable(json.data);
            updateStats(json.data);
        } else {
            showToast(json.message || 'Gagal memuat data', 'error');
        }
    } catch (err) {
        showToast('Gagal terhubung ke server', 'error');
        console.error(err);
    }
}

// ── Render Table ───────────────────────────────────────────────
function renderTable(data) {
    if (!data.length) {
        tableEl.style.display = 'none';
        emptyState.style.display = 'block';
        tableBody.innerHTML = '';
        return;
    }

    tableEl.style.display = 'table';
    emptyState.style.display = 'none';

    tableBody.innerHTML = data.map((row, i) => {
        const selisih = row.jumlah_penyerahan - row.jumlah_pengembalian;
        const selisihClass = selisih === 0 ? 'selisih-ok' : 'selisih-warn';
        const tgl = formatDate(row.tanggal);

        return `<tr>
            <td>${i + 1}</td>
            <td>${row.hari}, ${tgl}</td>
            <td>${esc(row.nama_petugas)}</td>
            <td>${esc(row.nama_guru_awal)}</td>
            <td>${row.jam_penyerahan.substring(0,5)}</td>
            <td>${row.jumlah_penyerahan}</td>
            <td>${esc(row.nama_guru_akhir)}</td>
            <td>${row.jam_pengembalian.substring(0,5)}</td>
            <td>${row.jumlah_pengembalian}</td>
            <td><span class="selisih-badge ${selisihClass}">${selisih}</span></td>
            <td>
                <div class="action-btns">
                    <button class="btn-icon" onclick="openEdit(${row.id})" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="btn-icon btn-icon-danger" onclick="confirmDelete(${row.id})" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// ── Stats ──────────────────────────────────────────────────────
function updateStats(data) {
    const total = data.length;
    const serah = data.reduce((s, r) => s + parseInt(r.jumlah_penyerahan), 0);
    const kembali = data.reduce((s, r) => s + parseInt(r.jumlah_pengembalian), 0);
    animateCount('statTotal', total);
    animateCount('statSerah', serah);
    animateCount('statKembali', kembali);
    animateCount('statSelisih', serah - kembali);
}

function animateCount(id, target) {
    const el = document.getElementById(id);
    const current = parseInt(el.textContent) || 0;
    if (current === target) { el.textContent = target; return; }

    const duration = 500;
    const step = (target - current) / (duration / 16);
    let val = current;

    const tick = () => {
        val += step;
        if ((step > 0 && val >= target) || (step < 0 && val <= target)) {
            el.textContent = target;
            return;
        }
        el.textContent = Math.round(val);
        requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

// ── Modal ──────────────────────────────────────────────────────
function openModal(data = null) {
    rekapForm.reset();
    $('#editId').value = '';

    if (data) {
        modalTitle.textContent = 'Edit Data Rekap';
        $('#editId').value = data.id;
        $('#inputHari').value = data.hari;
        $('#inputTanggal').value = data.tanggal;
        $('#inputPetugas').value = data.nama_petugas;
        $('#inputGuruAwal').value = data.nama_guru_awal;
        $('#inputJamSerah').value = data.jam_penyerahan;
        $('#inputJmlSerah').value = data.jumlah_penyerahan;
        $('#inputGuruAkhir').value = data.nama_guru_akhir;
        $('#inputJamKembali').value = data.jam_pengembalian;
        $('#inputJmlKembali').value = data.jumlah_pengembalian;
    } else {
        modalTitle.textContent = 'Tambah Data Rekap';
        
        // Auto-fill tanggal, hari, dan jam saat ini
        const now = new Date();
        
        // Format YYYY-MM-DD
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;
        
        // Format HH:MM
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const timeStr = `${hours}:${minutes}`;
        
        // Nama Hari
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const dayName = days[now.getDay()];
        
        $('#inputTanggal').value = todayStr;
        $('#inputHari').value = dayName;
        $('#inputJamSerah').value = timeStr;
    }

    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

// ── Open Edit (fetch single row) ───────────────────────────────
async function openEdit(id) {
    try {
        const res = await fetch(API);
        const json = await res.json();
        if (json.success) {
            const row = json.data.find(r => r.id == id);
            if (row) openModal(row);
        }
    } catch (err) {
        showToast('Gagal memuat data untuk edit', 'error');
    }
}

// ── Submit (Create / Update) ───────────────────────────────────
async function handleSubmit(e) {
    e.preventDefault();

    const id = $('#editId').value;
    const body = {
        hari: $('#inputHari').value,
        tanggal: $('#inputTanggal').value,
        nama_petugas: $('#inputPetugas').value,
        nama_guru_awal: $('#inputGuruAwal').value,
        jam_penyerahan: $('#inputJamSerah').value,
        jumlah_penyerahan: parseInt($('#inputJmlSerah').value),
        nama_guru_akhir: $('#inputGuruAkhir').value,
        jam_pengembalian: $('#inputJamKembali').value,
        jumlah_pengembalian: parseInt($('#inputJmlKembali').value),
    };

    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API}?id=${id}` : API;

    try {
        const res = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
        const json = await res.json();

        if (json.success) {
            showToast(json.message, 'success');
            closeModal();
            loadData();
        } else {
            showToast(json.message || 'Gagal menyimpan', 'error');
        }
    } catch (err) {
        showToast('Gagal menyimpan data', 'error');
    }
}

// ── Delete ─────────────────────────────────────────────────────
function confirmDelete(id) {
    deleteTargetId = id;
    confirmOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeConfirm() {
    confirmOverlay.classList.remove('active');
    document.body.style.overflow = '';
    deleteTargetId = null;
}

async function handleDelete() {
    if (!deleteTargetId) return;

    try {
        const res = await fetch(`${API}?id=${deleteTargetId}`, { method: 'DELETE' });
        const json = await res.json();

        if (json.success) {
            showToast(json.message, 'success');
            closeConfirm();
            loadData();
        } else {
            showToast(json.message || 'Gagal menghapus', 'error');
        }
    } catch (err) {
        showToast('Gagal menghapus data', 'error');
    }
}

// ── Toast ──────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const iconSvg = type === 'success'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span class="toast-icon">${iconSvg}</span><span>${esc(message)}</span>`;
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('toast-out');
        toast.addEventListener('animationend', () => toast.remove());
    }, 3000);
}

// ── Helpers ────────────────────────────────────────────────────
function showLoading() {
    tableEl.style.display = 'table';
    emptyState.style.display = 'none';
    tableBody.innerHTML = `<tr class="loading-row"><td colspan="11"><div class="spinner"></div>Memuat data...</td></tr>`;
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}

function esc(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
