<!-- MODAL FORM -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal" id="modal">
        <div class="modal-header">
            <h2 id="modalTitle">Tambah Data Rekap</h2>
            <button class="modal-close" id="modalClose" aria-label="Tutup modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form class="modal-form" id="rekapForm" autocomplete="off">
            <input type="hidden" id="editId" value="">

            <div class="form-section">
                <div class="section-badge serah-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Penyerahan Gawai
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="inputHari">Hari</label>
                        <select id="inputHari" required>
                            <option value="">Pilih hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputTanggal">Tanggal</label>
                        <input type="date" id="inputTanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="inputPetugas">Nama Petugas</label>
                        <input type="text" id="inputPetugas" placeholder="Masukkan nama petugas" required>
                    </div>
                    <div class="form-group">
                        <label for="inputGuruAwal">Nama Guru (Jam Pertama)</label>
                        <input type="text" id="inputGuruAwal" placeholder="Masukkan nama guru" required>
                    </div>
                    <div class="form-group">
                        <label for="inputJamSerah">Jam Penyerahan</label>
                        <input type="time" id="inputJamSerah" required>
                    </div>
                    <div class="form-group">
                        <label for="inputJmlSerah">Jumlah Gawai</label>
                        <input type="number" id="inputJmlSerah" min="0" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-badge kembali-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                    Pengembalian Gawai
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="inputGuruAkhir">Nama Guru (Jam Terakhir)</label>
                        <input type="text" id="inputGuruAkhir" placeholder="Masukkan nama guru" required>
                    </div>
                    <div class="form-group">
                        <label for="inputJamKembali">Jam Pengembalian</label>
                        <input type="time" id="inputJamKembali" required>
                    </div>
                    <div class="form-group">
                        <label for="inputJmlKembali">Jumlah Gawai</label>
                        <input type="number" id="inputJmlKembali" min="0" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" id="btnBatal">Batal</button>
                <button type="submit" class="btn btn-primary btn-save" id="btnSimpan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRM -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <h3>Hapus Data?</h3>
        <p>Data yang sudah dihapus tidak bisa dikembalikan.</p>
        <div class="confirm-actions">
            <button class="btn btn-ghost" id="btnConfirmCancel">Batal</button>
            <button class="btn btn-danger" id="btnConfirmDelete">Ya, Hapus</button>
        </div>
    </div>
</div>
