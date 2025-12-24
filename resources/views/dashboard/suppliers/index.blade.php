@extends('layouts.master')
@section('content')
<div class="page-header-section">
  <div>
    <h1 class="page-title"><i class="fas fa-users"></i> Data Alternatif (Supplier)</h1>
    <p class="page-subtitle">Kelola data supplier untuk perhitungan SPK</p>
  </div>
  <div class="page-actions">
    <a href="{{ route('supplier.create') }}" class="btn-add">
      <i class="fas fa-plus"></i> Tambah Supplier
    </a>
  </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
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
  <div class="col-md-3">
    <div class="mini-stats-card bg-gradient-green">
      <div class="mini-stats-icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="mini-stats-content">
        <h4>Rp {{ number_format($suppliers->avg('price_per_kg'), 0, ',', '.') }}</h4>
        <p>Rata-rata Harga</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="mini-stats-card bg-gradient-orange">
      <div class="mini-stats-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="mini-stats-content">
        <h4>{{ number_format($suppliers->sum('volume_per_month'), 0, ',', '.') }}</h4>
        <p>Total Volume/bulan</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="mini-stats-card bg-gradient-purple">
      <div class="mini-stats-icon">
        <i class="fas fa-percentage"></i>
      </div>
      <div class="mini-stats-content">
        <h4>{{ round($suppliers->avg('on_time_percent'), 1) }}%</h4>
        <p>Rata-rata Ketepatan</p>
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
          <th width="10%">Kode</th>
          <th width="20%">Nama Supplier</th>
          <th width="10%">Lokasi</th>
          <th width="12%">Harga/kg</th>
          <th width="13%">Volume/bulan</th>
          <th width="10%">Ketepatan</th>
          <th width="10%">Frekuensi</th>
          <th width="10%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suppliers as $i => $s)
        <tr>
          <td><span class="row-number">{{ $i + 1 }}</span></td>
          <td><span class="badge-code">{{ $s->code }}</span></td>
          <td><strong>{{ $s->name }}</strong></td>
          <td>
            @if($s->location)
              <i class="fas fa-map-marker-alt text-danger"></i> {{ $s->location }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td><span class="price-tag">Rp {{ number_format($s->price_per_kg, 0, ',', '.') }}</span></td>
          <td>{{ number_format($s->volume_per_month, 0, ',', '.') }} kg</td>
          <td>
            <div class="progress-wrapper">
              <div class="progress" style="height: 8px;">
                <div class="progress-bar 
                  @if($s->on_time_percent >= 90) bg-success
                  @elseif($s->on_time_percent >= 75) bg-warning
                  @else bg-danger
                  @endif" 
                  style="width: {{ $s->on_time_percent }}%">
                </div>
              </div>
              <span class="progress-label">{{ $s->on_time_percent }}%</span>
            </div>
          </td>
          <td>
            <span class="freq-badge">{{ $s->freq_per_month }}x/bulan</span>
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

.bg-gradient-orange::before {
  background: linear-gradient(135deg, #dd6b20 0%, #ed8936 100%);
}

.bg-gradient-purple::before {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
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

.bg-gradient-orange .mini-stats-icon {
  background: linear-gradient(135deg, #dd6b20 0%, #ed8936 100%);
}

.bg-gradient-purple .mini-stats-icon {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
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
}

.price-tag {
  background: #e6fffa;
  color: #234e52;
  padding: 5px 10px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 13px;
  display: inline-block;
}

.progress-wrapper {
  display: flex;
  align-items: center;
  gap: 8px;
}

.progress-wrapper .progress {
  flex: 1;
  background: #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
}

.progress-label {
  font-size: 12px;
  font-weight: 600;
  color: #1a365d;
  min-width: 40px;
}

.freq-badge {
  background: #ebf8ff;
  color: #2b6cb0;
  padding: 5px 10px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 12px;
  display: inline-block;
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
</style>

@push('scripts')
<script>
// Simple search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
  const searchValue = this.value.toLowerCase();
  const tableRows = document.querySelectorAll('#supplierTable tbody tr');
  
  tableRows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchValue) ? '' : 'none';
  });
});
</script>
@endpush
@endsection