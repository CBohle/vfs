<!-- CONTENIDO DEL DASHBOARD: CONTENIDO INICIAL -->
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php'; // conexión con variable $conexion

$mensajes_pendientes = 0;
$postulaciones_pendientes = 0;

// Consulta real de mensajes pendientes
$stmt1 = $conexion->query("SELECT COUNT(*) AS total FROM mensajes WHERE estado = 'pendiente'");
if ($stmt1) {
    $mensajes_pendientes = $stmt1->fetch_assoc()['total'];
    $stmt1->free();
}

// Consulta real de postulaciones pendientes
$stmt2 = $conexion->query("SELECT COUNT(*) AS total FROM curriculum WHERE estado = 'pendiente'");
if ($stmt2) {
    $postulaciones_pendientes = $stmt2->fetch_assoc()['total'];
    $stmt2->free();
}

// Consulta de total de clientes registrados
$sql_clientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resultado_clientes = mysqli_query($conexion, $sql_clientes);
$fila_clientes = mysqli_fetch_assoc($resultado_clientes);
$total_clientes = $fila_clientes['total_clientes'];

// Cálculo tiempo de respuesta mensajes
$sql_promedio = "SELECT AVG(TIMESTAMPDIFF(MINUTE, m.fecha_creacion, r.fecha_respuesta)) AS promedio_minutos FROM mensajes m INNER JOIN respuestas r ON r.mensaje_id = m.id WHERE r.fecha_respuesta IS NOT NULL";

$resultado = mysqli_query($conexion, $sql_promedio);
$fila = mysqli_fetch_assoc($resultado);
$promedio_minutos = round($fila['promedio_minutos'] ?? 0);

// Convertir a formato legible
$promedio_formateado = $promedio_minutos > 60
    ? round($promedio_minutos / 60, 1) . ' horas'
    : $promedio_minutos . ' minutos';
?>


<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-size: 1rem;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        a.marcarImportante {
            cursor: pointer;
            text-decoration: none;
        }

        div.dataTables_filter {
            display: none;
        }

        .col-mensaje {
            width: 25%;
            white-space: normal;
        }

        .truncado-3-lineas {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            /* ← esta línea soluciona la advertencia */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            line-height: 1.2em;
            max-height: 3.6em;
            text-align: justify;
        }

        .navbar {
            font-size: 1.0rem;
        }

        #contenido-dinamico {
            width: 100%;
            max-width: 1700px;
            /* Limita el ancho en desktop */
            padding: 1rem;
            margin-top: 3px;
            margin-bottom: 3px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid px-3 py-2">
        <div id="contenido-dinamico" class="mx-auto w-100" style="max-width: 100%;">
            <!-- Contenido     -->
            <!-- Encabezado     -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="mb-0">Dashboard</h2>
            </div>
            <!-- Pendientes y datos -->
            <div style="margin-bottom: 40px;">
                <!-- Pendientes -->
                <div class="secc-title row">
                    <h5>Pendientes</h5>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-warning-subtle h-100" id="verMensajes" style="cursor: pointer;">
                            <div class="card-header">Mensajes</div>
                            <div class="card-body">
                                📬 Tienes <strong><?= $mensajes_pendientes ?></strong> mensajes <strong>pendientes</strong> por revisar.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-danger-subtle h-100" id="verPostulaciones" style="cursor: pointer;">
                            <div class="card-header">Postulaciones</div>
                            <div class="card-body">
                                📄 Hay <strong><?= $postulaciones_pendientes ?></strong> <strong>postulaciones nuevas</strong> sin procesar.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Datos -->
                <div class="secc-title row">
                    <h5>Datos</h5>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-secondary-subtle h-100">
                            <div class="card-header">Tiempo de respuesta</div>
                            <div class="card-body">
                                ⏱️ El tiempo <strong>promedio de respuesta</strong> es de <strong><?= $promedio_formateado ?></strong>.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success-subtle h-100">
                            <div class="card-header">Clientes registrados</div>
                            <div class="card-body">
                                👥 Hay <strong><?= $total_clientes ?></strong> <strong>clientes</strong> registrados en la base de datos.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin contenido -->
            <!-- Footer -->
            <?php
            require_once __DIR__ . '/includes/footerAdmin.php';
            ?>
            <!-- Fin Footer -->
            <!-- Script para mostrar el contenido dinámico de Mensajes -->
            <script>
                $(document).ready(function() {
                    $('#verMensajes').click(function() {
                        $('#contenido-dinamico').load('mensajes.php');
                    });
                });
            </script>
            <!-- Script para mostrar el contenido dinámico de Postulaciones -->
            <script>
                $(document).ready(function() {
                    $('#verPostulaciones').click(function() {
                        $('#contenido-dinamico').load('postulaciones.php');
                    });
                });
            </script>
</body>