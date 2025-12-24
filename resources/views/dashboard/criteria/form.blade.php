@extends('layouts.master')
@section('content')
    <h1 class="page-title">
      <i class="fas {{ $criteria ? 'fa-edit' : 'fa-plus-circle' }}"></i> 
      {{ $criteria ? 'Edit Kriteria' : 'Tambah Kriteria Baru' }}
    </h1>
    <p class="page-subtitle">{{ $criteria ? 'Perbarui kriteria penilaian' : 'Tambahkan kriteria penilaian baru' }}</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 mx-auto">
    <div class="form-card">
      <form method="post" action="{{ $criteria ? route('kriteria.update', $criteria) : route('kriteria.store') }}" id="criteriaForm">
        @csrf
        @if($criteria) @method('PUT') @endif

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Kriteria</h5>
          
          <div class="form-group-modern">
            <label class="form-label-modern">
              <i class="fas fa-tag"></i> Nama Kriteria
              <span class="required">*</span>
            </label>
            <input type="text" name="name" 
                   value="{{ old('name', $criteria->name ?? '') }}" 
                   class="form-control-modern @error('name') is-invalid @enderror" 
                   placeholder="Contoh: Harga per kg"
                   required>
            <small class="form-hint">
              <i class="fas fa-info-circle"></i> Nama kriteria yang akan dinilai (misal: Harga, Volume, Ketepatan Waktu)
            </small>
            @error('name')
              <div class="invalid-feedback-modern">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group-modern">
            <label class="form-label-modern">
              <i class="fas fa-balance-scale"></i> Bobot Kriteria
              <span class="required">*</span>
            </label>
            <div class="weight-input-wrapper">
              <input type="number" name="weight" 
                     value="{{ old('weight', $criteria->weight ?? '') }}" 
                     class="form-control-modern @error('weight') is-invalid @enderror" 
                     placeholder="0.30"
                     step="0.01"
                     min="0"
                     max="1"
                     id="weightInput"
                     required>
              <div class="weight-preview">
                <span id="weightPercent">0</span>%
              </div>
            </div>
            <div class="weight-slider-container">
              <input type="range" id="weightSlider" min="0" max="100" value="0" class="weight-slider">
              <div class="slider-labels">
                <span>0%</span>
                <span>25%</span>
                <span>50%</span>
                <span>75%</span>
                <span>100%</span>
              </div>
            </div>
            <small class="form-hint">
              <i class="fas fa-info-circle"></i> Bobot dalam desimal (0.00 - 1.00). Total semua kriteria harus = 1.00
            </small>
            @error('weight')
              <div class="invalid-feedback-modern">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group-modern">
            <label class="form-label-modern">
              <i class="fas fa-chart-line"></i> Tipe Kriteria
              <span class="required">*</span>
            </label>
            <div class="type-selector">
              <label class="type-option">
                <input type="radio" name="type" value="benefit" 
                       {{ old('type', $criteria->type ?? 'benefit') == 'benefit' ? 'checked' : '' }} required>
                <div class="type-card">
                  <i class="fas fa-arrow-up"></i>
                  <span>Benefit</span>
                  <small>Semakin besar semakin baik</small>
                </div>
              </label>
              <label class="type-option">
                <input type="radio" name="type" value="cost" 
                       {{ old('type', $criteria->type ?? '') == 'cost' ? 'checked' : '' }} required>
                <div class="type-card">
                  <i class="fas fa-arrow-down"></i>
                  <span>Cost</span>
                  <small>Semakin kecil semakin baik</small>
                </div>
              </label>
            </div>
            <small class="form-hint">
              <i class="fas fa-info-circle"></i> Benefit: Volume, Ketepatan. Cost: Harga, Waktu
            </small>
          </div>
        </div>

        @if($criteria)
        <div class="info-box-param">
          <i class="fas fa-info-circle"></i>
          <div>
            <strong>Parameter Penilaian</strong>
            <p>Setelah menyimpan kriteria, Anda dapat menambahkan parameter penilaian pada halaman khusus parameter.</p>
            <a href="{{ route('parameter.index', $criteria) }}" class="btn-param-link">
              <i class="fas fa-sliders-h"></i> Kelola Parameter
            </a>
          </div>
        </div>
        @endif

        <div class="form-actions">
          <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> 
            {{ $criteria ? 'Update Kriteria' : 'Simpan Kriteria' }}
          </button>
          <a href="{{ route('kriteria.index') }}" class="btn-cancel">
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
  gap: 20px;
  margin-bottom: 30px;
}

.btn-back {
  padding: 10px 20px;
  background: white;
  color: #2b6cb0;
  border: 2px solid #2b6cb0;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-back:hover {
  background: #2b6cb0;
  color: white;
  transform: translateX(-5px);
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

.weight-input-wrapper {
  display: flex;
  gap: 15px;
  align-items: center;
}

.weight-input-wrapper .form-control-modern {
  flex: 1;
}

.weight-preview {
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  color: white;
  padding: 12px 25px;
  border-radius: 10px;
  font-size: 18px;
  font-weight: 700;
  min-width: 80px;
  text-align: center;
}

.weight-slider-container {
  margin-top: 15px;
  padding: 20px;
  background: #f7fafc;
  border-radius: 10px;
}

.weight-slider {
  width: 100%;
  height: 8px;
  border-radius: 10px;
  outline: none;
  -webkit-appearance: none;
  background: linear-gradient(to right, #2b6cb0 0%, #2b6cb0 0%, #e2e8f0 0%, #e2e8f0 100%);
}

.weight-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  cursor: pointer;
  box-shadow: 0 3px 10px rgba(43, 108, 176, 0.4);
  transition: all 0.3s ease;
}

.weight-slider::-webkit-slider-thumb:hover {
  transform: scale(1.2);
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.6);
}

.weight-slider::-moz-range-thumb {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
  cursor: pointer;
  border: none;
  box-shadow: 0 3px 10px rgba(43, 108, 176, 0.4);
}

.slider-labels {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
  font-size: 12px;
  color: #4a5568;
  font-weight: 600;
}

.type-selector {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.type-option {
  cursor: pointer;
  margin: 0;
}

.type-option input[type="radio"] {
  display: none;
}

.type-card {
  padding: 20px;
  border: 2px solid #e2e8f0;
  border-radius: 15px;
  text-align: center;
  transition: all 0.3s ease;
  background: white;
}

.type-card i {
  font-size: 32px;
  color: #cbd5e0;
  margin-bottom: 10px;
  display: block;
}

.type-card span {
  display: block;
  font-weight: 700;
  font-size: 16px;
  color: #1a365d;
  margin-bottom: 5px;
}

.type-card small {
  display: block;
  color: #4a5568;
  font-size: 12px;
}

.type-option input:checked + .type-card {
  border-color: #2b6cb0;
  background: linear-gradient(135deg, rgba(43, 108, 176, 0.1) 0%, rgba(26, 54, 93, 0.1) 100%);
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(43, 108, 176, 0.2);
}

.type-option input:checked + .type-card i {
  color: #2b6cb0;
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

.info-box-param {
  background: linear-gradient(135deg, #e6fffa 0%, #b2f5ea 100%);
  padding: 20px;
  border-radius: 15px;
  border-left: 4px solid #2c7a7b;
  display: flex;
  gap: 15px;
  align-items: flex-start;
  margin-bottom: 30px;
}

.info-box-param i {
  font-size: 24px;
  color: #2c7a7b;
  margin-top: 3px;
}

.info-box-param strong {
  display: block;
  color: #1a365d;
  margin-bottom: 5px;
  font-size: 16px;
}

.info-box-param p {
  color: #2d3748;
  margin: 0 0 10px 0;
  font-size: 14px;
}

.btn-param-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: #2c7a7b;
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
}

.btn-param-link:hover {
  background: #234e52;
  transform: translateY(-2px);
  box-shadow: 0 3px 10px rgba(44, 122, 123, 0.3);
  color: white;
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
  
  .weight-input-wrapper {
    flex-direction: column;
  }
  
  .weight-preview {
    width: 100%;
  }
  
  .type-selector {
    grid-template-columns: 1fr;
  }
}
</style>

@push('scripts')
<script>
const weightInput = document.getElementById('weightInput');
const weightSlider = document.getElementById('weightSlider');
const weightPercent = document.getElementById('weightPercent');

if (weightInput.value) {
  const initialValue = parseFloat(weightInput.value) * 100;
  weightSlider.value = initialValue;
  weightPercent.textContent = initialValue.toFixed(0);
  updateSliderBackground(initialValue);
}

weightInput.addEventListener('input', function() {
  const value = parseFloat(this.value) || 0;
  const percent = value * 100;
  weightSlider.value = percent;
  weightPercent.textContent = percent.toFixed(0);
  updateSliderBackground(percent);
});

weightSlider.addEventListener('input', function() {
  const value = parseFloat(this.value) / 100;
  weightInput.value = value.toFixed(2);
  weightPercent.textContent = this.value;
  updateSliderBackground(this.value);
});

function updateSliderBackground(value) {
  weightSlider.style.background = `linear-gradient(to right, #2b6cb0 0%, #2b6cb0 ${value}%, #e2e8f0 ${value}%, #e2e8f0 100%)`;
}

document.getElementById('criteriaForm').addEventListener('submit', function(e) {
  const weight = parseFloat(weightInput.value);
  
  if (weight < 0 || weight > 1) {
    e.preventDefault();
    alert('Bobot harus antara 0.00 dan 1.00');
    weightInput.focus();
    return false;
  }
  
  if (!weightInput.value.trim()) {
    e.preventDefault();
    alert('Mohon isi bobot kriteria');
    weightInput.focus();
    return false;
  }
});
</script>
@endpush
@endsection