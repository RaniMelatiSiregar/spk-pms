@extends('layouts.master')
@section('content')
<div class="page-header-section">
  <div>
    <h1 class="page-title"><i class="fas fa-users"></i> Data Alternatif (Supplier)</h1>
    <p class="page-subtitle">Kelola data supplier untuk perhitungan SPK</p>
  </div>
  <div class="page-actions">
    <button type="button" class="btn-import" data-bs-toggle="modal" data-bs-target="#importCsvModal">
        <i class="fas fa-file-csv"></i> Import CSV
    </button>
    <a href="{{ route('supplier.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Tambah Supplier
    </a>
</div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="mini-stats-card bg-gradient-blue">
      <div class="mini-stats-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="mini-stats-content">
        <h4>{{ $suppliers->count() }}</h4>
        <p>Total Supplier</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="mini-stats-card bg-gradient-green">
      <div class="mini-stats-icon">
        <i class="fas fa-map-marker-alt"></i>
      </div>
      <div class="mini-stats-content">
        <h4>{{ $suppliers->where('location', '!=', null)->count() }}</h4>
        <p>Supplier dengan Lokasi</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="mini-stats-card bg-gradient-purple">
      <div class="mini-stats-icon">
        <i class="fas fa-calendar-check"></i>
      </div>
      <div class="mini-stats-content">
        <h4>{{ $activeSuppliersCount }}</h4>
        <p>Supplier Aktif</p>
      </div>
    </div>
  </div>
</div>

<!-- Suppliers Table -->
<div class="data-table-card">
  <div class="table-card-header">
    <h5><i class="fas fa-table"></i> Daftar Supplier</h5>
    <div class="table-search">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" placeholder="Cari supplier..." class="search-input">
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="table modern-data-table" id="supplierTable">
      <thead>
        <tr>
          <th width="5%">No</th>
          <th width="15%">Kode</th>
          <th width="35%">Nama Supplier</th>
          <th width="25%">Lokasi</th>
          <th width="20%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suppliers as $i => $s)
        <tr>
          <td><span class="row-number">{{ $i + 1 }}</span></td>
          <td>
            <span class="badge-code">{{ $s->code }}</span>
            @if(in_array($s->id, $activeSupplierIds))
                <span class="badge bg-success text-white ms-1">Aktif</span>
            @endif
          </td>
          <td>
            <strong>{{ $s->name }}</strong>
            <div class="text-muted small mt-1">
              @if($s->periode)
                Periode: {{ $s->periode->name }}
              @else
                <span class="text-danger">Belum ada periode</span>
              @endif
            </div>
          </td>
          <td>
            @if($s->location)
              <i class="fas fa-map-marker-alt text-danger"></i> {{ $s->location }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-center">
            <div class="action-buttons">
              <a href="{{ route('supplier.edit', $s) }}" class="btn-action btn-edit" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('supplier.destroy', $s) }}" method="post" style="display:inline" 
                    onsubmit="return confirm('Yakin ingin menghapus supplier {{ $s->name }}?')">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete" title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
  <!-- Info Box -->
  <div class="card-footer bg-light">
    <div class="alert alert-info mb-0">
      <i class="fas fa-info-circle"></i> 
      <strong>Catatan:</strong> Data penilaian (harga, volume, ketepatan, frekuensi) diisi saat membuat periode baru di menu <a href="{{ route('periode.index') }}" class="alert-link">Periode</a>
    </div>
  </div>
</div>

<style>

.page-header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 20px;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 5px;
}

.page-subtitle {
  color: #4a5568;
  font-size: 14px;
  margin: 0;
}

.btn-add {
  padding: 12px 25px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.3);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(43, 108, 176, 0.4);
  color: white;
}

.btn-import {
    padding: 12px 25px;
    background: linear-gradient(135deg, #38a169 0%, #276749 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(56, 161, 105, 0.3);
    cursor: pointer;
    font-size: 14px;
}

.btn-import:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(56, 161, 105, 0.4);
    color: white;
}

/* Mini Stats Cards */
.mini-stats-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.mini-stats-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0.1;
  z-index: 0;
}

.bg-gradient-blue::before {
  background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);
}

.bg-gradient-green::before {
  background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
}

.bg-gradient-purple::before {
  background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
}

.mini-stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.mini-stats-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
  position: relative;
  z-index: 1;
}

.bg-gradient-blue .mini-stats-icon {
  background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);
}

.bg-gradient-green .mini-stats-icon {
  background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
}

.bg-gradient-purple .mini-stats-icon {
  background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
}

.mini-stats-content {
  position: relative;
  z-index: 1;
}

.mini-stats-content h4 {
  font-size: 24px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 2px;
}

.mini-stats-content p {
  margin: 0;
  color: #4a5568;
  font-size: 13px;
}

/* Data Table Card */
.data-table-card {
  background: white;
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.table-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e2e8f0;
  flex-wrap: wrap;
  gap: 15px;
}

.table-card-header h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin: 0;
}

.table-search {
  position: relative;
}

.table-search i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #4a5568;
}

.search-input {
  padding: 10px 15px 10px 40px;
  border: 2px solid #cbd5e0;
  border-radius: 10px;
  font-size: 14px;
  width: 300px;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #2b6cb0;
  box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
}

/* Modern Table */
.modern-data-table {
  margin: 0;
}

.modern-data-table thead th {
  background: #f7fafc;
  color: #1a365d;
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  padding: 15px 12px;
  border: none;
  white-space: nowrap;
}

.modern-data-table tbody td {
  padding: 15px 12px;
  vertical-align: middle;
  border-bottom: 1px solid #e2e8f0;
}

.modern-data-table tbody tr {
  transition: all 0.3s ease;
}

.modern-data-table tbody tr:hover {
  background: #f7fafc;
  transform: scale(1.01);
}

.row-number {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  background: #f7fafc;
  border-radius: 8px;
  font-weight: 600;
  color: #2b6cb0;
}

.badge-code {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 12px;
  display: inline-block;
  margin-right: 5px;
}

.badge-sm {
  font-size: 9px;
  padding: 2px 6px;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 5px;
  justify-content: center;
}

.btn-action {
  width: 35px;
  height: 35px;
  border-radius: 8px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
}

.btn-edit {
  background: #ebf8ff;
  color: #2b6cb0;
}

.btn-edit:hover {
  background: #2b6cb0;
  color: white;
  transform: scale(1.1);
}

.btn-delete {
  background: #fed7d7;
  color: #c53030;
}

.btn-delete:hover {
  background: #c53030;
  color: white;
  transform: scale(1.1);
}

.card-footer {
  background: #f7fafc !important;
  border-top: 1px solid #e2e8f0;
  padding: 15px 20px;
  border-radius: 0 0 15px 15px;
}

.alert-info {
  background-color: #ebf8ff;
  border-color: #bee3f8;
  color: #2c5282;
  border-radius: 10px;
  margin-bottom: 0;
}

.alert-link {
  color: #2b6cb0 !important;
  font-weight: 600;
  text-decoration: underline;
}

.btn-cancel-modal {
    padding: 10px 24px;
    background: white;
    color: #4a5568;
    border: 2px solid #cbd5e0;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-cancel-modal:hover {
    background: #4a5568;
    color: white;
}

.btn-submit-modal {
    padding: 10px 24px;
    background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(43, 108, 176, 0.3);
}

.btn-submit-modal:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(43, 108, 176, 0.4);
}

.page-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.btn-import {
    padding: 12px 25px;
    background: linear-gradient(135deg, #38a169 0%, #276749 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(56, 161, 105, 0.3);
    cursor: pointer;
    font-size: 14px;
}

.btn-import:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(56, 161, 105, 0.4);
    color: white;
}

.drop-zone {
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f7fafc;
    cursor: pointer;
    transition: all 0.25s ease;
    position: relative;
    user-select: none;
}

.drop-zone.drag-hover {
    border-color: #2b6cb0;
    background: #ebf8ff;
}

.drop-zone.file-selected {
    border-color: #38a169;
    background: #f0fff4;
    cursor: default;
}

.drop-zone input[type="file"] {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.drop-zone.file-selected input[type="file"] {
    pointer-events: none;
}

</style>

{{-- Notifikasi error import --}}
@if(session('import_errors') && count(session('import_errors')) > 0)
<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert" style="border-radius:12px;">
    <strong><i class="fas fa-exclamation-triangle"></i> Beberapa baris dilewati:</strong>
    <ul class="mb-0 mt-2">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Modal Import CSV --}}
<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden; border:none;">

            <div class="modal-header" style="background:linear-gradient(135deg,#2b6cb0 0%,#1a365d 100%); color:white; border:none; padding:18px 22px;">
                <h5 class="modal-title" id="importCsvLabel" style="font-size:16px; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-file-csv"></i> Import Supplier dari CSV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('supplier.importCsv') }}" method="POST" enctype="multipart/form-data" id="importCsvForm">
                @csrf
                <div class="modal-body" style="padding:22px;">

                    {{-- Panduan format --}}
                    <div style="background:#f0fff4; border-radius:10px; border-left:4px solid #38a169; padding:14px 16px; margin-bottom:20px;">
                        <p style="margin:0 0 8px; font-weight:600; color:#276749; font-size:13px;">
                            <i class="fas fa-info-circle"></i> Format CSV yang diperlukan
                        </p>
                        <div style="font-family:monospace; font-size:12px; color:#276749; background:white; border-radius:6px; padding:10px 12px; line-height:1.8;">
                            code,name,location<br>
                            SUP001,CV Maju Jaya,Muara Riau<br>
                            SUP002,PT Sumber Alam,Pekanbaru<br>
                            SUP003,UD Berkah,
                        </div>
                        <p style="margin:8px 0 0; font-size:11px; color:#4a5568;">
                            <strong>code</strong> dan <strong>name</strong> wajib diisi &nbsp;·&nbsp; 
                            <strong>location</strong> boleh kosong &nbsp;·&nbsp; 
                            baris pertama adalah header
                        </p>
                    </div>

                    {{-- Drag & Drop Zone --}}
                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="csv_file" id="csvFileInput" accept=".csv,.txt" required>

                        {{-- State: belum ada file --}}
                        <div id="stateDefault">
                            <i class="fas fa-cloud-upload-alt" style="font-size:42px; color:#a0aec0; display:block; margin-bottom:12px;"></i>
                            <p style="font-weight:600; color:#4a5568; margin:0 0 6px; font-size:15px;">
                                Drag &amp; drop file CSV di sini
                            </p>
                            <p style="color:#a0aec0; font-size:13px; margin:0;">
                                atau <span style="color:#2b6cb0; font-weight:600; text-decoration:underline;">klik untuk pilih file</span>
                            </p>
                            <p style="color:#a0aec0; font-size:12px; margin-top:8px;">
                                Format: .csv atau .txt &nbsp;·&nbsp; Maks. 2MB
                            </p>
                        </div>

                        {{-- State: file sudah dipilih --}}
                        <div id="stateSelected" style="display:none;">
                            <i class="fas fa-file-csv" style="font-size:42px; color:#38a169; display:block; margin-bottom:12px;"></i>
                            <p style="font-weight:600; color:#276749; margin:0 0 4px; font-size:15px;" id="dropFileName">-</p>
                            <p style="color:#68d391; font-size:13px; margin:0;">
                                <i class="fas fa-check-circle"></i> File siap diimport
                            </p>
                            <button type="button" id="btnChangeFile"
                                    style="margin-top:10px; background:none; border:none; color:#e53e3e; font-size:12px; cursor:pointer; text-decoration:underline; padding:0;">
                                <i class="fas fa-times"></i> Ganti file
                            </button>
                        </div>
                    </div>

                </div>

                <div class="modal-footer" style="border:none; background:#f7fafc; padding:14px 22px; gap:10px;">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                            style="padding:10px 22px; border:1.5px solid #cbd5e0; border-radius:8px; font-weight:600; font-size:14px; color:#4a5568; background:white; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" id="btnSubmitImport"
                            style="padding:10px 22px; background:linear-gradient(135deg,#2b6cb0 0%,#1a365d 100%); color:white; border:none; border-radius:8px; font-weight:600; font-size:14px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(43,108,176,0.3);">
                        <i class="fas fa-upload"></i> Import Sekarang
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
  const searchValue = this.value.toLowerCase();
  const tableRows = document.querySelectorAll('#supplierTable tbody tr');
  tableRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
});

// Drag & Drop CSV
(function () {
    const dropZone      = document.getElementById('dropZone');
    const fileInput     = document.getElementById('csvFileInput');
    const stateDefault  = document.getElementById('stateDefault');
    const stateSelected = document.getElementById('stateSelected');
    const dropFileName  = document.getElementById('dropFileName');
    const btnChange     = document.getElementById('btnChangeFile');
    const modal         = document.getElementById('importCsvModal');

    function applyFile(file) {
        if (!file) return;
        if (!file.name.match(/\.(csv|txt)$/i)) {
            alert('File harus berformat .csv atau .txt');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            return;
        }
        dropFileName.textContent    = file.name;
        stateDefault.style.display  = 'none';
        stateSelected.style.display = 'block';
        dropZone.classList.remove('drag-hover');
        dropZone.classList.add('file-selected');
    }

    function resetZone() {
        stateDefault.style.display  = 'block';
        stateSelected.style.display = 'none';
        dropZone.classList.remove('file-selected', 'drag-hover');
        fileInput.value = '';
    }

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) applyFile(this.files[0]);
    });

    btnChange.addEventListener('click', function (e) {
        e.stopPropagation();
        resetZone();
        setTimeout(() => fileInput.click(), 50);
    });

    dropZone.addEventListener('dragenter', function (e) {
        e.preventDefault();
        if (!dropZone.classList.contains('file-selected'))
            dropZone.classList.add('drag-hover');
    });

    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
    });

    dropZone.addEventListener('dragleave', function (e) {
        if (!dropZone.contains(e.relatedTarget))
            dropZone.classList.remove('drag-hover');
    });

    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('drag-hover');
        const file = e.dataTransfer.files[0];
        if (file) {
            try {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
            } catch (_) {}
            applyFile(file);
        }
    });

    modal.addEventListener('hidden.bs.modal', resetZone);
})();
</script>
@endpush
@endsection