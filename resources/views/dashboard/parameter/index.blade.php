@extends('layouts.master')
@section('content')
<div class="page-header-section">
  <div class="breadcrumb-section">
    <a href="{{ route('kriteria.index') }}" class="breadcrumb-link">
      <i class="fas fa-list-check"></i> Kriteria
    </a>
    <i class="fas fa-chevron-right"></i>
    <span class="breadcrumb-current">Parameter</span>
  </div>
  <a href="{{ route('kriteria.index') }}" class="btn-back-simple">
    <i class="fas fa-arrow-left"></i> Kembali
  </a>
</div>

<div class="criteria-info-card">
  <div class="criteria-info-icon">
    <i class="fas fa-sliders-h"></i>
  </div>
  <div class="criteria-info-content">
    <h2>Parameter untuk: {{ $kriteria->name }}</h2>
    <div class="criteria-meta">
      <span class="meta-item">
        <i class="fas fa-balance-scale"></i> Bobot: <strong>{{ $kriteria->weight }}</strong>
      </span>
      <span class="meta-item">
        <i class="fas fa-chart-line"></i> Tipe: <strong>{{ ucfirst($kriteria->type) }}</strong>
      </span>
      <span class="meta-item">
        <i class="fas fa-list-ol"></i> Total Parameter: <strong>{{ $parameters->count() }}</strong>
      </span>
    </div>
  </div>
  <a href="{{ route('parameter.create', $kriteria) }}" class="btn-add-param">
    <i class="fas fa-plus"></i> Tambah Parameter
  </a>
</div>

<div class="info-box-parameter mb-4">
  <div class="info-icon">
    <i class="fas fa-lightbulb"></i>
  </div>
  <div class="info-content">
    <h6>Tentang Parameter Penilaian</h6>
    <p>Parameter adalah aturan untuk mengkonversi nilai mentah (seperti harga, volume) menjadi skor utilitas (1-5). 
       Skor 5 = sangat baik, Skor 1 = kurang baik. Pastikan semua kemungkinan nilai sudah tercakup dalam parameter.</p>
  </div>
</div>

@if($parameters->count() > 0)
<div class="parameters-table-card">
  <div class="table-header">
    <h5><i class="fas fa-table"></i> Daftar Parameter</h5>
    <span class="param-count">{{ $parameters->count() }} Parameter</span>
  </div>
  
  <div class="table-responsive">
    <table class="table param-table">
      <thead>
        <tr>
          <th width="8%">Skor</th>
          <th width="15%">Operator</th>
          <th width="12%">Min Value</th>
          <th width="12%">Max Value</th>
          <th width="38%">Deskripsi</th>
          <th width="15%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($parameters as $param)
        <tr class="param-row score-{{ $param->score }}">
          <td>
            <div class="score-display score-{{ $param->score }}">
              <span class="score-number">{{ $param->score }}</span>
              <span class="score-label">
                @if($param->score == 5) Sangat Baik
                @elseif($param->score == 4) Baik
                @elseif($param->score == 3) Cukup
                @elseif($param->score == 2) Kurang
                @else Buruk
                @endif
              </span>
            </div>
          </td>
          <td>
            <span class="operator-badge">{{ $param->operator }}</span>
          </td>
          <td>
            @if($param->min_value)
              <span class="value-display">{{ number_format($param->min_value, 0, ',', '.') }}</span>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            @if($param->max_value)
              <span class="value-display">{{ number_format($param->max_value, 0, ',', '.') }}</span>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            <div class="description-text">{{ $param->description }}</div>
          </td>
          <td class="text-center">
            <div class="action-buttons">
              <a href="{{ route('parameter.edit', [$kriteria, $param]) }}" 
                 class="btn-action btn-edit" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('parameter.destroy', [$kriteria, $param]) }}" 
                    method="post" style="display:inline"
                    onsubmit="return confirm('Yakin ingin menghapus parameter ini?')">
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

<div class="example-section-param mt-4">
  <h5 class="example-title"><i class="fas fa-book-open"></i> Contoh Penggunaan Parameter</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <div class="example-card-param">
        <h6><i class="fas fa-money-bill-wave"></i> Harga per kg (Cost)</h6>
        <div class="example-list">
          <div class="example-item">
            <span class="ex-score">5</span>
            <span class="ex-operator">≤</span>
            <span class="ex-value">180</span>
            <span class="ex-desc">Harga sangat murah</span>
          </div>
          <div class="example-item">
            <span class="ex-score">4</span>
            <span class="ex-operator">between</span>
            <span class="ex-value">181 - 184</span>
            <span class="ex-desc">Harga murah</span>
          </div>
          <div class="example-item">
            <span class="ex-score">3</span>
            <span class="ex-operator">between</span>
            <span class="ex-value">185 - 189</span>
            <span class="ex-desc">Harga sedang</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="example-card-param">
        <h6><i class="fas fa-box"></i> Volume per bulan (Benefit)</h6>
        <div class="example-list">
          <div class="example-item">
            <span class="ex-score">5</span>
            <span class="ex-operator">≥</span>
            <span class="ex-value">15000</span>
            <span class="ex-desc">Volume sangat besar</span>
          </div>
          <div class="example-item">
            <span class="ex-score">4</span>
            <span class="ex-operator">between</span>
            <span class="ex-value">10000 - 14999</span>
            <span class="ex-desc">Volume besar</span>
          </div>
          <div class="example-item">
            <span class="ex-score">3</span>
            <span class="ex-operator">between</span>
            <span class="ex-value">7000 - 9999</span>
            <span class="ex-desc">Volume sedang</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@else
<div class="empty-state-param">
  <div class="empty-icon">
    <i class="fas fa-inbox"></i>
  </div>
  <h4>Belum Ada Parameter</h4>
  <p>Tambahkan parameter penilaian untuk kriteria <strong>{{ $kriteria->name }}</strong></p>
  <a href="{{ route('parameter.create', $kriteria) }}" class="btn-add-first">
    <i class="fas fa-plus-circle"></i> Tambah Parameter Pertama
  </a>
</div>
@endif

<style>
.page-header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

.breadcrumb-section {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #4a5568;
}

.breadcrumb-link {
  color: #2b6cb0;
  text-decoration: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: all 0.3s ease;
}

.breadcrumb-link:hover {
  color: #1a365d;
}

.breadcrumb-current {
  color: #1a365d;
  font-weight: 700;
}

.btn-back-simple {
  padding: 10px 20px;
  background: white;
  color: #4a5568;
  border: 2px solid #cbd5e0;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-back-simple:hover {
  background: #4a5568;
  color: white;
  border-color: #4a5568;
}

.criteria-info-card {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  gap: 25px;
  box-shadow: 0 10px 30px rgba(43, 108, 176, 0.3);
}

.criteria-info-icon {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  color: white;
  flex-shrink: 0;
}

.criteria-info-content {
  flex: 1;
  color: white;
}

.criteria-info-content h2 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 15px;
}

.criteria-meta {
  display: flex;
  gap: 25px;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  opacity: 0.95;
}

.meta-item i {
  font-size: 16px;
}

.meta-item strong {
  font-weight: 700;
}

.btn-add-param {
  padding: 14px 28px;
  background: white;
  color: #2b6cb0;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  flex-shrink: 0;
}

.btn-add-param:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  color: #2b6cb0;
}

.info-box-parameter {
  background: linear-gradient(135deg, #fffaf0 0%, #fef3c7 100%);
  border-radius: 15px;
  padding: 20px;
  display: flex;
  gap: 15px;
  border-left: 4px solid #dd6b20;
}

.info-icon {
  width: 50px;
  height: 50px;
  background: rgba(237, 137, 54, 0.2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: #dd6b20;
  flex-shrink: 0;
}

.info-content h6 {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 8px;
}

.info-content p {
  margin: 0;
  color: #744210;
  font-size: 14px;
  line-height: 1.6;
}

.parameters-table-card {
  background: white;
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e2e8f0;
}

.table-header h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.param-count {
  background: #ebf8ff;
  color: #2b6cb0;
  padding: 6px 15px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
}

.param-table {
  margin: 0;
}

.param-table thead th {
  background: #f7fafc;
  color: #1a365d;
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  padding: 15px 12px;
  border: none;
}

.param-table tbody td {
  padding: 15px 12px;
  vertical-align: middle;
  border-bottom: 1px solid #e2e8f0;
}

.param-row:hover {
  background: #f7fafc;
}

.score-display {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
}

.score-number {
  width: 45px;
  height: 45px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
  color: white;
}

.score-5 .score-number {
  background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
}

.score-4 .score-number {
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
}

.score-3 .score-number {
  background: linear-gradient(135deg, #ecc94b 0%, #d69e2e 100%);
}

.score-2 .score-number {
  background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
}

.score-1 .score-number {
  background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
}

.score-label {
  font-size: 11px;
  font-weight: 600;
  color: #4a5568;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.operator-badge {
  display: inline-block;
  padding: 6px 12px;
  background: #ebf8ff;
  color: #2b6cb0;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  font-family: 'Courier New', monospace;
}

.value-display {
  font-weight: 600;
  color: #1a365d;
  font-size: 15px;
}

.description-text {
  color: #4a5568;
  font-size: 14px;
  line-height: 1.5;
}

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
  text-decoration: none;
}

.btn-action.btn-edit {
  background: #ebf8ff;
  color: #2b6cb0;
}

.btn-action.btn-edit:hover {
  background: #2b6cb0;
  color: white;
  transform: scale(1.1);
}

.btn-action.btn-delete {
  background: #fed7d7;
  color: #c53030;
}

.btn-action.btn-delete:hover {
  background: #c53030;
  color: white;
  transform: scale(1.1);
}

.empty-state-param {
  background: white;
  border-radius: 20px;
  padding: 80px 40px;
  text-align: center;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.empty-icon {
  width: 120px;
  height: 120px;
  background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 60px;
  color: #cbd5e0;
  margin: 0 auto 30px;
}

.empty-state-param h4 {
  font-size: 24px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 10px;
}

.empty-state-param p {
  color: #4a5568;
  font-size: 16px;
  margin-bottom: 30px;
}

.btn-add-first {
  padding: 14px 30px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.3);
}

.btn-add-first:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(43, 108, 176, 0.4);
  color: white;
}

/* Example Section */
.example-section-param {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.example-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.example-card-param {
  background: #f7fafc;
  border: 2px solid #e2e8f0;
  border-radius: 15px;
  padding: 20px;
  height: 100%;
}

.example-card-param h6 {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.example-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.example-item {
  background: white;
  padding: 12px 15px;
  border-radius: 10px;
  display: grid;
  grid-template-columns: 40px 80px 120px 1fr;
  align-items: center;
  gap: 10px;
  font-size: 13px;
}

.ex-score {
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
}

.ex-operator {
  color: #2b6cb0;
  font-weight: 700;
  font-family: 'Courier New', monospace;
}

.ex-value {
  color: #1a365d;
  font-weight: 600;
}

.ex-desc {
  color: #4a5568;
}

@media (max-width: 768px) {
  .criteria-info-card {
    flex-direction: column;
    text-align: center;
  }
  
  .criteria-meta {
    justify-content: center;
  }
  
  .btn-add-param {
    width: 100%;
    justify-content: center;
  }
  
  .example-item {
    grid-template-columns: 1fr;
    text-align: center;
  }
  
  .ex-score {
    margin: 0 auto;
  }
}
</style>

@endsection