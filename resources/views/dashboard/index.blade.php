@extends('layouts.master')
@section('content')
<div class="dashboard-header">
  <div class="header-content">
    <div class="title-section">
      <h1 class="page-title"><i class="fas fa-tachometer-alt"></i> Dashboard Overview</h1>
      <p class="page-subtitle">Sistem Pendukung Keputusan Pemilihan Supplier</p>
    </div>
    <div class="periode-section">
      <form method="GET" class="periode-form">
        <div class="form-group">
          <label class="form-label"><i class="fas fa-calendar-alt"></i> Pilih Periode</label>
          <select name="periode_id" class="periode-select" onchange="this.form.submit()">
            @foreach($periodes as $p)
              <option value="{{ $p->id }}" {{ $selectedPeriode->id == $p->id ? 'selected' : '' }}>
                {{ $p->name }} ({{ $p->start_date->format('M Y') }})
              </option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stats-card card-primary">
      <div class="stats-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="stats-content">
        <h3>{{ $summary['total_suppliers'] }}</h3>
        <p>Total Supplier</p>
      </div>
      <div class="stats-trend">
        <i class="fas fa-arrow-up"></i> 100%
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="stats-card card-info">
      <div class="stats-icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="stats-content">
        <h3>Rp {{ number_format($summary['avg_price'], 0, ',', '.') }}</h3>
        <p>Rata-rata Harga/kg</p>
      </div>
      <div class="stats-trend">
        <i class="fas fa-chart-line"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="stats-card card-success">
      <div class="stats-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="stats-content">
        <h3>{{ number_format($summary['avg_volume'], 0, ',', '.') }}</h3>
        <p>Rata-rata Volume/bulan</p>
      </div>
      <div class="stats-trend">
        <i class="fas fa-arrow-up"></i> Good
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="stats-card card-warning">
      <div class="stats-icon">
        <i class="fas fa-star"></i>
      </div>
      <div class="stats-content">
        <h3>{{ $criterias->count() }}</h3>
        <p>Kriteria Penilaian</p>
      </div>
      <div class="stats-trend">
        <i class="fas fa-check-circle"></i> Active
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row g-3 mb-4">
  <!-- Price Distribution Chart -->
  <div class="col-md-6">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fas fa-chart-bar"></i> Distribusi Harga Supplier</h5>
        <div class="chart-actions">
          <button class="btn-icon"><i class="fas fa-sync-alt"></i></button>
        </div>
      </div>
      <canvas id="priceChart"></canvas>
    </div>
  </div>

  <!-- Volume Chart -->
  <div class="col-md-6">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fas fa-chart-line"></i> Volume Pasokan per Supplier</h5>
        <div class="chart-actions">
          <button class="btn-icon"><i class="fas fa-download"></i></button>
        </div>
      </div>
      <canvas id="volumeChart"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <!-- Performance Radar -->
  <div class="col-md-6">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fas fa-chart-area"></i> Performa Top 5 Supplier</h5>
      </div>
      <canvas id="radarChart"></canvas>
    </div>
  </div>

  <!-- Criteria Weight -->
  <div class="col-md-6">
    <div class="chart-card">
      <div class="chart-header">
        <h5><i class="fas fa-chart-pie"></i> Bobot Kriteria</h5>
      </div>
      <canvas id="criteriaChart"></canvas>
    </div>
  </div>
</div>

<!-- Recent Suppliers Table -->
<div class="row g-3">
  <div class="col-12">
    <div class="table-card">
      <div class="table-header">
        <h5><i class="fas fa-list"></i> Supplier Terbaru</h5>
        <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-primary">
          Lihat Semua <i class="fas fa-arrow-right"></i>
        </a>
      </div>
      <div class="table-responsive">
        <table class="table modern-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama Supplier</th>
              <th>Harga/kg</th>
              <th>Volume/bulan</th>
              <th>Ketepatan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($suppliers->take(5) as $s)
            <tr>
              <td><span class="badge bg-primary">{{ $s->code }}</span></td>
              <td>{{ $s->name }}</td>
              <td>Rp {{ number_format($s->price_per_kg, 0, ',', '.') }}</td>
              <td>{{ number_format($s->volume_per_month, 0, ',', '.') }} kg</td>
              <td>
                <div class="progress" style="height: 20px;">
                  <div class="progress-bar" role="progressbar" style="width: {{ $s->on_time_percent }}%">
                    {{ $s->on_time_percent }}%
                  </div>
                </div>
              </td>
              <td>
                @if($s->on_time_percent >= 90)
                  <span class="status-badge status-excellent">Excellent</span>
                @elseif($s->on_time_percent >= 75)
                  <span class="status-badge status-good">Good</span>
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
  </div>
</div>

<style>
.dashboard-header {
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

.stats-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stats-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
}

.card-primary::before { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); }
.card-info::before { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); }
.card-success::before { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); }
.card-warning::before { background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); }

.stats-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  margin-bottom: 15px;
}

.card-primary .stats-icon { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); }
.card-info .stats-icon { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); }
.card-success .stats-icon { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); }
.card-warning .stats-icon { background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%); }

.stats-content h3 {
  font-size: 32px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 5px;
}

.stats-content p {
  color: #7f8c8d;
  font-size: 14px;
  margin: 0;
}

.stats-trend {
  position: absolute;
  top: 25px;
  right: 25px;
  font-size: 12px;
  color: #27ae60;
  font-weight: 600;
}

.chart-card, .table-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  height: 100%;
}

.chart-header, .table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f1f3f5;
}

.chart-header h5, .table-header h5 {
  font-size: 18px;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-icon {
  width: 35px;
  height: 35px;
  border-radius: 8px;
  border: none;
  background: #f1f3f5;
  color: #7f8c8d;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-icon:hover {
  background: #3498db;
  color: white;
}

.modern-table {
  margin: 0;
}

.modern-table thead th {
  background: #f8f9fa;
  color: #2c3e50;
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  padding: 15px;
  border: none;
}

.modern-table tbody td {
  padding: 15px;
  vertical-align: middle;
  border-bottom: 1px solid #f1f3f5;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
}

.status-badge {
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-excellent {
  background: #d4edda;
  color: #155724;
}

.status-good {
  background: #fff3cd;
  color: #856404;
}

.status-poor {
  background: #f8d7da;
  color: #721c24;
}

.progress-bar {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
}

.btn-primary {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  font-weight: 600;
  transition: all 0.3s ease;
  color: white;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
  color: white;
}

.badge.bg-primary {
  background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%) !important;
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
  
  .page-title {
    font-size: 24px;
  }
}
</style>

@push('scripts')
<script>
// Prepare data from Laravel
const suppliers = @json($suppliers);
const criterias = @json($criterias);

// Price Distribution Chart
const priceCtx = document.getElementById('priceChart');
const priceLabels = suppliers.map(s => s.name);
const priceData = suppliers.map(s => s.price_per_kg);

new Chart(priceCtx, {
  type: 'bar',
  data: {
    labels: priceLabels,
    datasets: [{
      label: 'Harga per kg (Rp)',
      data: priceData,
      backgroundColor: 'rgba(52, 152, 219, 0.8)',
      borderColor: 'rgba(52, 152, 219, 1)',
      borderWidth: 2,
      borderRadius: 8
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Volume Chart
const volumeCtx = document.getElementById('volumeChart');
const volumeData = suppliers.map(s => s.volume_per_month);

new Chart(volumeCtx, {
  type: 'line',
  data: {
    labels: suppliers.map(s => s.name),
    datasets: [{
      label: 'Volume (kg/bulan)',
      data: volumeData,
      borderColor: 'rgba(39, 174, 96, 1)',
      backgroundColor: 'rgba(39, 174, 96, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4,
      pointRadius: 5,
      pointBackgroundColor: 'rgba(39, 174, 96, 1)',
      pointBorderColor: '#fff',
      pointBorderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Radar Chart - Top 5 Suppliers Performance
const radarCtx = document.getElementById('radarChart');
const top5 = suppliers.slice(0, 5);

new Chart(radarCtx, {
  type: 'radar',
  data: {
    labels: ['Harga', 'Volume', 'Ketepatan', 'Frekuensi'],
    datasets: top5.map((s, idx) => ({
      label: s.name,
      data: [
        (200 - s.price_per_kg) / 2,
        s.volume_per_month / 200,
        s.on_time_percent,
        s.freq_per_month * 20
      ],
      borderColor: `hsl(${idx * 72}, 70%, 50%)`,
      backgroundColor: `hsla(${idx * 72}, 70%, 50%, 0.2)`,
      borderWidth: 2
    }))
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
      r: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        }
      }
    }
  }
});

// Criteria Pie Chart
const criteriaCtx = document.getElementById('criteriaChart');
const criteriaNames = criterias.map(c => c.name);
const criteriaWeights = criterias.map(c => c.weight * 100);

new Chart(criteriaCtx, {
  type: 'doughnut',
  data: {
    labels: criteriaNames,
    datasets: [{
      data: criteriaWeights,
      backgroundColor: [
        'rgba(52, 152, 219, 0.8)',
        'rgba(44, 62, 80, 0.8)',
        'rgba(39, 174, 96, 0.8)',
        'rgba(230, 126, 34, 0.8)'
      ],
      borderWidth: 3,
      borderColor: '#fff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 15,
          font: {
            size: 12
          }
        }
      }
    }
  }
});
</script>
@endpush
@endsection