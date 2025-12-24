@extends('layouts.master')
@section('content')
<div class="form-page-header">
  <div class="header-content">
    <div class="header-text">
      <h1 class="page-title">
        <i class="fas fa-edit"></i> Edit Periode
      </h1>
      <p class="page-subtitle">Perbarui informasi periode penilaian supplier</p>
    </div>
  </div>
  <div class="header-actions">
    @if(!$periode->is_active)
    <form action="{{ route('periode.setActive', $periode) }}" method="POST" style="display:inline">
      @csrf
      @method('PUT')
      <button type="submit" class="btn-activate" title="Aktifkan Periode">
        <i class="fas fa-power-off"></i> Aktifkan
      </button>
    </form>
    @else
    <div class="active-badge">
      <i class="fas fa-check-circle"></i> Sedang Aktif
    </div>
    @endif
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="form-card">
      <form method="post" action="{{ route('periode.update', $periode) }}" id="periodeForm">
        @csrf
        @method('PUT')

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Periode</h5>
          
          <div class="current-period-info mb-4">
            <div class="info-grid">
              <div class="info-item">
                <label>Status:</label>
                <span class="status-badge {{ $periode->is_active ? 'active' : 'inactive' }}">
                  {{ $periode->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                </span>
              </div>
              <div class="info-item">
                <label>Dibuat:</label>
                <span>{{ $periode->created_at->format('d M Y H:i') }}</span>
              </div>
              <div class="info-item">
                <label>Diupdate:</label>
                <span>{{ $periode->updated_at->format('d M Y H:i') }}</span>
              </div>
              <div class="info-item">
                <label>Jumlah Supplier:</label>
                <span>{{ $periode->suppliers->count() }} supplier</span>
              </div>
              <div class="info-item">
                <label>Jumlah Kriteria:</label>
                <span>{{ $periode->criterias->count() }} kriteria</span>
              </div>
            </div>
          </div>
          
          <div class="row g-4">
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-tag"></i> Kode Periode
                  <span class="required">*</span>
                </label>
                <input type="text" name="code" 
                       value="{{ old('code', $periode->code) }}" 
                       class="form-control-modern @error('code') is-invalid @enderror" 
                       placeholder="Contoh: PER202401"
                       required>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Format: PERYYYYMM (PER202401, PER202402, dst)
                </small>
                @error('code')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-font"></i> Nama Periode
                  <span class="required">*</span>
                </label>
                <input type="text" name="name" 
                       value="{{ old('name', $periode->name) }}" 
                       class="form-control-modern @error('name') is-invalid @enderror" 
                       placeholder="Contoh: Evaluasi Januari 2024"
                       required>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Nama deskriptif untuk periode bulanan
                </small>
                @error('name')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-calendar-day"></i> Tanggal Mulai
                  <span class="required">*</span>
                </label>
                <input type="date" name="start_date" 
                       value="{{ old('start_date', $periode->start_date->format('Y-m-d')) }}" 
                       class="form-control-modern @error('start_date') is-invalid @enderror" 
                       required>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Biasanya tanggal 1 setiap bulan
                </small>
                @error('start_date')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-calendar-check"></i> Tanggal Selesai
                  <span class="required">*</span>
                </label>
                <input type="date" name="end_date" 
                       value="{{ old('end_date', $periode->end_date->format('Y-m-d')) }}" 
                       class="form-control-modern @error('end_date') is-invalid @enderror" 
                       required>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Biasanya akhir bulan (tanggal 28-31)
                </small>
                @error('end_date')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-12">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-align-left"></i> Deskripsi
                </label>
                <textarea name="description" 
                          class="form-control-modern" 
                          rows="3"
                          placeholder="Deskripsi singkat tentang periode penilaian bulanan ini...">{{ old('description', $periode->description) }}</textarea>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Deskripsi opsional untuk periode penilaian
                </small>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION: Edit Data Supplier -->
        @if($periode->suppliers->count() > 0)
        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-users"></i> Edit Data Supplier</h5>
          
          <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle"></i> 
            <strong>Perhatian:</strong> Anda dapat mengubah data supplier untuk periode ini. 
            Perubahan hanya berlaku untuk periode saat ini.
          </div>
          
          <div class="row g-4">
            <div class="col-12">
              @foreach($periode->suppliers as $supplier)
              <div class="supplier-edit-item mb-4 p-3 border rounded">
                <div class="supplier-header mb-3">
                  <h6 class="mb-1">
                    <i class="fas fa-user-tag"></i> {{ $supplier->code }} - {{ $supplier->name }}
                  </h6>
                  <small class="text-muted">
                    <i class="fas fa-map-marker-alt"></i> {{ $supplier->location }}
                  </small>
                </div>
                
                <div class="row g-3">
                  <!-- Harga -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-tag"></i> Harga (Rp/kg)
                        <span class="required">*</span>
                      </label>
                      <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" 
                               name="suppliers[{{ $supplier->id }}][price_per_kg]" 
                               class="form-control form-control-sm @error("suppliers.{$supplier->id}.price_per_kg") is-invalid @enderror"
                               placeholder="Contoh: 180"
                               min="0"
                               step="1"
                               value="{{ old("suppliers.{$supplier->id}.price_per_kg", $supplier->price_per_kg) }}"
                               required>
                      </div>
                      <small class="text-muted">Harga per kg</small>
                      @error("suppliers.{$supplier->id}.price_per_kg")
                        <div class="invalid-feedback-modern">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  
                  <!-- Volume Pasokan -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-boxes"></i> Volume (kg/bulan)
                        <span class="required">*</span>
                      </label>
                      <input type="number" 
                             name="suppliers[{{ $supplier->id }}][volume_per_month]" 
                             class="form-control form-control-sm @error("suppliers.{$supplier->id}.volume_per_month") is-invalid @enderror"
                             placeholder="Contoh: 15000"
                             min="0"
                             step="1"
                             value="{{ old("suppliers.{$supplier->id}.volume_per_month", $supplier->volume_per_month) }}"
                             required>
                      <small class="text-muted">Volume pasokan per bulan</small>
                      @error("suppliers.{$supplier->id}.volume_per_month")
                        <div class="invalid-feedback-modern">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  
                  <!-- Ketepatan Waktu -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-clock"></i> Ketepatan (%)
                        <span class="required">*</span>
                      </label>
                      <div class="input-group">
                        <input type="number" 
                               name="suppliers[{{ $supplier->id }}][on_time_percent]" 
                               class="form-control form-control-sm @error("suppliers.{$supplier->id}.on_time_percent") is-invalid @enderror"
                               placeholder="Contoh: 95"
                               min="0"
                               max="100"
                               step="0.1"
                               value="{{ old("suppliers.{$supplier->id}.on_time_percent", $supplier->on_time_percent) }}"
                               required>
                        <span class="input-group-text">%</span>
                      </div>
                      <small class="text-muted">Persentase ketepatan pengiriman</small>
                      @error("suppliers.{$supplier->id}.on_time_percent")
                        <div class="invalid-feedback-modern">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  
                  <!-- Frekuensi Pengiriman -->
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-shipping-fast"></i> Frekuensi (kali)
                        <span class="required">*</span>
                      </label>
                      <select name="suppliers[{{ $supplier->id }}][freq_per_month]" 
                              class="form-control form-control-sm @error("suppliers.{$supplier->id}.freq_per_month") is-invalid @enderror"
                              required>
                        <option value="">Pilih frekuensi</option>
                        @for($i = 0; $i <= 10; $i++)
                        <option value="{{ $i }}" 
                                {{ old("suppliers.{$supplier->id}.freq_per_month", $supplier->freq_per_month) == $i ? 'selected' : '' }}>
                          {{ $i }} kali
                        </option>
                        @endfor
                        <option value="11" {{ old("suppliers.{$supplier->id}.freq_per_month", $supplier->freq_per_month) == 11 ? 'selected' : '' }}>>10 kali</option>
                      </select>
                      <small class="text-muted">Frekuensi pengiriman per bulan</small>
                      @error("suppliers.{$supplier->id}.freq_per_month")
                        <div class="invalid-feedback-modern">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                
                <!-- Tombol Reset ke Nilai Semula -->
                <div class="mt-3">
                  <button type="button" class="btn-reset-data btn-sm" 
                          onclick="resetSupplierData('{{ $supplier->id }}', {{ $supplier->toJson() }})">
                    <i class="fas fa-undo"></i> Reset ke Nilai Semula
                  </button>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <!-- SECTION: Edit Kriteria (Opsional) -->
        @if($periode->criterias->count() > 0 && !$periode->is_active)
        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-list-check"></i> Edit Kriteria Penilaian</h5>
          
          <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Perhatian:</strong> Perubahan kriteria akan mempengaruhi hasil penilaian. 
            Hanya dapat diubah jika periode tidak aktif.
          </div>
          
          <div class="row g-4">
            <div class="col-12">
              @foreach($periode->criterias as $criteria)
              <div class="criteria-edit-item mb-4 p-3 border rounded">
                <div class="criteria-header mb-3">
                  <h6 class="mb-1">
                    <i class="fas fa-filter"></i> {{ $criteria->code }} - {{ $criteria->name }}
                  </h6>
                  <div class="criteria-meta">
                    <span class="badge {{ $criteria->type == 'Benefit' ? 'bg-success' : 'bg-danger' }}">
                      {{ $criteria->type }}
                    </span>
                    <span class="badge bg-info">
                      Bobot: {{ $criteria->percentage }}%
                    </span>
                  </div>
                </div>
                
                <!-- Edit Bobot Kriteria -->
                <div class="row mb-3">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-weight"></i> Bobot Kriteria (%)
                      </label>
                      <div class="input-group">
                        <input type="number" 
                               name="criterias[{{ $criteria->id }}][percentage]" 
                               class="form-control form-control-sm"
                               min="0"
                               max="100"
                               step="1"
                               value="{{ old("criterias.{$criteria->id}.percentage", $criteria->percentage) }}">
                        <span class="input-group-text">%</span>
                      </div>
                      <small class="text-muted">Persentase bobot kriteria</small>
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label-sm">
                        <i class="fas fa-exchange-alt"></i> Jenis Kriteria
                      </label>
                      <select name="criterias[{{ $criteria->id }}][type]" 
                              class="form-control form-control-sm">
                        <option value="Benefit" {{ old("criterias.{$criteria->id}.type", $criteria->type) == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                        <option value="Cost" {{ old("criterias.{$criteria->id}.type", $criteria->type) == 'Cost' ? 'selected' : '' }}>Cost</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <!-- Parameter Kriteria -->
                <div class="parameters-section">
                  <h6 class="mb-2 text-muted">Parameter Penilaian:</h6>
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                      <thead>
                        <tr>
                          <th width="10%">Skor</th>
                          <th width="30%">Deskripsi</th>
                          <th width="15%">Operator</th>
                          <th width="15%">Min Value</th>
                          <th width="15%">Max Value</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($criteria->parameters->sortByDesc('score') as $param)
                        <tr>
                          <td>
                            <input type="number" 
                                   name="parameters[{{ $param->id }}][score]" 
                                   class="form-control form-control-sm"
                                   min="1"
                                   max="5"
                                   value="{{ old("parameters.{$param->id}.score", $param->score) }}"
                                   readonly>
                          </td>
                          <td>
                            <input type="text" 
                                   name="parameters[{{ $param->id }}][description]" 
                                   class="form-control form-control-sm"
                                   value="{{ old("parameters.{$param->id}.description", $param->description) }}">
                          </td>
                          <td>
                            <select name="parameters[{{ $param->id }}][operator]" 
                                    class="form-control form-control-sm">
                              <option value="lte" {{ old("parameters.{$param->id}.operator", $param->operator) == 'lte' ? 'selected' : '' }}>≤ (lte)</option>
                              <option value="gte" {{ old("parameters.{$param->id}.operator", $param->operator) == 'gte' ? 'selected' : '' }}>≥ (gte)</option>
                              <option value="equal" {{ old("parameters.{$param->id}.operator", $param->operator) == 'equal' ? 'selected' : '' }}>= (equal)</option>
                              <option value="between" {{ old("parameters.{$param->id}.operator", $param->operator) == 'between' ? 'selected' : '' }}>between</option>
                            </select>
                          </td>
                          <td>
                            <input type="number" 
                                   name="parameters[{{ $param->id }}][min_value]" 
                                   class="form-control form-control-sm"
                                   step="any"
                                   value="{{ old("parameters.{$param->id}.min_value", $param->min_value) }}">
                          </td>
                          <td>
                            <input type="number" 
                                   name="parameters[{{ $param->id }}][max_value]" 
                                   class="form-control form-control-sm"
                                   step="any"
                                   value="{{ old("parameters.{$param->id}.max_value", $param->max_value) }}">
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <div class="form-actions">
          <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> Update Periode
          </button>
          <a href="{{ route('periode.index') }}" class="btn-cancel">
            <i class="fas fa-times"></i> Batal
          </a>
          @if(!$periode->is_active)
          <button type="button" class="btn-delete" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Hapus
          </button>
          @endif
        </div>
      </form>

      <!-- Delete Form -->
      @if(!$periode->is_active)
      <form id="deleteForm" action="{{ route('periode.destroy', $periode) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>
      @endif
    </div>

    <!-- Stats Card -->
    <div class="stats-card mt-4">
      <div class="stats-card-header">
        <i class="fas fa-chart-bar"></i> Statistik Periode
      </div>
      <div class="stats-card-body">
        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
              <h3>{{ $periode->suppliers->count() }}</h3>
              <p>Total Supplier</p>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">
              <i class="fas fa-list-check"></i>
            </div>
            <div class="stat-content">
              <h3>{{ $periode->criterias->count() }}</h3>
              <p>Total Kriteria</p>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
              <h3>{{ $periode->start_date->diffInDays($periode->end_date) + 1 }}</h3>
              <p>Durasi (Hari)</p>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">
              <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-content">
              <h3>{{ $periode->criterias->sum('percentage') }}%</h3>
              <p>Total Bobot</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Warning Card -->
    @if($periode->is_active)
    <div class="warning-card mt-4">
      <div class="warning-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="warning-content">
        <h5>Periode Sedang Aktif</h5>
        <p>Periode ini sedang aktif. Hanya informasi dasar periode yang dapat diubah. 
          Data supplier dan kriteria tidak dapat diubah pada periode aktif.</p>
      </div>
    </div>
    @elseif($periode->suppliers->count() == 0)
    <div class="warning-card mt-4">
      <div class="warning-icon">
        <i class="fas fa-exclamation-circle"></i>
      </div>
      <div class="warning-content">
        <h5>Tidak Ada Supplier</h5>
        <p>Periode ini tidak memiliki supplier. Anda dapat menambah supplier melalui menu Supplier Management.</p>
      </div>
    </div>
    @endif
  </div>
</div>

<style>
.form-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 20px;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.header-text {
  flex: 1;
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

.btn-activate {
  padding: 10px 20px;
  background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(56, 161, 105, 0.3);
}

.btn-activate:hover {
  background: linear-gradient(135deg, #2f855a 0%, #38a169 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(56, 161, 105, 0.4);
}

.active-badge {
  padding: 10px 20px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 10px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

/* Current Period Info */
.current-period-info {
  background: #f7fafc;
  padding: 20px;
  border-radius: 10px;
  border: 2px solid #e2e8f0;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.info-item label {
  font-size: 12px;
  font-weight: 600;
  color: #4a5568;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-item span {
  font-weight: 600;
  color: #1a365d;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: inline-block;
  width: fit-content;
}

.status-badge.active {
  background: #c6f6d5;
  color: #22543d;
}

.status-badge.inactive {
  background: #fed7d7;
  color: #742a2a;
}

/* Form Card */
.form-card {
  background: white;
  border-radius: 20px;
  padding: 35px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  margin-bottom: 25px;
}

.form-section {
  margin-bottom: 35px;
  padding-bottom: 30px;
  border-bottom: 2px solid #e2e8f0;
}

.form-section:last-of-type {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.section-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.section-title i {
  color: #2b6cb0;
}

/* Alerts */
.alert {
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 20px;
}

.alert-info {
  background: #ebf8ff;
  border: 1px solid #90cdf4;
  color: #2c5282;
}

.alert-warning {
  background: #fffaf0;
  border: 1px solid #f6ad55;
  color: #744210;
}

/* Form Controls */
.form-group-modern {
  margin-bottom: 25px;
}

.form-label-modern {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: #1a365d;
  margin-bottom: 10px;
  font-size: 14px;
}

.form-label-modern i {
  color: #2b6cb0;
  font-size: 16px;
}

.required {
  color: #e53e3e;
  font-weight: 700;
}

.form-control-modern {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #cbd5e0;
  border-radius: 10px;
  font-size: 15px;
  transition: all 0.3s ease;
  background: #f7fafc;
}

.form-control-modern:focus {
  outline: none;
  border-color: #2b6cb0;
  background: white;
  box-shadow: 0 0 0 4px rgba(43, 108, 176, 0.1);
}

.form-control-modern.is-invalid {
  border-color: #e53e3e;
}

textarea.form-control-modern {
  resize: vertical;
  font-family: inherit;
  min-height: 100px;
}

/* Small Form Controls */
.form-control-sm {
  padding: 8px 12px;
  font-size: 14px;
  border-radius: 6px;
}

.form-label-sm {
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 5px;
  color: #2d3748;
}

/* Form Hints */
.form-hint {
  display: block;
  margin-top: 8px;
  color: #718096;
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.form-hint i {
  color: #2b6cb0;
}

.invalid-feedback-modern {
  display: block;
  margin-top: 8px;
  color: #e53e3e;
  font-size: 13px;
  font-weight: 600;
}

/* Supplier Edit Item */
.supplier-edit-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.supplier-edit-item:hover {
  background: #edf2f7;
  border-color: #cbd5e0;
}

.supplier-header {
  padding-bottom: 10px;
  border-bottom: 1px solid #e2e8f0;
}

.supplier-header h6 {
  color: #1a365d;
  font-weight: 600;
}

.btn-reset-data {
  padding: 4px 12px;
  background: #e2e8f0;
  color: #4a5568;
  border: 1px solid #cbd5e0;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-reset-data:hover {
  background: #cbd5e0;
}

/* Criteria Edit Item */
.criteria-edit-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
}

.criteria-meta {
  display: flex;
  gap: 8px;
  margin-top: 5px;
}

.criteria-meta .badge {
  font-size: 11px;
  padding: 4px 8px;
}

/* Parameters Table */
.parameters-section table th {
  background: #f1f5f9;
  font-size: 12px;
  font-weight: 600;
}

.parameters-section table td {
  padding: 8px;
}

.parameters-section .form-control-sm {
  height: 32px;
  font-size: 13px;
}

/* Form Actions */
.form-actions {
  display: flex;
  gap: 15px;
  padding-top: 30px;
  border-top: 2px solid #e2e8f0;
  margin-top: 30px;
  flex-wrap: wrap;
}

.btn-submit {
  padding: 14px 35px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(43, 108, 176, 0.4);
}

.btn-cancel {
  padding: 14px 35px;
  background: white;
  color: #4a5568;
  border: 2px solid #cbd5e0;
  border-radius: 10px;
  font-weight: 600;
  font-size: 16px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.btn-cancel:hover {
  background: #4a5568;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(74, 85, 104, 0.3);
}

.btn-delete {
  padding: 14px 35px;
  background: #e53e3e;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.btn-delete:hover {
  background: #c53030;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(229, 62, 62, 0.3);
}

/* Stats Card */
.stats-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.stats-card-header {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  padding: 20px 25px;
  font-weight: 700;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.stats-card-body {
  padding: 25px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: #f7fafc;
  border-radius: 10px;
  transition: transform 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  background: #2b6cb0;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: white;
}

.stat-content h3 {
  font-size: 24px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 5px;
}

.stat-content p {
  color: #4a5568;
  margin: 0;
  font-size: 13px;
}

/* Warning Card */
.warning-card {
  background: #fffaf0;
  border: 2px solid #fed7d7;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  gap: 15px;
  align-items: flex-start;
}

.warning-icon {
  width: 40px;
  height: 40px;
  background: #dd6b20;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: white;
  flex-shrink: 0;
}

.warning-content h5 {
  font-size: 16px;
  font-weight: 700;
  color: #744210;
  margin-bottom: 5px;
}

.warning-content p {
  color: #744210;
  margin: 0;
  font-size: 14px;
  line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
  .form-page-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .header-content {
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
  }
  
  .header-actions {
    width: 100%;
  }
  
  .btn-activate, .active-badge {
    width: 100%;
    justify-content: center;
  }
  
  .form-card {
    padding: 25px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-submit, .btn-cancel, .btn-delete {
    width: 100%;
    justify-content: center;
  }
  
  .col-lg-10 {
    padding: 0 15px;
  }
  
  .info-grid {
    grid-template-columns: 1fr 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr 1fr;
  }
  
  .supplier-edit-item .row .col-md-3 {
    margin-bottom: 15px;
  }
  
  .parameters-section table {
    display: block;
    overflow-x: auto;
  }
}

@media (max-width: 576px) {
  .row.g-4 {
    margin: 0 -10px;
  }
  
  .col-md-6, .col-12 {
    padding: 0 10px;
  }
  
  .warning-card {
    flex-direction: column;
    text-align: center;
  }
  
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .supplier-edit-item .row {
    margin: 0 -5px;
  }
  
  .supplier-edit-item .col-md-3 {
    padding: 0 5px;
  }
}
</style>

@push('scripts')
<script>
// Reset supplier data ke nilai semula
function resetSupplierData(supplierId, originalData) {
    document.querySelector(`input[name="suppliers[${supplierId}][price_per_kg]"]`).value = originalData.price_per_kg;
    document.querySelector(`input[name="suppliers[${supplierId}][volume_per_month]"]`).value = originalData.volume_per_month;
    document.querySelector(`input[name="suppliers[${supplierId}][on_time_percent]"]`).value = originalData.on_time_percent;
    document.querySelector(`select[name="suppliers[${supplierId}][freq_per_month]"]`).value = originalData.freq_per_month;
    
    // Show success message
    showToast('Data supplier berhasil direset ke nilai semula', 'success');
}

// Date validation untuk periode bulanan
document.getElementById('periodeForm').addEventListener('submit', function(e) {
  const startDate = new Date(this.start_date.value);
  const endDate = new Date(this.end_date.value);
  const daysDifference = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
  
  if (endDate <= startDate) {
    e.preventDefault();
    alert('Tanggal selesai harus lebih besar dari tanggal mulai');
    this.end_date.focus();
    return false;
  }
  
  if (daysDifference > 31) {
    e.preventDefault();
    alert('Periode penilaian maksimal 31 hari (1 bulan). Periode yang dipilih: ' + daysDifference + ' hari.');
    this.end_date.focus();
    return false;
  }
  
  // Validasi input supplier jika ada
  const supplierInputs = document.querySelectorAll('input[name^="suppliers["], select[name^="suppliers["]');
  let hasEmptySupplierFields = false;
  
  supplierInputs.forEach(input => {
    if (input.required && !input.value) {
      input.classList.add('is-invalid');
      hasEmptySupplierFields = true;
    } else {
      input.classList.remove('is-invalid');
    }
  });
  
  if (hasEmptySupplierFields) {
    e.preventDefault();
    alert('Harap lengkapi semua data supplier yang wajib diisi');
    return false;
  }
  
  // Validasi total bobot kriteria jika diubah
  const criteriaInputs = document.querySelectorAll('input[name^="criterias["]');
  let totalPercentage = 0;
  
  criteriaInputs.forEach(input => {
    if (input.name.includes('[percentage]') && input.value) {
      totalPercentage += parseFloat(input.value) || 0;
    }
  });
  
  if (totalPercentage > 0 && Math.abs(totalPercentage - 100) > 0.01) {
    e.preventDefault();
    alert(`Total bobot kriteria harus 100%. Saat ini total: ${totalPercentage.toFixed(2)}%`);
    return false;
  }
});

// Auto-fill end date ketika start date diubah
document.querySelector('[name="start_date"]').addEventListener('change', function() {
  const startDate = new Date(this.value);
  const endDateInput = document.querySelector('[name="end_date"]');
  
  if (startDate && !endDateInput.value) {
    // Set end date ke akhir bulan
    const endOfMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
    endDateInput.valueAsDate = endOfMonth;
  }
});

// Validasi real-time
document.querySelectorAll('input[type="date"]').forEach(input => {
  input.addEventListener('change', function() {
    const startDate = new Date(document.querySelector('[name="start_date"]').value);
    const endDate = new Date(document.querySelector('[name="end_date"]').value);
    
    if (startDate && endDate) {
      const daysDifference = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
      
      if (endDate <= startDate) {
        this.classList.add('is-invalid');
      } else if (daysDifference > 31) {
        this.classList.add('is-invalid');
      } else {
        this.classList.remove('is-invalid');
      }
    }
  });
});

// Real-time validation untuk input supplier
document.querySelectorAll('input[name^="suppliers["], select[name^="suppliers["]').forEach(input => {
  input.addEventListener('input', function() {
    if (this.value) {
      this.classList.remove('is-invalid');
    }
  });
});

// Confirm delete
function confirmDelete() {
  if (confirm('Apakah Anda yakin ingin menghapus periode ini? Semua data supplier dan kriteria yang terkait juga akan dihapus!')) {
    document.getElementById('deleteForm').submit();
  }
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// Tambah CSS untuk toast
const style = document.createElement('style');
style.textContent = `
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    z-index: 9999;
    opacity: 0;
    transform: translateX(100px);
    transition: all 0.3s ease;
}

.toast-notification.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-success {
    background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
}

.toast-info {
    background: linear-gradient(135deg, #2b6cb0 0%, #4299e1 100%);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-content i {
    font-size: 18px;
}
`;
document.head.appendChild(style);
</script>
@endpush
@endsection