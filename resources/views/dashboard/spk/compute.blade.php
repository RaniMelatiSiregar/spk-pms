@extends('layouts.master')
@section('content')
<div class="compute-header">
  <div>
    <h1 class="page-title"><i class="fas fa-calculator"></i> Perhitungan Metode SMART</h1>
    <p class="page-subtitle">Simple Multi-Attribute Rating Technique untuk Pemilihan Supplier</p>
  </div>
  <div class="header-actions">
    <a href="{{ route('spk.result') }}" class="btn-result">
      <i class="fas fa-trophy"></i> Lihat Hasil Akhir
    </a>
  </div>
</div>

<div class="method-info-card">
  <div class="method-icon">
    <i class="fas fa-lightbulb"></i>
  </div>
  <div class="method-content">
    <h5>Tentang Metode SMART</h5>
    <p>SMART adalah metode pengambilan keputusan multi kriteria yang menggunakan pembobotan linear untuk menghitung nilai utilitas setiap alternatif. Skor akhir dihitung dengan rumus:</p>
    <div class="formula">
      <strong>Nilai(A) = Σ (Wⱼ × Uⱼ(A))</strong>
      <span>dimana Wⱼ = bobot kriteria j, Uⱼ = nilai utilitas kriteria j</span>
    </div>
  </div>
</div>

<div class="step-section">
  <div class="step-header">
    <div class="step-number">1</div>
    <div class="step-title">
      <h4>Kriteria dan Bobot Penilaian</h4>
      <p>Berikut adalah kriteria yang digunakan beserta bobotnya</p>
    </div>
  </div>
  
  <div class="criteria-weight-table">
    <table class="table modern-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kriteria</th>
          <th>Bobot</th>
          <th>Persentase</th>
          <th>Parameter</th>
        </tr>
      </thead>
      <tbody>
        @foreach($criteriaList as $i => $c)
<tr>
  <td>{{ $i + 1 }}</td>
  <td><strong>{{ $c->name }}</strong></td>
  <td><span class="weight-badge">{{ $c->weight }}</span></td>
  <td>
    <div class="percent-bar">
      <div class="percent-fill" style="width: {{ $c->weight * 100 }}%; background: {{ ['#2c3e50', '#3498db', '#27ae60', '#e67e22'][$i % 4] }};">
        {{ round($c->weight * 100) }}%
      </div>
    </div>
  </td>
  <td class="text-muted small">
    @foreach($c->parameters as $p)
        {{ $p->score }} = {{ $p->description }}@if(!$loop->last); @endif
    @endforeach
  </td>
</tr>
@endforeach

        <tr class="total-row">
          <td colspan="2"><strong>TOTAL BOBOT</strong></td>
          <td><strong>1.00</strong></td>
          <td><strong>100%</strong></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="step-section">
  <div class="step-header">
    <div class="step-number">2</div>
    <div class="step-title">
      <h4>Data Alternatif (Supplier)</h4>
      <p>Data mentah dari semua supplier yang akan dinilai</p>
    </div>
  </div>
  
  <div class="alternatives-table">
    <div class="table-responsive">
      <table class="table modern-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Supplier</th>
            <th>Harga/kg</th>
            <th>Volume/bulan</th>
            <th>Ketepatan</th>
            <th>Frekuensi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $i => $s)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td><span class="badge bg-primary">{{ $s->code }}</span></td>
            <td><strong>{{ $s->name }}</strong></td>
            <td>Rp {{ number_format($s->price_per_kg ?? 0, 0, ',', '.') }}</td>
            <td>{{ number_format($s->volume_per_month ?? 0, 0, ',', '.') }} kg</td>
            <td>{{ $s->on_time_percent ?? 0 }}%</td>
            <td>{{ $s->freq_per_month ?? 0 }}x</td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center">Belum ada data supplier</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="step-section">
  <div class="step-header">
    <div class="step-number">3</div>
    <div class="step-title">
      <h4>Konversi ke Nilai Utilitas (Skala 1-5)</h4>
      <p>Data mentah dikonversi menjadi nilai utilitas berdasarkan parameter kriteria</p>
    </div>
  </div>

  <div class="utility-table">
    <div class="table-responsive">
      <table class="table modern-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Supplier</th>
            <th class="bg-dark-blue">Harga</th>
            <th class="bg-blue">Volume</th>
            <th class="bg-green">Ketepatan</th>
            <th class="bg-orange">Frekuensi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($results as $i => $r)
          @php
            $supplier = $r['supplier'];
            $harga_score = $supplier->price_per_kg <= 180 ? 5 : 
                          ($supplier->price_per_kg <= 184 ? 4 : 
                          ($supplier->price_per_kg <= 189 ? 3 : 
                          ($supplier->price_per_kg <= 194 ? 2 : 1)));
            
            $volume_score = $supplier->volume_per_month >= 15000 ? 5 : 
                           ($supplier->volume_per_month >= 10000 ? 4 : 
                           ($supplier->volume_per_month >= 7000 ? 3 : 
                           ($supplier->volume_per_month >= 4000 ? 2 : 1)));
            
            $ketepatan_score = $supplier->on_time_percent == 100 ? 5 : 
                              ($supplier->on_time_percent >= 90 ? 4 : 
                              ($supplier->on_time_percent >= 75 ? 3 : 
                              ($supplier->on_time_percent >= 50 ? 2 : 1)));
            
            $frekuensi_score = $supplier->freq_per_month >= 4 ? 5 : 
                              ($supplier->freq_per_month == 3 ? 4 : 
                              ($supplier->freq_per_month == 2 ? 3 : 
                              ($supplier->freq_per_month == 1 ? 2 : 1)));
          @endphp
          <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $supplier->name }}</strong></td>
            <td class="text-center"><span class="utility-badge badge-dark-blue">{{ $harga_score }}</span></td>
            <td class="text-center"><span class="utility-badge badge-blue">{{ $volume_score }}</span></td>
            <td class="text-center"><span class="utility-badge badge-green">{{ $ketepatan_score }}</span></td>
            <td class="text-center"><span class="utility-badge badge-orange">{{ $frekuensi_score }}</span></td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center">Belum ada data perhitungan</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="step-section">
  <div class="step-header">
    <div class="step-number">4</div>
    <div class="step-title">
      <h4>Perhitungan Nilai Akhir</h4>
      <p>Nilai akhir = (Utilitas × Bobot) untuk setiap kriteria kemudian dijumlahkan</p>
    </div>
  </div>

  <div class="calculation-cards">
    @forelse($results->take(5) as $i => $r)
    @php
      $supplier = $r['supplier'];
      $harga_score = $supplier->price_per_kg <= 180 ? 5 : 
                    ($supplier->price_per_kg <= 184 ? 4 : 
                    ($supplier->price_per_kg <= 189 ? 3 : 
                    ($supplier->price_per_kg <= 194 ? 2 : 1)));
      
      $volume_score = $supplier->volume_per_month >= 15000 ? 5 : 
                     ($supplier->volume_per_month >= 10000 ? 4 : 
                     ($supplier->volume_per_month >= 7000 ? 3 : 
                     ($supplier->volume_per_month >= 4000 ? 2 : 1)));
      
      $ketepatan_score = $supplier->on_time_percent == 100 ? 5 : 
                        ($supplier->on_time_percent >= 90 ? 4 : 
                        ($supplier->on_time_percent >= 75 ? 3 : 
                        ($supplier->on_time_percent >= 50 ? 2 : 1)));
      
      $frekuensi_score = $supplier->freq_per_month >= 4 ? 5 : 
                        ($supplier->freq_per_month == 3 ? 4 : 
                        ($supplier->freq_per_month == 2 ? 3 : 
                        ($supplier->freq_per_month == 1 ? 2 : 1)));
    @endphp
    <div class="calc-card">
      <div class="calc-header">
        <span class="calc-rank">{{ $i + 1 }}</span>
        <h5>{{ $supplier->name }}</h5>
        <span class="calc-code">{{ $supplier->code }}</span>
      </div>
      <div class="calc-body">
        <div class="formula-line">
          <span class="formula-label">Harga:</span>
          <span class="formula-calc">
            {{ $harga_score }} × 0.30 = {{ number_format($harga_score * 0.30, 3) }}
          </span>
        </div>

        <div class="formula-line">
          <span class="formula-label">Volume:</span>
          <span class="formula-calc">
            {{ $volume_score }} × 0.25 = {{ number_format($volume_score * 0.25, 3) }}
          </span>
        </div>

        <div class="formula-line">
          <span class="formula-label">Ketepatan:</span>
          <span class="formula-calc">
            {{ $ketepatan_score }} × 0.25 = {{ number_format($ketepatan_score * 0.25, 3) }}
          </span>
        </div>

        <div class="formula-line">
          <span class="formula-label">Frekuensi:</span>
          <span class="formula-calc">
            {{ $frekuensi_score }} × 0.20 = {{ number_format($frekuensi_score * 0.20, 3) }}
          </span>
        </div>

        <div class="calc-total">
          <span>Total Nilai:</span>
          <strong>{{ $r['score'] }}</strong>
        </div>
      </div>
    </div>
    @empty
    <p class="text-center">Belum ada perhitungan untuk supplier.</p>
    @endforelse
  </div>

  @if($results->count() > 5)
  <div class="show-more-box">
    <p>Menampilkan 5 perhitungan teratas. Total {{ $results->count() }} supplier telah dihitung.</p>
    <a href="{{ route('spk.result') }}" class="btn-show-all">
      <i class="fas fa-list"></i> Lihat Semua Hasil
    </a>
  </div>
  @endif
</div>

<div class="conclusion-section">
  <div class="conclusion-icon">
    <i class="fas fa-trophy"></i>
  </div>
  <div class="conclusion-content">
    <h4>Kesimpulan</h4>
    <p>Berdasarkan metode SMART, supplier terbaik adalah:</p>
    @if($results->isNotEmpty())
    <div class="winner-box">
      <div class="winner-badge"><i class="fas fa-crown"></i></div>
      <div class="winner-info">
        <h3>{{ $results[0]['supplier']->name }}</h3>
        <p>{{ $results[0]['supplier']->code }} | Skor: <strong>{{ $results[0]['score'] }}</strong></p>
      </div>
    </div>
    @else
    <div class="winner-box">
      <div class="winner-info">
        <p>Belum ada supplier atau kriteria untuk periode ini.</p>
      </div>
    </div>
    @endif
    <a href="{{ route('spk.result') }}" class="btn-view-full">
      <i class="fas fa-chart-bar"></i> Lihat Ranking Lengkap & Grafik
    </a>
  </div>
</div>

<style>
.compute-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 30px;
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
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

.header-actions {
  display: flex;
  gap: 15px;
}

.btn-result {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  color: white;
  padding: 12px 20px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-result:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
  color: white;
}

.method-info-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  display: flex;
  gap: 20px;
  align-items: flex-start;
}

.method-icon {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}

.method-content {
  flex: 1;
}

.method-content h5 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.method-content p {
  color: #7f8c8d;
  margin-bottom: 15px;
}

.formula {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 10px;
  border-left: 4px solid #3498db;
}

.formula strong {
  color: #2c3e50;
  font-size: 16px;
}

.formula span {
  color: #7f8c8d;
  font-size: 14px;
  display: block;
  margin-top: 5px;
}

.step-section {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  margin-bottom: 30px;
}

.step-header {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  margin-bottom: 25px;
}

.step-number {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
  color: white;
}

.step-title h4 {
  color: #2c3e50;
  margin-bottom: 5px;
}

.step-title p {
  color: #7f8c8d;
  margin: 0;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
}

.modern-table th {
  background: #f8f9fa;
  color: #2c3e50;
  font-weight: 600;
  padding: 15px;
  text-align: left;
  border-bottom: 2px solid #e9ecef;
}

.modern-table td {
  padding: 15px;
  border-bottom: 1px solid #f1f3f5;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
}

.weight-badge {
  background: #2c3e50;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 14px;
}

.percent-bar {
  width: 100%;
  height: 24px;
  background: #f1f3f5;
  border-radius: 12px;
  overflow: hidden;
}

.percent-fill {
  height: 100%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 600;
  border-radius: 12px;
}

.total-row {
  background: #f8f9fa;
  font-weight: 700;
}

.total-row td {
  border-bottom: none;
}

.bg-primary {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%) !important;
}

.bg-dark-blue { background: #2c3e50; color: white; }
.bg-blue { background: #3498db; color: white; }
.bg-green { background: #27ae60; color: white; }
.bg-orange { background: #e67e22; color: white; }

.utility-badge {
  display: inline-block;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: white;
  font-size: 14px;
}

.badge-dark-blue { background: #2c3e50; }
.badge-blue { background: #3498db; }
.badge-green { background: #27ae60; }
.badge-orange { background: #e67e22; }

.calculation-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.calc-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
}

.calc-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.calc-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e9ecef;
}

.calc-rank {
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: white;
  font-size: 14px;
}

.calc-header h5 {
  margin: 0;
  color: #2c3e50;
  flex: 1;
}

.calc-code {
  background: #6c757d;
  color: white;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
}

.formula-line {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
  padding: 8px 0;
  border-bottom: 1px solid #e9ecef;
}

.formula-label {
  color: #2c3e50;
  font-weight: 500;
}

.formula-calc {
  color: #3498db;
  font-weight: 600;
  font-family: 'Courier New', monospace;
}

.calc-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
  padding-top: 15px;
  border-top: 2px solid #3498db;
  font-weight: 700;
  color: #2c3e50;
}

.calc-total strong {
  color: #27ae60;
  font-size: 18px;
}

.show-more-box {
  text-align: center;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 10px;
  border: 2px dashed #dee2e6;
}

.show-more-box p {
  color: #6c757d;
  margin-bottom: 15px;
}

.btn-show-all {
  background: #6c757d;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-show-all:hover {
  background: #5a6268;
  color: white;
}

.conclusion-section {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  display: flex;
  gap: 25px;
  align-items: center;
}

.conclusion-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  color: #856404;
}

.conclusion-content {
  flex: 1;
}

.conclusion-content h4 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.conclusion-content p {
  color: #7f8c8d;
  margin-bottom: 20px;
}

.winner-box {
  display: flex;
  align-items: center;
  gap: 20px;
  background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
  padding: 20px;
  border-radius: 12px;
  border: 2px solid #ffd700;
  margin-bottom: 20px;
}

.winner-badge {
  width: 60px;
  height: 60px;
  background: #ffd700;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: #856404;
}

.winner-info h3 {
  color: #2c3e50;
  margin-bottom: 5px;
}

.winner-info p {
  color: #7f8c8d;
  margin: 0;
}

.btn-view-full {
  background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
  color: white;
  padding: 12px 25px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-view-full:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .compute-header {
    flex-direction: column;
    gap: 20px;
  }
  
  .method-info-card {
    flex-direction: column;
    text-align: center;
  }
  
  .step-header {
    flex-direction: column;
    text-align: center;
  }
  
  .calculation-cards {
    grid-template-columns: 1fr;
  }
  
  .conclusion-section {
    flex-direction: column;
    text-align: center;
  }
  
  .winner-box {
    flex-direction: column;
    text-align: center;
  }
}
</style>

@endsection