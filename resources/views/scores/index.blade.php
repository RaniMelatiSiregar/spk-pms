@extends('layouts.master')

@section('content')
<h2>Input Nilai Supplier — {{ $supplier->name }}</h2>

<form method="POST">
    @csrf

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Input Nilai</th>
                <th>Pilih Parameter</th>
                <th>Score</th>
            </tr>
        </thead>

        <tbody>
            @foreach($criterias as $c)
            @php
                $old = $scores[$c->id] ?? null;
            @endphp
            <tr>
                <td><strong>{{ $c->name }}</strong></td>

                <td style="width:180px">
                    <input type="number" step="0.01" name="criteria[{{ $c->id }}][raw_value]"
                           value="{{ $old->raw_value ?? '' }}"
                           class="form-control" required>
                </td>

                <td style="width:250px">
                    <select name="criteria[{{ $c->id }}][parameter_id]" class="form-control" required>
                        <option value="">-- pilih --</option>
                        @foreach($c->parameters as $p)
                        <option value="{{ $p->id }}"
                            {{ isset($old) && $old->parameter_id == $p->id ? 'selected' : '' }}>
                            {{ $p->description }}
                        </option>
                        @endforeach
                    </select>
                </td>

                <td style="width:80px" class="text-center">
                    {{ $old->score ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="btn btn-primary">Simpan Nilai</button>
</form>
@endsection
