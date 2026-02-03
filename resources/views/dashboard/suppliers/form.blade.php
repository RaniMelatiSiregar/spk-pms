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
          <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Dasar Supplier</h5>
          
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
                       class="form-control-modern @error('location') is-invalid @enderror" 
                       placeholder="Contoh: Muara, Riau">
                @error('location')
                  <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- INFO BOX -->
        <div class="form-section">
          <div class="alert alert-info-custom">
            <div class="alert-icon">
              <i class="fas fa-info-circle"></i>
            </div>
            <div class="alert-content">
              <h6><i class="fas fa-calendar-alt"></i> Informasi Periode & Penilaian</h6>
              <p class="mb-2">Data penilaian (harga, volume, ketepatan, frekuensi) akan diisi saat membuat periode baru.</p>
              <div class="alert-steps">
                <div class="step">
                  <span class="step-number">1</span>
                  <span class="step-text">Simpan data supplier ini terlebih dahulu</span>
                </div>
                <div class="step">
                  <span class="step-number">2</span>
                  <span class="step-text">Buka menu <a href="{{ route('periode.index') }}" class="alert-link">Periode</a> → Buat periode baru</span>
                </div>
                <div class="step">
                  <span class="step-number">3</span>
                  <span class="step-text">Pilih supplier ini dan isi data penilaiannya</span>
                </div>
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

.invalid-feedback-modern {
  display: block;
  margin-top: 8px;
  color: #e53e3e;
  font-size: 13px;
  font-weight: 600;
}

.alert-info-custom {
  background: linear-gradient(135deg, #ebf8ff 0%, #e6fffa 100%);
  border: 2px solid #bee3f8;
  border-left: 5px solid #2b6cb0;
  color: #2c5282;
  padding: 20px;
  border-radius: 12px;
  margin: 0;
}

.alert-info-custom .alert-icon {
  float: left;
  margin-right: 15px;
  font-size: 24px;
  color: #2b6cb0;
}

.alert-info-custom .alert-content {
  overflow: hidden;
}

.alert-info-custom h6 {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.alert-info-custom h6 i {
  color: #2b6cb0;
}

.alert-info-custom p {
  margin-bottom: 15px;
  line-height: 1.5;
}

.alert-steps {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 15px;
}

.step {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 15px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 8px;
  border-left: 3px solid #2b6cb0;
}

.step-number {
  width: 28px;
  height: 28px;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 14px;
}

.step-text {
  flex: 1;
  font-size: 14px;
  color: #2d3748;
}

.alert-link {
  color: #2b6cb0 !important;
  font-weight: 600;
  text-decoration: underline;
}

.alert-link:hover {
  color: #1a365d !important;
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
  
  .alert-info-custom .alert-icon {
    float: none;
    margin-right: 0;
    margin-bottom: 10px;
    text-align: center;
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