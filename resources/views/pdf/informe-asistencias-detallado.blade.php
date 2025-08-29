<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Asistencias</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
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
    <h1>Informe de Asistencias</h1>
    <table>
        <thead>
            <tr>
                <th>ID Asistencia</th>
                <th>Usuario</th>
                <th>Tipo Asistencia</th>
                <th>Monto (CLP)</th>
                <th>Fecha</th>
                <th>Asunto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
                <tr>
                    <td>{{ $asistencia->asistencia_id }}</td>
                    <td>{{ $asistencia->usuario }}</td>
                    <td>{{ ucfirst($asistencia->tipo_asistencia) }}</td>
                    <td>{{ number_format($asistencia->monto_clp, 0, ',', '.') }}</td>
                    <td>{{ $asistencia->fecha }}</td>
                    <td>{{ $asistencia->asunto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>