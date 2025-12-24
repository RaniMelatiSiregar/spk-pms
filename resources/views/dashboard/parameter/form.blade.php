@extends('layouts.master')
@section('content')
<div class="form-page-header">
  <a href="{{ route('parameter.index', $kriteria) }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Kembali
  </a>
  <div>
    <h1 class="page-title">
      <i class="fas {{ $parameter ? 'fa-edit' : 'fa-plus-circle' }}"></i> 
      {{ $parameter ? 'Edit Parameter' : 'Tambah Parameter Baru' }}
    </h1>
    <p class="page-subtitle">
      Untuk kriteria: <strong>{{ $kriteria->name }}</strong>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-lg-9 mx-auto">
    <div class="form-card">
      <form method="post" action="{{ $parameter ? route('parameter.update', [$kriteria, $parameter]) : route('parameter.store', $kriteria) }}" id="parameterForm">
        @csrf
        @if($parameter) @method('PUT') @endif

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-star"></i> Skor Utilitas</h5>
          
          <div class="score-selector">
            @for($i = 5; $i >= 1; $i--)
            <label class="score-option">
              <input type="radio" name="score" value="{{ $i }}" 
                     {{ old('score', $parameter->score ?? '') == $i ? 'checked' : '' }} required>
              <div class="score-card score-{{ $i }}">
                <div class="score-number">{{ $i }}</div>
                <div class="score-info">
                  <span class="score-name">
                    @if($i == 5) Sangat Baik
                    @elseif($i == 4) Baik
                    @elseif($i == 3) Cukup
                    @elseif($i == 2) Kurang
                    @else Buruk
                    @endif
                  </span>
                  <small>
                    @if($i == 5) Nilai terbaik
                    @elseif($i == 4) Nilai bagus
                    @elseif($i == 3) Nilai standar
                    @elseif($i == 2) Nilai rendah
                    @else Nilai terburuk
                    @endif
                  </small>
                </div>
              </div>
            </label>
            @endfor
          </div>
          @error('score')
            <div class="invalid-feedback-modern">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-calculator"></i> Kondisi/Operator</h5>
          
          <div class="form-group-modern">
            <label class="form-label-modern">
              <i class="fas fa-code"></i> Pilih Operator
              <span class="required">*</span>
            </label>
            <select name="operator" id="operatorSelect" 
                    class="form-control-modern @error('operator') is-invalid @enderror" required>
              <option value="">-- Pilih Operator --</option>
              <option value="<=" {{ old('operator', $parameter->operator ?? '') == '<=' ? 'selected' : '' }}>
                ≤ (Kurang dari sama dengan)
              </option>
              <option value=">=" {{ old('operator', $parameter->operator ?? '') == '>=' ? 'selected' : '' }}>
                ≥ (Lebih dari sama dengan)
              </option>
              <option value="<" {{ old('operator', $parameter->operator ?? '') == '<' ? 'selected' : '' }}>
                < (Kurang dari)
              </option>
              <option value=">" {{ old('operator', $parameter->operator ?? '') == '>' ? 'selected' : '' }}>
                > (Lebih dari)
              </option>
              <option value="=" {{ old('operator', $parameter->operator ?? '') == '=' ? 'selected' : '' }}>
                = (Sama dengan)
              </option>
              <option value="between" {{ old('operator', $parameter->operator ?? '') == 'between' ? 'selected' : '' }}>
                Between (Di antara)
              </option>
            </select>
            @error('operator')
              <div class="invalid-feedback-modern">{{ $message }}</div>
            @enderror
          </div>

          <div class="row" id="valueInputs">
            <div class="col-md-6" id="minValueGroup">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-arrow-down"></i> <span id="minLabel">Nilai Minimum</span>
                </label>
                <input type="number" name="min_value" id="minValue"
                       value="{{ old('min_value', $parameter->min_value ?? '') }}" 
                       class="form-control-modern" 
                       step="any"
                       placeholder="Contoh: 1000">
              </div>
            </div>
            <div class="col-md-6" id="maxValueGroup">
              <div class="form-group-modern">
                <label class="form-label-modern">
                  <i class="fas fa-arrow-up"></i> <span id="maxLabel">Nilai Maximum</span>
                </label>
                <input type="number" name="max_value" id="maxValue"
                       value="{{ old('max_value', $parameter->max_value ?? '') }}" 
                       class="form-control-modern" 
                       step="any"
                       placeholder="Contoh: 5000">
              </div>
            </div>
          </div>

          <!-- Live Preview -->
          <div class="condition-preview">
            <i class="fas fa-eye"></i>
            <span>Preview Kondisi:</span>
            <strong id="conditionText">Pilih operator terlebih dahulu</strong>
          </div>
        </div>

        <div class="form-section">
          <h5 class="section-title"><i class="fas fa-align-left"></i> Deskripsi</h5>
          
          <div class="form-group-modern">
            <label class="form-label-modern">
              <i class="fas fa-pen"></i> Deskripsi Parameter
              <span class="required">*</span>
            </label>
            <textarea name="description" 
                      class="form-control-modern @error('description') is-invalid @enderror" 
                      rows="4"
                      placeholder="Contoh: Harga sangat murah dan kompetitif"
                      required>{{ old('description', $parameter->description ?? '') }}</textarea>
            <small class="form-hint">
              <i class="fas fa-info-circle"></i> Jelaskan secara singkat tentang kondisi ini
            </small>
            @error('description')
              <div class="invalid-feedback-modern">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Examples -->
        <div class="examples-section">
          <h5 class="examples-title"><i class="fas fa-lightbulb"></i> Contoh Parameter</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="example-box">
                <h6><i class="fas fa-check-circle"></i> Contoh 1: Harga ≤ 180</h6>
                <div class="example-detail">
                  <span class="example-label">Skor:</span>
                  <span class="example-value">5</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Operator:</span>
                  <span class="example-value">≤</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Max Value:</span>
                  <span class="example-value">180</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Deskripsi:</span>
                  <span class="example-value">Harga sangat murah</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="example-box">
                <h6><i class="fas fa-check-circle"></i> Contoh 2: Volume 10000-14999</h6>
                <div class="example-detail">
                  <span class="example-label">Skor:</span>
                  <span class="example-value">4</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Operator:</span>
                  <span class="example-value">between</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Min Value:</span>
                  <span class="example-value">10000</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Max Value:</span>
                  <span class="example-value">14999</span>
                </div>
                <div class="example-detail">
                  <span class="example-label">Deskripsi:</span>
                  <span class="example-value">Volume pasokan besar</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> 
            {{ $parameter ? 'Update Parameter' : 'Simpan Parameter' }}
          </button>
          <a href="{{ route('parameter.index', $kriteria) }}" class="btn-cancel">
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

.form-section:last-of-type {
  border-bottom: none;
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

.score-selector {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 15px;
}

.score-option {
  cursor: pointer;
  margin: 0;
}

.score-option input[type="radio"] {
  display: none;
}

.score-card {
  padding: 20px;
  border: 2px solid #e2e8f0;
  border-radius: 15px;
  transition: all 0.3s ease;
  background: white;
  display: flex;
  align-items: center;
  gap: 15px;
}

.score-card:hover {
  border-color: #cbd5e0;
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.score-option input:checked + .score-card {
  border-color: #2b6cb0;
  background: linear-gradient(135deg, rgba(43, 108, 176, 0.05) 0%, rgba(26, 54, 93, 0.05) 100%);
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(43, 108, 176, 0.2);
}

.score-number {
  width: 55px;
  height: 55px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
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

.score-info {
  flex: 1;
}

.score-name {
  display: block;
  font-weight: 700;
  font-size: 16px;
  color: #1a365d;
  margin-bottom: 3px;
}

.score-info small {
  display: block;
  font-size: 12px;
  color: #4a5568;
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

textarea.form-control-modern {
  resize: vertical;
  font-family: inherit;
}

select.form-control-modern {
  cursor: pointer;
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

/* Condition Preview */
.condition-preview {
  background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
  padding: 20px;
  border-radius: 12px;
  margin-top: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 2px solid #bee3f8;
}

.condition-preview i {
  font-size: 20px;
  color: #2b6cb0;
}

.condition-preview span {
  color: #1a365d;
  font-weight: 600;
}

.condition-preview strong {
  color: #2b6cb0;
  font-size: 18px;
}

/* Examples */
.examples-section {
  background: #f7fafc;
  padding: 25px;
  border-radius: 15px;
  margin-bottom: 30px;
}

.examples-title {
  font-size: 16px;
  font-weight: 700;
  color: #1a365d;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.example-box {
  background: white;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  height: 100%;
}

.example-box h6 {
  font-size: 14px;
  font-weight: 700;
  color: #2b6cb0;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.example-detail {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e2e8f0;
  font-size: 13px;
}

.example-detail:last-child {
  border-bottom: none;
}

.example-label {
  color: #4a5568;
  font-weight: 600;
}

.example-value {
  color: #1a365d;
  font-weight: 700;
}

/* Form Actions */
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
  
  .score-selector {
    grid-template-columns: 1fr;
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
const operatorSelect = document.getElementById('operatorSelect');
const minValueGroup = document.getElementById('minValueGroup');
const maxValueGroup = document.getElementById('maxValueGroup');
const minValue = document.getElementById('minValue');
const maxValue = document.getElementById('maxValue');
const minLabel = document.getElementById('minLabel');
const maxLabel = document.getElementById('maxLabel');
const conditionText = document.getElementById('conditionText');

function updateFields() {
  const operator = operatorSelect.value;

  minValueGroup.style.display = 'block';
  maxValueGroup.style.display = 'block';
  
  switch(operator) {
    case '<=':
    case '<':
      minValueGroup.style.display = 'none';
      maxLabel.textContent = 'Nilai Maximum';
      minValue.value = '';
      break;
      
    case '>=':
    case '>':
      maxValueGroup.style.display = 'none';
      minLabel.textContent = 'Nilai Minimum';
      maxValue.value = '';
      break;
      
    case '=':
      maxValueGroup.style.display = 'none';
      minLabel.textContent = 'Nilai';
      maxValue.value = '';
      break;
      
    case 'between':
      minLabel.textContent = 'Nilai Minimum';
      maxLabel.textContent = 'Nilai Maximum';
      break;
      
    default:
      minValueGroup.style.display = 'none';
      maxValueGroup.style.display = 'none';
  }
  
  updatePreview();
}

function updatePreview() {
  const operator = operatorSelect.value;
  const min = minValue.value;
  const max = maxValue.value;
  
  if (!operator) {
    conditionText.textContent = 'Pilih operator terlebih dahulu';
    return;
  }
  
  let text = 'Nilai ';
  
  switch(operator) {
    case '<=':
      text += max ? `≤ ${max}` : '≤ [max value]';
      break;
    case '>=':
      text += min ? `≥ ${min}` : '≥ [min value]';
      break;
    case '<':
      text += max ? `< ${max}` : '< [max value]';
      break;
    case '>':
      text += min ? `> ${min}` : '> [min value]';
      break;
    case '=':
      text += min ? `= ${min}` : '= [value]';
      break;
    case 'between':
      text += (min && max) ? `antara ${min} - ${max}` : 'antara [min] - [max]';
      break;
  }
  
  conditionText.textContent = text;
}

operatorSelect.addEventListener('change', updateFields);
minValue.addEventListener('input', updatePreview);
maxValue.addEventListener('input', updatePreview);

updateFields();

document.getElementById('parameterForm').addEventListener('submit', function(e) {
  const operator = operatorSelect.value;
  const min = parseFloat(minValue.value);
  const max = parseFloat(maxValue.value);
  
  if (operator === 'between') {
    if (!min || !max) {
      e.preventDefault();
      alert('Untuk operator "between", harap isi nilai minimum dan maximum');
      return false;
    }
    if (min >= max) {
      e.preventDefault();
      alert('Nilai minimum harus lebih kecil dari nilai maximum');
      return false;
    }
  }
});
</script>
@endpush
@endsection