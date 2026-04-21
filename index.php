<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Admin Rekap Penyerahan & Pengembalian Gawai">
    <title>Rekap Gawai — Dashboard Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <div class="brand-text">
            <span class="brand-title">Rekap Gawai</span>
            <span class="brand-sub">Admin Panel</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Dashboard</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="admin-avatar">A</div>
            <div class="admin-info">
                <span class="admin-name">Administrator</span>
                <span class="admin-role">Super Admin</span>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN -->
<main class="main-content">
    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <div class="topbar-title">
            <h1>Dashboard Rekap Gawai</h1>
            <p class="topbar-date" id="currentDate"></p>
        </div>
    </header>

    <!-- STATS -->
    <section class="stats-grid">
        <div class="stat-card stat-total">
            <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
            <div class="stat-info"><span class="stat-value" id="statTotal">0</span><span class="stat-label">Total Rekap</span></div>
        </div>
        <div class="stat-card stat-serah">
            <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div class="stat-info"><span class="stat-value" id="statSerah">0</span><span class="stat-label">Total Diserahkan</span></div>
        </div>
        <div class="stat-card stat-kembali">
            <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg></div>
            <div class="stat-info"><span class="stat-value" id="statKembali">0</span><span class="stat-label">Total Dikembalikan</span></div>
        </div>
        <div class="stat-card stat-selisih">
            <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
            <div class="stat-info"><span class="stat-value" id="statSelisih">0</span><span class="stat-label">Selisih Gawai</span></div>
        </div>
    </section>

    <!-- TABLE -->
    <section class="table-section">
        <div class="table-header">
            <h2>Data Rekap Penyerahan & Pengembalian</h2>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-outline" id="btnCetak" style="border-color: #cbd5e1; background: white; color: #334155; display: flex; align-items: center; gap: 6px;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak Laporan
                </button>
                <button class="btn btn-primary" id="btnTambah">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="filters-bar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchInput" placeholder="Cari nama petugas atau guru...">
            </div>
            <div class="date-filters">
                <div class="date-input-wrap"><label for="dateFrom">Dari</label><input type="date" id="dateFrom"></div>
                <div class="date-input-wrap"><label for="dateTo">Sampai</label><input type="date" id="dateTo"></div>
                <button class="btn btn-outline" id="btnResetFilter">Reset</button>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th><th>Hari / Tanggal</th><th>Petugas</th><th>Guru Awal</th>
                        <th>Jam Serah</th><th>Jml Serah</th><th>Guru Akhir</th>
                        <th>Jam Kembali</th><th>Jml Kembali</th><th>Selisih</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div class="empty-state" id="emptyState" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
            <h3>Belum Ada Data</h3>
            <p>Klik tombol "Tambah Data" untuk mulai mencatat.</p>
        </div>
    </section>
</main>

<?php include 'partials/modal.php'; ?>

<div class="toast-container" id="toastContainer"></div>
<script src="assets/js/app.js"></script>
</body>
</html>
