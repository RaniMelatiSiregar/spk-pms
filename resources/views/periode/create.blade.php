@extends('layouts.master')
@section('content')
<div class="form-page-header">
    <div class="header-content">
        <div class="header-text">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i> Tambah Periode Baru
            </h1>
            <p class="page-subtitle">Buat periode penilaian supplier bulanan</p>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('periode.generateNextMonth') }}" class="btn-generate">
            <i class="fas fa-calendar-plus"></i> Generate Bulan Depan
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="form-card">
            <form method="post" action="{{ route('periode.store') }}" id="periodeForm">
                @csrf

                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Periode Bulanan</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-tag"></i> Kode Periode
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="code" 
                                        value="{{ old('code', $suggestions['code'] ?? '') }}" 
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
                                        value="{{ old('name', $suggestions['name'] ?? '') }}" 
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
                                        value="{{ old('start_date', $suggestions['start_date'] ?? '') }}" 
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
                                        value="{{ old('end_date', $suggestions['end_date'] ?? '') }}" 
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
                                            placeholder="Deskripsi singkat tentang periode penilaian bulanan ini...">{{ old('description') }}</textarea>
                                <small class="form-hint">
                                    <i class="fas fa-info-circle"></i> Deskripsi opsional untuk periode penilaian
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Pilih Supplier dengan Input Ulang -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-users"></i> Pilih & Input Data Supplier</h5>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Perhatian:</strong> Data supplier akan disalin dari periode sebelumnya. 
                        Mohon input ulang data kriteria untuk periode ini karena harga dan parameter lainnya mungkin berubah.
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-check-square"></i> Pilih Supplier dari Periode Sebelumnya
                                    <span class="required">*</span>
                                </label>
                                
                                <div class="supplier-checkbox-header mb-2">
                                    <button type="button" class="btn-select-all btn-sm" id="toggle_all_suppliers">
                                        <i class="fas fa-check-double"></i> Pilih Semua
                                    </button>
                                    <span class="supplier-count" id="selected_count">0 supplier dipilih</span>
                                </div>
                                
                                <div class="supplier-checkbox-group" style="max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; background: #f8fafc;">
                                    @if($allSuppliers->count() > 0)
                                        @foreach($allSuppliers as $supplier)
                                        <div class="supplier-checkbox-item" data-supplier-id="{{ $supplier->id }}">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input supplier-checkbox" type="checkbox" 
                                                    name="selected_suppliers[]" 
                                                    value="{{ $supplier->id }}"
                                                    id="supplier_{{ $supplier->id }}"
                                                    data-supplier-data='@json($supplier)'
                                                    {{ in_array($supplier->id, old('selected_suppliers', [])) ? 'checked' : '' }}
                                                    onchange="toggleSupplierInputs(this)">
                                                <label class="form-check-label" for="supplier_{{ $supplier->id }}">
                                                    <div class="supplier-info">
                                                        <div class="supplier-main">
                                                            <strong class="supplier-code">{{ $supplier->code }}</strong> 
                                                            <span class="supplier-name">{{ $supplier->name }}</span>
                                                        </div>
                                                        <div class="supplier-details">
                                                            <small class="text-muted">
                                                                <i class="fas fa-map-marker-alt"></i> {{ $supplier->location }}
                                                                @if($supplier->periode)
                                                                • <i class="fas fa-history"></i> Dari: {{ $supplier->periode->name }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div class="supplier-stats-old">
                                                            <small class="text-muted">
                                                                <strong>Data Lama:</strong>
                                                                <span>Rp {{ number_format($supplier->price_per_kg) }}/kg</span> •
                                                                <span>{{ number_format($supplier->volume_per_month) }} kg</span> •
                                                                <span>{{ $supplier->on_time_percent }}% tepat</span> •
                                                                <span>{{ $supplier->freq_per_month }}x kirim</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            
                                            <!-- Input Form untuk Supplier yang Dipilih -->
                                            <div class="supplier-inputs mt-3 mb-4 p-3 bg-light rounded border" 
                                                 id="inputs_supplier_{{ $supplier->id }}" 
                                                 style="display: none;">
                                                <h6 class="mb-3 text-primary">
                                                    <i class="fas fa-edit"></i> Input Data Baru untuk {{ $supplier->code }}
                                                </h6>
                                                
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
                                                                       name="supplier_data[{{ $supplier->id }}][price_per_kg]" 
                                                                       class="form-control form-control-sm"
                                                                       placeholder="Contoh: 180"
                                                                       min="0"
                                                                       step="1"
                                                                       value="{{ old("supplier_data.{$supplier->id}.price_per_kg", $supplier->price_per_kg) }}"
                                                                       required>
                                                            </div>
                                                            <small class="text-muted">Input harga baru per kg</small>
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
                                                                   name="supplier_data[{{ $supplier->id }}][volume_per_month]" 
                                                                   class="form-control form-control-sm"
                                                                   placeholder="Contoh: 15000"
                                                                   min="0"
                                                                   step="1"
                                                                   value="{{ old("supplier_data.{$supplier->id}.volume_per_month", $supplier->volume_per_month) }}"
                                                                   required>
                                                            <small class="text-muted">Input volume baru per bulan</small>
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
                                                                       name="supplier_data[{{ $supplier->id }}][on_time_percent]" 
                                                                       class="form-control form-control-sm"
                                                                       placeholder="Contoh: 95"
                                                                       min="0"
                                                                       max="100"
                                                                       step="0.1"
                                                                       value="{{ old("supplier_data.{$supplier->id}.on_time_percent", $supplier->on_time_percent) }}"
                                                                       required>
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                            <small class="text-muted">Persentase ketepatan pengiriman</small>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Frekuensi Pengiriman -->
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label-sm">
                                                                <i class="fas fa-shipping-fast"></i> Frekuensi (kali)
                                                                <span class="required">*</span>
                                                            </label>
                                                            <select name="supplier_data[{{ $supplier->id }}][freq_per_month]" 
                                                                    class="form-control form-control-sm"
                                                                    required>
                                                                <option value="">Pilih frekuensi</option>
                                                                @for($i = 0; $i <= 10; $i++)
                                                                <option value="{{ $i }}" 
                                                                        {{ old("supplier_data.{$supplier->id}.freq_per_month", $supplier->freq_per_month) == $i ? 'selected' : '' }}>
                                                                    {{ $i }} kali
                                                                </option>
                                                                @endfor
                                                                <option value="11" {{ old("supplier_data.{$supplier->id}.freq_per_month", $supplier->freq_per_month) == 11 ? 'selected' : '' }}>>10 kali</option>
                                                            </select>
                                                            <small class="text-muted">Frekuensi pengiriman per bulan</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Tombol Reset ke Data Lama -->
                                                <div class="mt-2">
                                                    <button type="button" class="btn-reset-data btn-sm" 
                                                            onclick="resetSupplierData({{ $supplier->id }})">
                                                        <i class="fas fa-undo"></i> Reset ke Data Lama
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada supplier dari periode sebelumnya</p>
                                            <p class="text-muted">Silakan tambah supplier terlebih dahulu</p>
                                        </div>
                                    @endif
                                </div>
                                <small class="form-hint">
                                    <i class="fas fa-info-circle"></i> Centang supplier dan input data baru untuk periode ini
                                </small>
                                @error('selected_suppliers')
                                    <div class="invalid-feedback-modern">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Periode
                    </button>
                    <a href="{{ route('periode.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="info-card mt-4">
            <div class="info-card-header">
                <i class="fas fa-lightbulb"></i> Panduan Input Data Supplier
            </div>
            <div class="info-card-body">
                <ul>
                    <li><strong>Pilih Supplier:</strong> Centang supplier yang akan dinilai pada periode ini.</li>
                    <li><strong>Input Data Baru:</strong> Setiap supplier yang dipilih akan menampilkan form input data baru.</li>
                    <li><strong>Data Wajib Diinput Ulang:</strong> Harga, volume, ketepatan waktu, dan frekuensi harus diinput ulang untuk setiap periode karena mungkin berubah.</li>
                    <li><strong>Reset Data:</strong> Gunakan tombol "Reset ke Data Lama" jika ingin mengembalikan ke nilai sebelumnya.</li>
                    <li><strong>Data Lama:</strong> Informasi data dari periode sebelumnya ditampilkan sebagai referensi.</li>
                    <li><strong>Validasi:</strong> Pastikan semua field diisi dengan benar sebelum menyimpan.</li>
                </ul>
            </div>
        </div>
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

.btn-generate {
    padding: 12px 20px;
    background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(56, 161, 105, 0.3);
    white-space: nowrap;
}

.btn-generate:hover {
    background: linear-gradient(135deg, #2f855a 0%, #38a169 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(56, 161, 105, 0.4);
    color: white;
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

.alert-info {
    background: #ebf8ff;
    border: 1px solid #90cdf4;
    color: #2c5282;
    border-radius: 10px;
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

/* Supplier Checkbox Styling */
.supplier-checkbox-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.btn-select-all {
    padding: 6px 15px;
    background: #2b6cb0;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-select-all:hover {
    background: #1a365d;
    transform: translateY(-2px);
}

.supplier-count {
    font-size: 12px;
    font-weight: 600;
    color: #2b6cb0;
    background: #ebf8ff;
    padding: 4px 12px;
    border-radius: 20px;
}

.supplier-checkbox-group {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
}

.supplier-checkbox-item {
    padding: 15px;
    border-bottom: 1px solid #edf2f7;
    transition: background 0.2s ease;
}

.supplier-checkbox-item:last-child {
    border-bottom: none;
}

.supplier-checkbox-item:hover {
    background: #edf2f7;
}

.supplier-checkbox-item .form-check-input {
    margin-top: 8px;
    width: 18px;
    height: 18px;
    border: 2px solid #cbd5e0;
    cursor: pointer;
}

.supplier-checkbox-item .form-check-input:checked {
    background-color: #2b6cb0;
    border-color: #2b6cb0;
}

.supplier-checkbox-item .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.2);
}

.supplier-info {
    margin-left: 10px;
}

.supplier-main {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
}

.supplier-code {
    background: #2b6cb0;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.supplier-name {
    font-weight: 600;
    color: #1a365d;
}

.supplier-details {
    margin-bottom: 5px;
}

.supplier-details small {
    display: flex;
    align-items: center;
    gap: 5px;
}

.supplier-stats-old {
    margin-top: 5px;
    padding: 5px 10px;
    background: #f7fafc;
    border-radius: 5px;
    border-left: 3px solid #cbd5e0;
}

.supplier-stats-old small {
    color: #718096;
    font-size: 11px;
}

.supplier-stats-old span {
    margin-right: 8px;
}

/* Supplier Inputs */
.supplier-inputs {
    background: #f0fff4;
    border-left: 4px solid #38a169;
    transition: all 0.3s ease;
}

.supplier-inputs .form-label-sm {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2d3748;
}

.supplier-inputs .form-control-sm {
    padding: 8px 12px;
    font-size: 14px;
}

.supplier-inputs .input-group-text {
    font-size: 14px;
    padding: 8px 12px;
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

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
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

/* Info Card */
.info-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.info-card-header {
    background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
    color: white;
    padding: 20px 25px;
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
    
    .btn-generate {
        width: 100%;
        justify-content: center;
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
    
    .col-lg-10 {
        padding: 0 15px;
    }
    
    .supplier-main {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .supplier-inputs .row .col-md-3 {
        margin-bottom: 15px;
    }
}

@media (max-width: 576px) {
    .row.g-4 {
        margin: 0 -10px;
    }
    
    .col-md-6, .col-12 {
        padding: 0 10px;
    }
    
    .supplier-checkbox-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .supplier-count {
        align-self: flex-start;
    }
    
    .btn-select-all {
        width: 100%;
        justify-content: center;
    }
    
    .supplier-inputs .row {
        margin: 0 -5px;
    }
    
    .supplier-inputs .col-md-3 {
        padding: 0 5px;
    }
}
</style>

@push('scripts')
<script>
// Data supplier dari server
const supplierData = {!! $allSuppliers->mapWithKeys(function($supplier) {
    return [
        $supplier->id => [
            'price_per_kg' => $supplier->price_per_kg,
            'volume_per_month' => $supplier->volume_per_month,
            'on_time_percent' => $supplier->on_time_percent,
            'freq_per_month' => $supplier->freq_per_month
        ]
    ];
})->toJson() !!};

// Toggle tampilan input saat checkbox diklik
function toggleSupplierInputs(checkbox) {
    const supplierId = checkbox.value;
    const inputContainer = document.getElementById(`inputs_supplier_${supplierId}`);
    
    if (checkbox.checked) {
        inputContainer.style.display = 'block';
        // Tambah animasi
        inputContainer.style.animation = 'fadeIn 0.3s ease-in-out';
    } else {
        inputContainer.style.display = 'none';
    }
    
    updateSelectedCount();
}

// Reset data ke nilai lama
function resetSupplierData(supplierId) {
    const data = supplierData[supplierId];
    
    // Reset semua input
    document.querySelector(`input[name="supplier_data[${supplierId}][price_per_kg]"]`).value = data.price_per_kg;
    document.querySelector(`input[name="supplier_data[${supplierId}][volume_per_month]"]`).value = data.volume_per_month;
    document.querySelector(`input[name="supplier_data[${supplierId}][on_time_percent]"]`).value = data.on_time_percent;
    document.querySelector(`select[name="supplier_data[${supplierId}][freq_per_month]"]`).value = data.freq_per_month;
    
    // Show success message
    showToast('Data berhasil direset ke nilai lama', 'success');
}

// Hitung supplier yang dipilih
function updateSelectedCount() {
    const selectedCheckboxes = document.querySelectorAll('.supplier-checkbox:checked');
    const selectedCount = document.getElementById('selected_count');
    const submitButton = document.querySelector('.btn-submit');
    
    if (selectedCount) {
        selectedCount.textContent = selectedCheckboxes.length + ' supplier dipilih';
        
        // Ubah warna count berdasarkan jumlah
        if (selectedCheckboxes.length === 0) {
            selectedCount.style.background = '#fed7d7';
            selectedCount.style.color = '#742a2a';
        } else if (selectedCheckboxes.length < 3) {
            selectedCount.style.background = '#feebc8';
            selectedCount.style.color = '#744210';
        } else {
            selectedCount.style.background = '#c6f6d5';
            selectedCount.style.color = '#22543d';
        }
    }
    
    // Validasi input data supplier
    validateSupplierInputs();
}

// Validasi input data supplier
function validateSupplierInputs() {
    let allValid = true;
    const selectedCheckboxes = document.querySelectorAll('.supplier-checkbox:checked');
    
    selectedCheckboxes.forEach(checkbox => {
        const supplierId = checkbox.value;
        const inputs = document.querySelectorAll(`#inputs_supplier_${supplierId} [required]`);
        
        inputs.forEach(input => {
            if (!input.value) {
                allValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
    });
    
    return allValid;
}

// Toggle semua checkbox supplier
document.getElementById('toggle_all_suppliers')?.addEventListener('click', function(e) {
    e.preventDefault();
    const checkboxes = document.querySelectorAll('.supplier-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
        cb.dispatchEvent(new Event('change'));
    });
    
    updateSelectedCount();
});

// Validasi form sebelum submit
document.getElementById('periodeForm').addEventListener('submit', function(e) {
    const startDate = new Date(this.start_date.value);
    const endDate = new Date(this.end_date.value);
    
    // Reset error styles
    this.start_date.classList.remove('is-invalid');
    this.end_date.classList.remove('is-invalid');
    
    let hasError = false;
    
    if (!this.start_date.value || !this.end_date.value) {
        alert('Tanggal mulai dan tanggal selesai harus diisi');
        hasError = true;
    }
    
    if (endDate <= startDate) {
        this.end_date.classList.add('is-invalid');
        alert('Tanggal selesai harus lebih besar dari tanggal mulai');
        hasError = true;
    }
    
    const daysDifference = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
    
    if (daysDifference > 31) {
        this.end_date.classList.add('is-invalid');
        alert('Periode penilaian maksimal 31 hari (1 bulan). Periode yang dipilih: ' + daysDifference + ' hari.');
        hasError = true;
    }
    
    // Validasi untuk supplier
    const selectedSuppliers = document.querySelectorAll('.supplier-checkbox:checked');
    if (selectedSuppliers.length === 0) {
        alert('Pilih minimal 1 supplier untuk melanjutkan');
        hasError = true;
    }
    
    // Validasi input data supplier
    if (!validateSupplierInputs()) {
        alert('Harap lengkapi semua data supplier yang dipilih');
        hasError = true;
    }
    
    if (hasError) {
        e.preventDefault();
        return false;
    }
    
    // Show loading
    const submitButton = this.querySelector('.btn-submit');
    if (submitButton) {
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitButton.disabled = true;
    }
});

// Auto-fill end date ketika start date diubah
document.querySelector('[name="start_date"]').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDateInput = document.querySelector('[name="end_date"]');
    
    if (startDate && !isNaN(startDate.getTime())) {
        // Set end date ke akhir bulan
        const endOfMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
        
        // Format date to YYYY-MM-DD
        const formattedDate = endOfMonth.toISOString().split('T')[0];
        endDateInput.value = formattedDate;
        
        // Remove any existing error classes
        this.classList.remove('is-invalid');
        endDateInput.classList.remove('is-invalid');
    }
});

// Initialize form saat load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize selected count
    updateSelectedCount();
    
    // Show inputs for already checked checkboxes (from old form data)
    document.querySelectorAll('.supplier-checkbox:checked').forEach(checkbox => {
        toggleSupplierInputs(checkbox);
    });
    
    // Initialize dates jika ada suggestion
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    
    if (startDateInput.value && !endDateInput.value) {
        const startDate = new Date(startDateInput.value);
        if (!isNaN(startDate.getTime())) {
            const endOfMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
            const formattedDate = endOfMonth.toISOString().split('T')[0];
            endDateInput.value = formattedDate;
        }
    }
    
    // Validasi real-time tanggal
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
    document.querySelectorAll('[name^="supplier_data"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Search filter untuk supplier (opsional)
    const addSearchFilter = () => {
        const supplierContainer = document.querySelector('.supplier-checkbox-group');
        const checkboxes = document.querySelectorAll('.supplier-checkbox-item');
        
        if (checkboxes.length > 10) {
            const searchBox = document.createElement('div');
            searchBox.className = 'supplier-search mb-3';
            searchBox.innerHTML = `
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control form-control-modern" 
                           placeholder="Cari supplier berdasarkan kode atau nama..." 
                           id="supplierSearch">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            supplierContainer.parentNode.insertBefore(searchBox, supplierContainer);
            
            const searchInput = document.getElementById('supplierSearch');
            const clearButton = document.getElementById('clearSearch');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                checkboxes.forEach(checkboxItem => {
                    const supplierCode = checkboxItem.querySelector('.supplier-code').textContent.toLowerCase();
                    const supplierName = checkboxItem.querySelector('.supplier-name').textContent.toLowerCase();
                    const supplierLocation = checkboxItem.querySelector('.supplier-details').textContent.toLowerCase();
                    
                    if (supplierCode.includes(searchTerm) || 
                        supplierName.includes(searchTerm) || 
                        supplierLocation.includes(searchTerm)) {
                        checkboxItem.style.display = 'block';
                    } else {
                        checkboxItem.style.display = 'none';
                    }
                });
            });
            
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                checkboxes.forEach(checkboxItem => {
                    checkboxItem.style.display = 'block';
                });
                searchInput.focus();
            });
        }
    };
    
    // Panggil fungsi search filter
    addSearchFilter();
});

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

// Tambah CSS untuk toast dan animasi
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