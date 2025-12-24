@extends('layouts.master')
@section('content')
<div class="result-header">
  <div>
    <h1 class="page-title"><i class="fas fa-trophy"></i> Hasil Perankingan Supplier</h1>
    <p class="page-subtitle">Metode SMART (Simple Multi-Attribute Rating Technique)</p>
  </div>
  <div class="export-buttons">
    <a href="{{ route('spk.export.excel') }}" class="btn-export btn-excel">
      <i class="fas fa-file-excel"></i> Export Excel
    </a>
    <a href="{{ route('spk.export.pdf') }}" class="btn-export btn-pdf">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>

<!-- Top 3 Winners -->
<div class="winners-section">
  <div class="row g-3 mb-4">
    @if(isset($results[1]))
    <div class="col-md-4">
      <div class="winner-card rank-2">
        <div class="rank-badge">
          <i class="fas fa-medal"></i>
          <span>2</span>
        </div>
        <div class="winner-content">
          <h5>{{ $results[1]['supplier']->name }}</h5>
          <p class="supplier-code">{{ $results[1]['supplier']->code }}</p>
          <div class="score-display">
            <span class="score-label">Skor Akhir</span>
            <span class="score-value">{{ $results[1]['score'] }}</span>
          </div>
          <div class="winner-stats">
            <div class="stat-item">
              <i class="fas fa-money-bill-wave"></i>
              <span>Rp {{ number_format($results[1]['supplier']->price_per_kg, 0, ',', '.') }}</span>
            </div>
            <div class="stat-item">
              <i class="fas fa-box"></i>
              <span>{{ number_format($results[1]['supplier']->volume_per_month, 0, ',', '.') }} kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if(isset($results[0]))
    <div class="col-md-4">
      <div class="winner-card rank-1">
        <div class="crown-icon">
          <i class="fas fa-crown"></i>
        </div>
        <div class="rank-badge">
          <i class="fas fa-trophy"></i>
          <span>1</span>
        </div>
        <div class="winner-content">
          <h5>{{ $results[0]['supplier']->name }}</h5>
          <p class="supplier-code">{{ $results[0]['supplier']->code }}</p>
          <div class="score-display">
            <span class="score-label">Skor Akhir</span>
            <span class="score-value">{{ $results[0]['score'] }}</span>
          </div>
          <div class="winner-stats">
            <div class="stat-item">
              <i class="fas fa-money-bill-wave"></i>
              <span>Rp {{ number_format($results[0]['supplier']->price_per_kg, 0, ',', '.') }}</span>
            </div>
            <div class="stat-item">
              <i class="fas fa-box"></i>
              <span>{{ number_format($results[0]['supplier']->volume_per_month, 0, ',', '.') }} kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if(isset($results[2]))
    <div class="col-md-4">
      <div class="winner-card rank-3">
        <div class="rank-badge">
          <i class="fas fa-medal"></i>
          <span>3</span>
        </div>
        <div class="winner-content">
          <h5>{{ $results[2]['supplier']->name }}</h5>
          <p class="supplier-code">{{ $results[2]['supplier']->code }}</p>
          <div class="score-display">
            <span class="score-label">Skor Akhir</span>
            <span class="score-value">{{ $results[2]['score'] }}</span>
          </div>
          <div class="winner-stats">
            <div class="stat-item">
              <i class="fas fa-money-bill-wave"></i>
              <span>Rp {{ number_format($results[2]['supplier']->price_per_kg, 0, ',', '.') }}</span>
            </div>
            <div class="stat-item">
              <i class="fas fa-box"></i>
              <span>{{ number_format($results[2]['supplier']->volume_per_month, 0, ',', '.') }} kg</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- Ranking Table -->
<div class="ranking-table-card">
  <div class="card-header-custom">
    <h5><i class="fas fa-list-ol"></i> Peringkat Lengkap Semua Supplier</h5>
  </div>
  <div class="table-responsive">
    <table class="table ranking-table">
      <thead>
        <tr>
          <th>Rank</th>
          <th>Kode</th>
          <th>Nama Supplier</th>
          <th>Harga/kg</th>
          <th>Volume/bln</th>
          <th>Ketepatan</th>
          <th>Frekuensi</th>
          <th>Detail Skor</th>
          <th class="text-center">Skor Akhir</th>
        </tr>
      </thead>
      <tbody>
        @foreach($results as $i => $r)
        <tr class="{{ $i < 3 ? 'top-rank' : '' }}">
          <td>
            <div class="rank-number rank-{{ $i + 1 }}">
              @if($i == 0)
                <i class="fas fa-trophy"></i>
              @elseif($i == 1 || $i == 2)
                <i class="fas fa-medal"></i>
              @else
                {{ $i + 1 }}
              @endif
            </div>
          </td>
          <td><span class="badge bg-primary">{{ $r['supplier']->code }}</span></td>
          <td><strong>{{ $r['supplier']->name }}</strong></td>
          <td>Rp {{ number_format($r['supplier']->price_per_kg, 0, ',', '.') }}</td>
          <td>{{ number_format($r['supplier']->volume_per_month, 0, ',', '.') }} kg</td>
          <td>
            <div class="progress" style="height: 20px; width: 80px;">
              <div class="progress-bar bg-success" style="width: {{ $r['supplier']->on_time_percent }}%">
                {{ $r['supplier']->on_time_percent }}%
              </div>
            </div>
          </td>
          <td>{{ $r['supplier']->freq_per_month }}x</td>
          <td>
       <div class="detail-scores">
        <span class="detail-badge">H: {{ $r['detail']['price'] ?? '0.0000' }}</span>
        <span class="detail-badge">V: {{ $r['detail']['volume'] ?? '0.0000' }}</span>
        <span class="detail-badge">K: {{ $r['detail']['on_time'] ?? '0.0000' }}</span>
        <span class="detail-badge">F: {{ $r['detail']['freq'] ?? '0.0000' }}</span>
        </div>
          </td>
          <td class="text-center">
            <span class="final-score">{{ $r['score'] }}</span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Charts Section -->
<div class="row g-3 mt-4">
  <div class="col-md-8">
    <div class="chart-card-result">
      <div class="chart-header-custom">
        <h5><i class="fas fa-chart-bar"></i> Grafik Perbandingan Skor</h5>
      </div>
      <canvas id="scoreComparisonChart"></canvas>
    </div>
  </div>
  <div class="col-md-4">
    <div class="chart-card-result">
      <div class="chart-header-custom">
        <h5><i class="fas fa-chart-pie"></i> Distribusi Top 5</h5>
      </div>
      <canvas id="top5PieChart"></canvas>
    </div>
  </div>
</div>

<style>
.result-header {
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

.export-buttons {
  display: flex;
  gap: 10px;
}

.btn-export {
  padding: 10px 20px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-excel {
  background: #28a745;
  color: white;
}

.btn-excel:hover {
  background: #218838;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
  color: white;
}

.btn-pdf {
  background: #dc3545;
  color: white;
}

.btn-pdf:hover {
  background: #c82333;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
  color: white;
}

/* Winners Section */
.winners-section {
  margin-bottom: 40px;
}

.winner-card {
  background: white;
  border-radius: 20px;
  padding: 30px 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  position: relative;
  transition: all 0.3s ease;
  text-align: center;
  overflow: hidden;
}

.winner-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 6px;
}

.rank-1 {
  transform: scale(1.05);
  z-index: 2;
}

.rank-1::before {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
}

.rank-2::before {
  background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
}

.rank-3::before {
  background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
}

.winner-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.rank-1:hover {
  transform: translateY(-10px) scale(1.07);
}

.crown-icon {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 30px;
  color: #2b6cb0;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50% { transform: translateX(-50%) translateY(-10px); }
}

.rank-badge {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 700;
  color: white;
  position: relative;
}

.rank-1 .rank-badge {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  box-shadow: 0 5px 20px rgba(43, 108, 176, 0.4);
}

.rank-2 .rank-badge {
  background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
  box-shadow: 0 5px 20px rgba(74, 85, 104, 0.4);
}

.rank-3 .rank-badge {
  background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
  box-shadow: 0 5px 20px rgba(44, 82, 130, 0.4);
}

.winner-content h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 5px;
}

.supplier-code {
  color: #4a5568;
  font-size: 13px;
  margin-bottom: 20px;
}

.score-display {
  background: #f7fafc;
  padding: 15px;
  border-radius: 12px;
  margin-bottom: 20px;
}

.score-label {
  display: block;
  font-size: 12px;
  color: #4a5568;
  margin-bottom: 5px;
}

.score-value {
  display: block;
  font-size: 32px;
  font-weight: 700;
  color: #2b6cb0;
}

.winner-stats {
  display: flex;
  justify-content: space-around;
  gap: 10px;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
  font-size: 13px;
  color: #4a5568;
}

.stat-item i {
  font-size: 18px;
  color: #2b6cb0;
}

/* Ranking Table */
.ranking-table-card {
  background: white;
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.card-header-custom {
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e2e8f0;
}

.card-header-custom h5 {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin: 0;
}

.ranking-table {
  margin: 0;
}

.ranking-table thead th {
  background: #f7fafc;
  color: #1a365d;
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  padding: 15px 12px;
  border: none;
  white-space: nowrap;
}

.ranking-table tbody td {
  padding: 15px 12px;
  vertical-align: middle;
  border-bottom: 1px solid #e2e8f0;
}

.ranking-table tbody tr:hover {
  background: #f7fafc;
}

.ranking-table tbody tr.top-rank {
  background: #ebf8ff;
}

.ranking-table tbody tr.top-rank:hover {
  background: #bee3f8;
}

.rank-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  background: #f7fafc;
  color: #1a365d;
}

.rank-number.rank-1 {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
}

.rank-number.rank-2 {
  background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
  color: white;
}

.rank-number.rank-3 {
  background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
  color: white;
}

.detail-scores {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
}

.detail-badge {
  background: #ebf8ff;
  color: #2b6cb0;
  padding: 3px 8px;
  border-radius: 5px;
  font-size: 11px;
  font-weight: 600;
}

.final-score {
  font-size: 20px;
  font-weight: 700;
  color: white;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  padding: 8px 15px;
  border-radius: 10px;
  display: inline-block;
}

/* Chart Cards */
.chart-card-result {
  background: white;
  border-radius: 20px;
  padding: 25px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  height: 100%;
}

.chart-header-custom {
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e2e8f0;
}

.chart-header-custom h5 {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin: 0;
}

/* Custom legend styles for pie chart */
.pie-chart-legend {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-top: 15px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  padding: 5px;
  border-radius: 5px;
  transition: background-color 0.2s;
}

.legend-item:hover {
  background-color: #f7fafc;
}

.legend-color {
  width: 15px;
  height: 15px;
  border-radius: 3px;
  flex-shrink: 0;
}

.legend-label {
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.legend-value {
  font-weight: bold;
  color: #1a365d;
}
</style>

@push('scripts')
<script>
// Data from Laravel
const results = @json($results);

// Score Comparison Chart
const scoreCtx = document.getElementById('scoreComparisonChart');
const supplierNames = results.map(r => r.supplier.name.length > 15 ? r.supplier.name.substring(0, 15) + '...' : r.supplier.name);
const scores = results.map(r => r.score);

// Create gradient
const ctx = scoreCtx.getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(43, 108, 176, 0.8)');
gradient.addColorStop(1, 'rgba(26, 54, 93, 0.8)');

new Chart(scoreCtx, {
  type: 'bar',
  data: {
    labels: supplierNames,
    datasets: [{
      label: 'Skor SMART',
      data: scores,
      backgroundColor: gradient,
      borderColor: 'rgba(43, 108, 176, 1)',
      borderWidth: 2,
      borderRadius: 10,
      barThickness: 30
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        cornerRadius: 8,
        titleFont: {
          size: 14,
          weight: 'bold'
        },
        bodyFont: {
          size: 13
        },
        callbacks: {
          title: function(context) {
            return results[context[0].dataIndex].supplier.name;
          },
          label: function(context) {
            return 'Skor: ' + context.parsed.y;
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        max: 5,
        grid: {
          color: 'rgba(0, 0, 0, 0.05)',
          drawBorder: false
        },
        ticks: {
          font: {
            size: 12
          }
        }
      },
      x: {
        grid: {
          display: false,
          drawBorder: false
        },
        ticks: {
          font: {
            size: 11
          },
          maxRotation: 45,
          minRotation: 45
        }
      }
    }
  }
});

// Top 5 Pie Chart - PERBAIKAN DI SINI
const top5Ctx = document.getElementById('top5PieChart');
const top5Data = results.slice(0, 5);
const top5Names = top5Data.map(r => r.supplier.name);
const top5Scores = top5Data.map(r => r.score);

// Warna untuk pie chart
const pieColors = [
  'rgba(43, 108, 176, 0.8)',
  'rgba(74, 85, 104, 0.8)',
  'rgba(44, 82, 130, 0.8)',
  'rgba(26, 54, 93, 0.8)',
  'rgba(66, 153, 225, 0.8)'
];

const pieChart = new Chart(top5Ctx, {
  type: 'doughnut',
  data: {
    labels: top5Names,
    datasets: [{
      data: top5Scores,
      backgroundColor: pieColors,
      borderColor: '#fff',
      borderWidth: 3,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        position: 'right',
        labels: {
          padding: 15,
          font: {
            size: 11
          },
          usePointStyle: true,
          pointStyle: 'circle',
          // Fungsi untuk menampilkan label lengkap
          generateLabels: function(chart) {
            const data = chart.data;
            if (data.labels.length && data.datasets.length) {
              return data.labels.map((label, i) => {
                const value = data.datasets[0].data[i];
                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                const percentage = ((value / total) * 100).toFixed(1);
                
                return {
                  text: label + ' (' + percentage + '%)',
                  fillStyle: data.datasets[0].backgroundColor[i],
                  strokeStyle: data.datasets[0].borderColor,
                  lineWidth: data.datasets[0].borderWidth,
                  pointStyle: data.datasets[0].pointStyle,
                  hidden: isNaN(data.datasets[0].data[i]) || chart.getDatasetMeta(0).data[i].hidden,
                  index: i
                };
              });
            }
            return [];
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        cornerRadius: 8,
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.parsed;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = ((value / total) * 100).toFixed(1);
            return label + ': ' + value + ' (' + percentage + '%)';
          }
        }
      }
    },
    // Menambahkan layout padding untuk memberi ruang pada label yang panjang
    layout: {
      padding: {
        left: 10,
        right: 10,
        top: 10,
        bottom: 10
      }
    }
  }
});

// Fungsi untuk membuat custom legend jika diperlukan
function createCustomLegend(chart, containerId) {
  const legendContainer = document.getElementById(containerId);
  if (!legendContainer) return;
  
  legendContainer.innerHTML = '';
  
  chart.data.labels.forEach((label, i) => {
    const value = chart.data.datasets[0].data[i];
    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
    const percentage = ((value / total) * 100).toFixed(1);
    
    const legendItem = document.createElement('div');
    legendItem.className = 'legend-item';
    
    const colorBox = document.createElement('div');
    colorBox.className = 'legend-color';
    colorBox.style.backgroundColor = chart.data.datasets[0].backgroundColor[i];
    
    const labelText = document.createElement('span');
    labelText.className = 'legend-label';
    labelText.textContent = label;
    
    const valueText = document.createElement('span');
    valueText.className = 'legend-value';
    valueText.textContent = value + ' (' + percentage + '%)';
    
    legendItem.appendChild(colorBox);
    legendItem.appendChild(labelText);
    legendItem.appendChild(valueText);
    
    legendContainer.appendChild(legendItem);
  });
}

// Animate numbers on scroll
const animateValue = (element, start, end, duration) => {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    element.textContent = (progress * (end - start) + start).toFixed(4);
    if (progress < 1) {
      window.requestAnimationFrame(step);
    }
  };
  window.requestAnimationFrame(step);
};

// Animate score values on page load
document.addEventListener('DOMContentLoaded', () => {
  const scoreValues = document.querySelectorAll('.score-value');
  scoreValues.forEach(el => {
    const finalScore = parseFloat(el.textContent);
    el.textContent = '0.0000';
    setTimeout(() => {
      animateValue(el, 0, finalScore, 1500);
    }, 300);
  });
});
</script>
@endpush
@endsection