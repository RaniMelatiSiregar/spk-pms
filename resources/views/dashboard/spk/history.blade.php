@extends('layouts.master')
@section('content')

<div class="page-header-section">
  <div class="header-content">
    <div class="title-section">
      <h1 class="page-title"><i class="fas fa-clock-rotate-left"></i> History Perhitungan</h1>
      <p class="page-subtitle">Lihat hasil perhitungan SMART berdasarkan periode</p>
    </div>
    <div class="periode-section">
      <form method="GET" class="periode-form">
        <div class="form-group">
          <label class="form-label"><i class="fas fa-calendar-alt"></i> Pilih Periode</label>
          <select name="periode_id" class="periode-select" onchange="this.form.submit()">
            @foreach($periodes as $p)
              <option value="{{ $p->id }}" {{ $p->id == $periode_id ? 'selected' : '' }}>
                {{ $p->name }} ({{ $p->start_date->format('M Y') }})
              </option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </div>
</div>

@if($results->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">
      <i class="fas fa-chart-bar"></i>
    </div>
    <h3>Belum ada perhitungan</h3>
    <p>Belum ada data perhitungan untuk periode yang dipilih.</p>
  </div>
@else
  @php
    $currentPeriode = $periodes->firstWhere('id', $periode_id);
  @endphp
  
  <div class="results-card">
    <div class="card-header">
      <h5><i class="fas fa-trophy"></i> Hasil Ranking Supplier</h5>
      <div class="header-badges">
        <span class="badge badge-primary">{{ $results->count() }} Supplier</span>
        @if($currentPeriode)
          <span class="badge badge-success">Periode: {{ $currentPeriode->name }}</span>
        @endif
      </div>
    </div>
    <div class="table-responsive">
      <table class="results-table">
        <thead>
          <tr>
            <th class="rank-col">Peringkat</th>
            <th class="supplier-col">Supplier</th>
            <th class="score-col">Score</th>
            <th class="status-col">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($results as $i => $r)
          <tr class="{{ $i < 3 ? 'top-three' : '' }}">
            <td class="rank-cell">
              <div class="rank-badge rank-{{ $i + 1 }}">
                @if($i + 1 == 1)
                  <i class="fas fa-crown"></i>
                @elseif($i + 1 == 2)
                  <i class="fas fa-medal"></i>
                @elseif($i + 1 == 3)
                  <i class="fas fa-award"></i>
                @else
                  {{ $i + 1 }}
                @endif
              </div>
            </td>
            <td class="supplier-cell">
              <div class="supplier-info">
                <div class="supplier-code">{{ $r['supplier']->code }}</div>
                <div class="supplier-name">{{ $r['supplier']->name }}</div>
              </div>
            </td>
            <td class="score-cell">
              <div class="score-value">{{ number_format($r['score'], 3) }}</div>
              <div class="score-bar">
                <div class="score-progress" style="width: {{ ($r['score'] / 5) * 100 }}%"></div>
              </div>
            </td>
            <td class="status-cell">
              @if($r['score'] >= 4.5)
                <span class="status-badge status-excellent">Excellent</span>
              @elseif($r['score'] >= 3.5)
                <span class="status-badge status-good">Good</span>
              @elseif($r['score'] >= 2.5)
                <span class="status-badge status-fair">Fair</span>
              @else
                <span class="status-badge status-poor">Poor</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

<style>
.page-header-section {
  margin-bottom: 30px;
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 30px;
}

.title-section {
  flex: 1;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 5px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.page-subtitle {
  color: #7f8c8d;
  font-size: 14px;
  margin: 0;
}

.periode-section {
  min-width: 280px;
}

.periode-form {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #e9ecef;
}

.form-group {
  margin: 0;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 8px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.periode-select {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 14px;
  background: white;
  color: #2c3e50;
  transition: all 0.3s ease;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233498db' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 15px center;
  background-size: 16px;
}

.periode-select:focus {
  border-color: #3498db;
  box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
  outline: none;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.empty-icon {
  font-size: 64px;
  color: #bdc3c7;
  margin-bottom: 20px;
}

.empty-state h3 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.empty-state p {
  color: #7f8c8d;
  margin: 0;
}

.results-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  overflow: hidden;
}

.card-header {
  padding: 25px 25px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.card-header h5 {
  font-size: 18px;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.header-badges {
  display: flex;
  gap: 10px;
}

.badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.badge-primary {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  color: white;
}

.badge-success {
  background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
  color: white;
}

.results-table {
  width: 100%;
  border-collapse: collapse;
}

.results-table thead {
  background: #f8f9fa;
}

.results-table th {
  padding: 15px 20px;
  text-align: left;
  font-weight: 600;
  color: #2c3e50;
  font-size: 13px;
  text-transform: uppercase;
  border-bottom: 2px solid #e9ecef;
}

.rank-col { width: 100px; }
.supplier-col { width: 40%; }
.score-col { width: 30%; }
.status-col { width: 150px; }

.results-table tbody tr {
  border-bottom: 1px solid #f1f3f5;
  transition: all 0.3s ease;
}

.results-table tbody tr:hover {
  background: #f8f9fa;
}

.results-table tbody tr.top-three {
  background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
}

.rank-cell {
  padding: 15px 20px;
  text-align: center;
}

.rank-badge {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 14px;
  color: white;
}

.rank-1 {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #856404 !important;
}

.rank-2 {
  background: linear-gradient(135deg, #c0c0c0 0%, #e0e0e0 100%);
  color: #495057 !important;
}

.rank-3 {
  background: linear-gradient(135deg, #cd853f 0%, #d4a574 100%);
  color: white !important;
}

.rank-4, .rank-5, .rank-6, .rank-7, .rank-8, .rank-9, .rank-10 {
  background: #f8f9fa;
  color: #6c757d;
}

.supplier-cell {
  padding: 15px 20px;
}

.supplier-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.supplier-code {
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
}

.supplier-name {
  color: #7f8c8d;
  font-size: 13px;
}

.score-cell {
  padding: 15px 20px;
}

.score-value {
  font-weight: 700;
  color: #2c3e50;
  font-size: 16px;
  margin-bottom: 8px;
}

.score-bar {
  width: 100%;
  height: 8px;
  background: #f1f3f5;
  border-radius: 10px;
  overflow: hidden;
}

.score-progress {
  height: 100%;
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  border-radius: 10px;
  transition: width 0.3s ease;
}

.status-cell {
  padding: 15px 20px;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
}

.status-excellent {
  background: #d4edda;
  color: #155724;
}

.status-good {
  background: #d1ecf1;
  color: #0c5460;
}

.status-fair {
  background: #fff3cd;
  color: #856404;
}

.status-poor {
  background: #f8d7da;
  color: #721c24;
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 20px;
  }
  
  .periode-section {
    min-width: 100%;
  }
  
  .card-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .header-badges {
    align-self: flex-start;
  }
  
  .results-table {
    font-size: 14px;
  }
  
  .results-table th,
  .results-table td {
    padding: 12px 15px;
  }
}
</style>

@endsection