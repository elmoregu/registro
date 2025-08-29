<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Asistencias por Cobrar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Informe de Asistencias por Cobrar</h1>
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Total de Asistencias</th>
                <th>Total Valor (UF)</th>
                <th>Total en Pesos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($informes as $informe)
                <tr>
                    <td>{{ $informe->empresa }}</td>
                    <td>{{ $informe->total_asistencias }}</td>
                    <td>{{ number_format($informe->total_valor, 2, ',', '.') }}</td>
                    <td>{{ number_format($informe->monto_clp, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
