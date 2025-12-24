@extends('layouts.master')
@section('content')
<div class="page-header-section">
  <div>
    <h1 class="page-title"><i class="fas fa-list-check"></i> Data Kriteria Penilaian</h1>
    <p class="page-subtitle">Kelola kriteria untuk metode SMART dalam pemilihan supplier</p>
  </div>
  <div class="page-actions">
    <a href="{{ route('kriteria.create') }}" class="btn-add">
      <i class="fas fa-plus"></i> Tambah Kriteria
    </a>
  </div>
</div>

<div class="weight-summary-card mb-4">
  <div class="weight-header">
    <h5><i class="fas fa-balance-scale"></i> Total Bobot Kriteria</h5>
    <div class="weight-display">
      <span class="weight-value">{{ $criterias->sum('weight') }}</span>
      <span class="weight-label">/ 1.00</span>
    </div>
  </div>
  <div class="weight-progress">
    <div class="progress" style="height: 10px;">
      <div class="progress-bar {{ $criterias->sum('weight') == 1 ? 'bg-success' : 'bg-warning' }}" 
           style="width: {{ $criterias->sum('weight') * 100 }}%">
      </div>
    </div>
  </div>
  @if($criterias->sum('weight') != 1)
    <div class="weight-warning">
      <i class="fas fa-exclamation-triangle"></i>
      Peringatan: Total bobot harus sama dengan 1.00 untuk perhitungan yang akurat!
    </div>
  @else
    <div class="weight-success">
      <i class="fas fa-check-circle"></i>
      Total bobot sudah sesuai dan siap untuk perhitungan.
    </div>
  @endif
</div>

<div class="row g-3">
  @foreach($criterias as $i => $c)
  <div class="col-md-6">
    <div class="criteria-card">
      <div class="criteria-header">
        <div class="criteria-icon" style="background: linear-gradient(135deg, {{ ['#2b6cb0', '#3182ce', '#38a169', '#dd6b20'][$i % 4] }} 0%, {{ ['#1a365d', '#2c5282', '#2f855a', '#c05621'][$i % 4] }} 100%);">
          <i class="fas {{ ['fa-money-bill-wave', 'fa-box', 'fa-clock', 'fa-calendar-check'][$i % 4] }}"></i>
        </div>
        <div class="criteria-title">
          <h5>{{ $c->name }}</h5>
          <p>{{ ucfirst($c->type) }} · {{ $c->parameters->count() }} Parameter</p>
        </div>
        <div class="criteria-actions">
          <a href="{{ route('parameter.index', $c) }}" class="btn-action-mini btn-params" title="Parameter">
            <i class="fas fa-sliders-h"></i>
          </a>
          <a href="{{ route('kriteria.edit', $c) }}" class="btn-action-mini btn-edit" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <form action="{{ route('kriteria.destroy', $c) }}" method="post" style="display:inline"
                onsubmit="return confirm('Yakin ingin menghapus kriteria {{ $c->name }}?')">
            @csrf 
            @method('DELETE')
            <button type="submit" class="btn-action-mini btn-delete" title="Hapus">
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </div>
      </div>

      <div class="criteria-body">
        <div class="weight-section">
          <label>Bobot Kriteria</label>
          <div class="weight-bar">
            <div class="weight-fill" style="width: {{ $c->weight * 100 }}%; background: linear-gradient(135deg, {{ ['#2b6cb0', '#3182ce', '#38a169', '#dd6b20'][$i % 4] }} 0%, {{ ['#1a365d', '#2c5282', '#2f855a', '#c05621'][$i % 4] }} 100%);">
              <span>{{ $c->weight }}</span>
            </div>
          </div>
          <small>{{ round($c->weight * 100, 1) }}% dari total</small>
        </div>

        @if($c->parameters->count() > 0)
        <div class="parameter-section">
          <label><i class="fas fa-sliders-h"></i> Parameter Penilaian ({{ $c->parameters->count() }})</label>
          <div class="parameter-content">
            <div class="parameter-list">
              @foreach($c->parameters->take(3) as $param)
                <div class="parameter-item">
                  <div class="parameter-score">
                    <span class="score-badge">{{ $param->score }}</span>
                  </div>
                  <div class="parameter-description">
                    {{ $param->description }}
                  </div>
                </div>
              @endforeach
              @if($c->parameters->count() > 3)
                <div class="parameter-more">
                  <a href="{{ route('parameter.index', $c) }}">
                    <i class="fas fa-ellipsis-h"></i> Lihat {{ $c->parameters->count() - 3 }} parameter lainnya
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
        @else
        <div class="no-parameter">
          <i class="fas fa-info-circle"></i>
          <span>Belum ada parameter.</span>
          <a href="{{ route('parameter.index', $c) }}">Tambah Parameter</a>
        </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="info-box-criteria mt-4">
  <div class="info-icon">
    <i class="fas fa-info-circle"></i>
  </div>
  <div class="info-content">
    <h6>Tentang Metode SMART</h6>
    <p>Metode SMART (Simple Multi-Attribute Rating Technique) menggunakan bobot untuk setiap kriteria. Total bobot semua kriteria harus = 1.00 (atau 100%). Semakin besar bobot, semakin penting kriteria tersebut dalam penilaian.</p>
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

.weight-summary-card {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.weight-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.weight-header h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.weight-display {
  display: flex;
  align-items: baseline;
  gap: 5px;
}

.weight-value {
  font-size: 36px;
  font-weight: 700;
  color: #2b6cb0;
}

.weight-label {
  font-size: 18px;
  color: #4a5568;
}

.weight-progress {
  margin-bottom: 15px;
}

.weight-progress .progress {
  background: #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
}

.weight-warning {
  background: #fffaf0;
  color: #744210;
  padding: 12px 15px;
  border-radius: 10px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #fed7d7;
}

.weight-success {
  background: #f0fff4;
  color: #22543d;
  padding: 12px 15px;
  border-radius: 10px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #c6f6d5;
}

.criteria-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  height: 100%;
}

.criteria-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.criteria-header {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 25px;
  background: #f7fafc;
  border-bottom: 2px solid #e2e8f0;
}

.criteria-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  flex-shrink: 0;
}

.criteria-title {
  flex: 1;
}

.criteria-title h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 3px;
}

.criteria-title p {
  font-size: 13px;
  color: #4a5568;
  margin: 0;
}

.criteria-actions {
  display: flex;
  gap: 5px;
}

.btn-action-mini {
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
  text-decoration: none;
}

.btn-action-mini.btn-params {
  background: #e6fffa;
  color: #2c7a7b;
}

.btn-action-mini.btn-params:hover {
  background: #2c7a7b;
  color: white;
  transform: scale(1.1);
}

.btn-action-mini.btn-edit {
  background: #ebf8ff;
  color: #2b6cb0;
}

.btn-action-mini.btn-edit:hover {
  background: #2b6cb0;
  color: white;
  transform: scale(1.1);
}

.btn-action-mini.btn-delete {
  background: #fed7d7;
  color: #c53030;
}

.btn-action-mini.btn-delete:hover {
  background: #c53030;
  color: white;
  transform: scale(1.1);
}

.criteria-body {
  padding: 25px;
}

.weight-section {
  margin-bottom: 25px;
}

.weight-section label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.weight-bar {
  height: 45px;
  background: #f7fafc;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 8px;
  position: relative;
}

.weight-fill {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 18px;
  transition: width 0.5s ease;
}

.weight-section small {
  font-size: 12px;
  color: #4a5568;
}

.parameter-section {
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}

.parameter-section label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: #4a5568;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.parameter-content {
  background: #f7fafc;
  padding: 20px;
  border-radius: 15px;
  border: 1px solid #e2e8f0;
}

.parameter-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.parameter-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 12px 15px;
  background: white;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
}

.parameter-item:hover {
  transform: translateX(5px);
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  border-color: #cbd5e0;
}

.parameter-score {
  flex-shrink: 0;
}

.score-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 10px;
  font-weight: 700;
  font-size: 16px;
  box-shadow: 0 3px 8px rgba(43, 108, 176, 0.3);
}

.parameter-description {
  flex: 1;
  font-size: 14px;
  color: #4a5568;
  font-weight: 500;
  line-height: 1.4;
}

.parameter-more {
  text-align: center;
  padding: 10px;
}

.parameter-more a {
  color: #2b6cb0;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.parameter-more a:hover {
  color: #1a365d;
}

.no-parameter {
  padding: 20px;
  background: #fffaf0;
  border-radius: 10px;
  text-align: center;
  color: #744210;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}

.no-parameter i {
  font-size: 24px;
}

.no-parameter a {
  color: #dd6b20;
  font-weight: 600;
  text-decoration: none;
}

.no-parameter a:hover {
  color: #c05621;
  text-decoration: underline;
}

.info-box-criteria {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  border-radius: 20px;
  padding: 25px;
  display: flex;
  gap: 20px;
  color: white;
  box-shadow: 0 5px 20px rgba(43, 108, 176, 0.3);
}

.info-icon {
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  flex-shrink: 0;
}

.info-content h6 {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 10px;
}

.info-content p {
  margin: 0;
  line-height: 1.6;
  opacity: 0.95;
}

@media (max-width: 768px) {
  .page-header-section {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .criteria-header {
    padding: 20px;
  }
  
  .parameter-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    text-align: left;
  }
  
  .score-badge {
    width: 35px;
    height: 35px;
    font-size: 14px;
  }
  
  .parameter-description {
    font-size: 13px;
  }
}
</style>

@endsection