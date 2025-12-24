<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perankingan SPK</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
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
                <th>Harga/kg</th>
                <th>Volume/bln</th>
                <th>Ketepatan</th>
                <th>Frekuensi</th>
                @foreach($criteriaList as $c)
                    <th>{{ $c->code }}</th>
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
                <td>Rp {{ number_format($res['supplier']->price_per_kg, 0) }}</td>
                <td>{{ number_format($res['supplier']->volume_per_month, 0) }} kg</td>
                <td>{{ $res['supplier']->on_time_percent }}%</td>
                <td>{{ $res['supplier']->freq_per_month }}x</td>
                @foreach($criteriaList as $c)
                    <td>{{ $res['detail'][$c->code]['score'] ?? '-' }}</td>
                @endforeach
                <td>{{ $res['score'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
