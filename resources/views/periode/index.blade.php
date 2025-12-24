@extends('layouts.master')
@section('content')
<div class="page-header-section">
  <div>
    <h1 class="page-title"><i class="fas fa-calendar-alt"></i> Kelola Periode Penilaian</h1>
    <p class="page-subtitle">Atur periode evaluasi supplier dan copy data antar periode</p>
  </div>
  <div class="page-actions">
    <a href="{{ route('periode.create') }}" class="btn-add">
      <i class="fas fa-plus"></i> Tambah Periode Baru
    </a>
  </div>
</div>

<!-- Active Periode Alert -->
@if($activePeriode)
<div class="active-periode-alert">
  <div class="alert-icon">
    <i class="fas fa-check-circle"></i>
  </div>
  <div class="alert-content">
    <h5>Periode Aktif Saat Ini</h5>
    <p><strong>{{ $activePeriode->name }}</strong> ({{ $activePeriode->start_date->format('d M Y') }} - {{ $activePeriode->end_date->format('d M Y') }})</p>
  </div>
</div>
@endif

<!-- Periode List -->
<div class="periode-grid">
  @forelse($periodes as $periode)
  <div class="periode-card {{ $periode->is_active ? 'active-card' : '' }}">
    <div class="periode-header">
      <div class="periode-badge {{ $periode->is_active ? 'badge-active' : 'badge-inactive' }}">
        {{ $periode->is_active ? 'AKTIF' : 'NON-AKTIF' }}
      </div>
      @if($periode->is_active)
      <div class="active-icon">
        <i class="fas fa-star"></i>
      </div>
      @endif
    </div>

    <div class="periode-body">
      <h4>{{ $periode->name }}</h4>
      <p class="periode-code">
        <i class="fas fa-tag"></i> {{ $periode->code }}
      </p>
      
      <div class="periode-date">
        <i class="fas fa-calendar"></i>
        <span>{{ $periode->start_date->format('d M Y') }} - {{ $periode->end_date->format('d M Y') }}</span>
      </div>

      @if($periode->description)
      <div class="periode-desc">
        <i class="fas fa-info-circle"></i>
        {{ Str::limit($periode->description, 100) }}
      </div>
      @endif

      <div class="periode-stats">
        <div class="stat-box">
          <i class="fas fa-users"></i>
          <div>
            <strong>{{ $periode->suppliers->count() }}</strong>
            <span>Supplier</span>
          </div>
        </div>
        <div class="stat-box">
          <i class="fas fa-list-check"></i>
          <div>
            <strong>{{ $periode->criterias->count() }}</strong>
            <span>Kriteria</span>
          </div>
        </div>
      </div>
    </div>

    <div class="periode-actions">
      @if(!$periode->is_active)
      <form action="{{ route('periode.setActive', $periode) }}" method="POST" style="display:inline">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-action btn-activate" title="Aktifkan Periode">
          <i class="fas fa-power-off"></i> Aktifkan
        </button>
      </form>
      @endif

      <a href="{{ route('periode.edit', $periode) }}" class="btn-action btn-edit" title="Edit">
        <i class="fas fa-edit"></i>
      </a>

      @if(!$periode->is_active)
      <form action="{{ route('periode.destroy', $periode) }}" method="POST" style="display:inline"
            onsubmit="return confirm('Yakin ingin menghapus periode {{ $periode->name }}? Semua data supplier dan kriteria di periode ini akan ikut terhapus!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-action btn-delete" title="Hapus">
          <i class="fas fa-trash"></i>
        </button>
      </form>
      @endif
    </div>
  </div>
  @empty
  <div class="empty-state">
    <i class="fas fa-calendar-times"></i>
    <h3>Belum Ada Periode</h3>
    <p>Tambahkan periode penilaian pertama Anda</p>
    <a href="{{ route('periode.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Periode
    </a>
  </div>
  @endforelse
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

/* Active Periode Alert */
.active-periode-alert {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  border-radius: 15px;
  padding: 20px 25px;
  color: white;
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
  box-shadow: 0 5px 20px rgba(43, 108, 176, 0.3);
}

.alert-icon {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
}

.alert-content h5 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 5px;
}

.alert-content p {
  margin: 0;
  opacity: 0.95;
}

/* Periode Grid */
.periode-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 25px;
}

.periode-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.periode-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.periode-card.active-card {
  border-color: #2b6cb0;
  box-shadow: 0 5px 20px rgba(43, 108, 176, 0.3);
}

.periode-header {
  background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.active-card .periode-header {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
}

.periode-badge {
  padding: 6px 15px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-active {
  background: rgba(255, 255, 255, 0.3);
  color: white;
}

.badge-inactive {
  background: #e2e8f0;
  color: #4a5568;
}

.active-icon {
  font-size: 24px;
  color: white;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.8; }
}

/* Periode Body */
.periode-body {
  padding: 25px;
}

.periode-body h4 {
  font-size: 20px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 10px;
}

.periode-code {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #2b6cb0;
  font-weight: 600;
  margin-bottom: 15px;
}

.periode-date {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 15px;
  background: #f7fafc;
  border-radius: 10px;
  font-size: 14px;
  color: #4a5568;
  margin-bottom: 15px;
}

.periode-date i {
  color: #2b6cb0;
  font-size: 16px;
}

.periode-desc {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 13px;
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 20px;
  padding: 12px;
  background: #f7fafc;
  border-radius: 10px;
}

.periode-desc i {
  color: #2b6cb0;
  margin-top: 2px;
}

/* Stats */
.periode-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  padding-top: 20px;
  border-top: 2px solid #e2e8f0;
}

.stat-box {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f7fafc;
  border-radius: 10px;
}

.stat-box i {
  font-size: 24px;
  color: #2b6cb0;
}

.stat-box strong {
  display: block;
  font-size: 20px;
  font-weight: 700;
  color: #1a365d;
}

.stat-box span {
  font-size: 12px;
  color: #4a5568;
}

/* Actions */
.periode-actions {
  padding: 20px;
  background: #f7fafc;
  display: flex;
  gap: 10px;
  justify-content: center;
  border-top: 2px solid #e2e8f0;
}

.btn-action {
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  font-weight: 600;
  font-size: 13px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
}

.btn-activate {
  background: #2b6cb0;
  color: white;
  flex: 1;
}

.btn-activate:hover {
  background: #2c5282;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.3);
}

.btn-edit {
  background: #3182ce;
  color: white;
}

.btn-edit:hover {
  background: #2b6cb0;
  transform: scale(1.05);
}

.btn-delete {
  background: #e53e3e;
  color: white;
}

.btn-delete:hover {
  background: #c53030;
  transform: scale(1.05);
}

/* Empty State */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.empty-state i {
  font-size: 64px;
  color: #cbd5e0;
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 24px;
  color: #1a365d;
  margin-bottom: 10px;
}

.empty-state p {
  color: #4a5568;
  margin-bottom: 25px;
}

.btn-primary {
  padding: 12px 30px;
  background: #2b6cb0;
  color: white;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: #2c5282;
  transform: translateY(-2px);
  color: white;
}
</style>

@endsection