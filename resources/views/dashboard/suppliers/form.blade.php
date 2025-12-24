@extends('layouts.master')
@section('content')
<div class="form-page-header">
  <div>
    <h1 class="page-title">
      <i class="fas {{ $supplier ? 'fa-edit' : 'fa-plus-circle' }}"></i> 
      {{ $supplier ? 'Edit Supplier' : 'Tambah Supplier Baru' }}
    </h1>
    <p class="page-subtitle">{{ $supplier ? 'Perbarui data supplier' : 'Tambahkan supplier baru ke sistem' }}</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 mx-auto">
    <div class="form-card">
      <form method="post" action="{{ $supplier ? route('supplier.update', $supplier) : route('supplier.store') }}" id="supplierForm">
        @csrf
        @if($supplier) @method('PUT') @endif

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
          
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-barcode"></i> Kode Supplier
                  <span class="required">*</span>
                </label>
                <input type="text" name="code" 
                       value="{{ old('code', $supplier->code ?? '') }}" 
                       class="form-control-modern @error('code') is-invalid @enderror" 
                       placeholder="Contoh: SUP001"
                       required>
                @error('code')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-building"></i> Nama Supplier
                  <span class="required">*</span>
                </label>
                <input type="text" name="name" 
                       value="{{ old('name', $supplier->name ?? '') }}" 
                       class="form-control-modern @error('name') is-invalid @enderror" 
                       placeholder="Contoh: CV Putra Muara"
                       required>
                @error('name')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-map-marker-alt"></i> Lokasi
                </label>
                <input type="text" name="location" 
                       value="{{ old('location', $supplier->location ?? '') }}" 
                       class="form-control-modern" 
                       placeholder="Contoh: Muara, Riau">
              </div>
            </div>
          </div>
        </div>

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-chart-line"></i> Data Penilaian</h5>
          
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-money-bill-wave"></i> Harga per kg (Rp)
                  <span class="required">*</span>
                </label>
                <div class="input-group-modern">
                  <span class="input-prefix">Rp</span>
                  <input type="number" name="price_per_kg" 
                         value="{{ old('price_per_kg', $supplier->price_per_kg ?? '') }}" 
                         class="form-control-modern @error('price_per_kg') is-invalid @enderror" 
                         placeholder="185000"
                         required>
                </div>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Rentang normal: Rp 170.000 - Rp 205.000
                </small>
                @error('price_per_kg')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-box"></i> Volume per bulan (kg)
                  <span class="required">*</span>
                </label>
                <div class="input-group-modern">
                  <input type="number" name="volume_per_month" 
                         value="{{ old('volume_per_month', $supplier->volume_per_month ?? '') }}" 
                         class="form-control-modern @error('volume_per_month') is-invalid @enderror" 
                         placeholder="10000"
                         required>
                  <span class="input-suffix">kg</span>
                </div>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Minimal 3.000 kg per bulan
                </small>
                @error('volume_per_month')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-clock"></i> Ketepatan Waktu (%)
                  <span class="required">*</span>
                </label>
                <div class="input-group-modern">
                  <input type="number" name="on_time_percent" 
                         value="{{ old('on_time_percent', $supplier->on_time_percent ?? '') }}" 
                         class="form-control-modern @error('on_time_percent') is-invalid @enderror" 
                         placeholder="95"
                         min="0" max="100"
                         required>
                  <span class="input-suffix">%</span>
                </div>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Persentase pengiriman tepat waktu (0-100%)
                </small>
                @error('on_time_percent')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-calendar-check"></i> Frekuensi per bulan
                  <span class="required">*</span>
                </label>
                <div class="input-group-modern">
                  <input type="number" name="freq_per_month" 
                         value="{{ old('freq_per_month', $supplier->freq_per_month ?? '') }}" 
                         class="form-control-modern @error('freq_per_month') is-invalid @enderror" 
                         placeholder="3"
                         min="0"
                         required>
                  <span class="input-suffix">kali</span>
                </div>
                <small class="form-hint">
                  <i class="fas fa-info-circle"></i> Berapa kali pengiriman dalam sebulan
                </small>
                @error('freq_per_month')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> 
            {{ $supplier ? 'Update Data' : 'Simpan Data' }}
          </button>
          <a href="{{ route('supplier.index') }}" class="btn-cancel">
            <i class="fas fa-times"></i> Batal
          </a>
        </div>
      </form>
    </div>

    <!-- Info Card -->
    <div class="info-card mt-3">
      <div class="info-card-header">
        <i class="fas fa-lightbulb"></i> Tips Pengisian
      </div>
      <div class="info-card-body">
        <ul>
          <li><strong>Kode Supplier:</strong> Gunakan format SUP001, SUP002, dst. Kode harus unik.</li>
          <li><strong>Harga:</strong> Harga yang lebih rendah akan mendapat nilai lebih tinggi dalam perhitungan.</li>
          <li><strong>Volume:</strong> Volume yang lebih besar menunjukkan kapasitas pasokan yang lebih baik.</li>
          <li><strong>Ketepatan:</strong> Persentase pengiriman yang tepat waktu (100% = selalu tepat waktu).</li>
          <li><strong>Frekuensi:</strong> Jumlah pengiriman per bulan, semakin sering semakin baik.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<style>
.form-page-header {
  display: flex;
  align-items: center;
  margin-bottom: 30px;
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

.form-card {
  background: white;
  border-radius: 20px;
  padding: 35px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
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

.form-group-modern {
  margin-bottom: 20px;
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

.input-group-modern {
  display: flex;
  align-items: center;
  border: 2px solid #cbd5e0;
  border-radius: 10px;
  background: #f7fafc;
  overflow: hidden;
  transition: all 0.3s ease;
}

.input-group-modern:focus-within {
  border-color: #2b6cb0;
  background: white;
  box-shadow: 0 0 0 4px rgba(43, 108, 176, 0.1);
}

.input-prefix, .input-suffix {
  padding: 12px 15px;
  background: #ebf8ff;
  color: #2b6cb0;
  font-weight: 600;
  font-size: 14px;
}

.input-group-modern .form-control-modern {
  border: none;
  background: transparent;
  box-shadow: none;
}

.input-group-modern .form-control-modern:focus {
  box-shadow: none;
}

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

.form-actions {
  display: flex;
  gap: 15px;
  padding-top: 30px;
  border-top: 2px solid #e2e8f0;
  margin-top: 30px;
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

.info-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.info-card-header {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  padding: 15px 25px;
  font-weight: 700;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.info-card-body {
  padding: 25px;
}

.info-card-body ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.info-card-body li {
  padding: 10px 0;
  border-bottom: 1px solid #e2e8f0;
  color: #4a5568;
  font-size: 14px;
  line-height: 1.6;
}

.info-card-body li:last-child {
  border-bottom: none;
}

.info-card-body strong {
  color: #1a365d;
}

@media (max-width: 768px) {
  .form-page-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .form-card {
    padding: 25px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-submit, .btn-cancel {
    width: 100%;
    justify-content: center;
  }
}
</style>

@push('scripts')
<script>
document.getElementById('supplierForm').addEventListener('submit', function(e) {
  const requiredFields = this.querySelectorAll('[required]');
  let isValid = true;
  
  requiredFields.forEach(field => {
    if (!field.value.trim()) {
      isValid = false;
      field.classList.add('is-invalid');
    } else {
      field.classList.remove('is-invalid');
    }
  });
  
  if (!isValid) {
    e.preventDefault();
    alert('Mohon lengkapi semua field yang wajib diisi (*)');
  }
});

document.querySelectorAll('input[type="number"]').forEach(input => {
  input.addEventListener('input', function(e) {
    this.classList.remove('is-invalid');
  });
});

document.querySelectorAll('.form-control-modern').forEach(input => {
  input.addEventListener('focus', function() {
    this.parentElement.style.transform = 'scale(1.01)';
  });
  
  input.addEventListener('blur', function() {
    this.parentElement.style.transform = 'scale(1)';
  });
});
</script>
@endpush
@endsection