<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perankingan SPK</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }

        table { border-collapse: collapse; width: 100%; }

        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        thead { background-color: #f2f2f2; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>

<h2>Hasil Perankingan Supplier</h2>

<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Kode</th>
            <th>Nama Supplier</th>

            @foreach($criteriaList as $c)
                <th>{{ $c->name ?? $c->nama ?? $c->criteria_name ?? $c->code }}</th>
            @endforeach

            <th>Skor Akhir</th>
        </tr>
    </thead>

    <tbody>
        @foreach($results as $index => $res)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $res['supplier']->code }}</td>
            <td>{{ $res['supplier']->name }}</td>

            {{-- ✅ mapping sesuai data asli --}}
            @foreach($criteriaList as $c)
                @switch(strtolower($c->code))
                    @case('c1')
                        <td>Rp {{ number_format($res['supplier']->price_per_kg, 0, ',', '.') }}</td>
                        @break

                    @case('c2')
                        <td>{{ number_format($res['supplier']->volume_per_month, 0, ',', '.') }} kg</td>
                        @break

                    @case('c3')
                        <td>{{ $res['supplier']->on_time_percent }}%</td>
                        @break

                    @case('c4')
                        <td>{{ $res['supplier']->freq_per_month }}x</td>
                        @break

                    @default
                        <td>-</td>
                @endswitch
            @endforeach

            <td>{{ number_format($res['score'], 4) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>