<?php
require_once __DIR__ . '/../../includes/auth.php';
// require_once __DIR__ . '/../../includes/db.php'; // ← Activa cuando uses base de datos real

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
    $id = $_POST['id'];
    $nuevoEstado = $_POST['estado'];

    // Consulta real:
    /*
    $stmt = $conn->prepare("UPDATE mensajes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevoEstado, $id);
    $stmt->execute();
    $stmt->close();
    */
    // Si se envía por AJAX no redirijas.
    exit;
}

// Simulación de mensajes (reemplazar por BD)
$mensajes = [
    ["id" => 1, "nombre" => "Juan Pérez", "email" => "juan@mail.com", "fecha" => "2025-05-03", "mensaje" => "Hola, quiero más info", "estado" => "pendiente"],
    ["id" => 2, "nombre" => "Ana López", "email" => "ana@mail.com", "fecha" => "2025-05-02", "mensaje" => "Consulta sobre servicios", "estado" => "leído"],
    ["id" => 3, "nombre" => "Carlos Rojas", "email" => "carlos@mail.com", "fecha" => "2025-05-01", "mensaje" => "Quiero agendar", "estado" => "respondido"]
];
?>

<h4 class="mb-4 text-muted">Mensajes de Contacto</h4>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Actualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $msg): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['fecha']) ?></td>
                        <td><?= htmlspecialchars($msg['nombre']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td><?= htmlspecialchars($msg['mensaje']) ?></td>
                        <td>
                            <span class="badge
                            <?= $msg['estado'] === 'respondido' ? 'bg-success' : ($msg['estado'] === 'leído' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                <?= ucfirst($msg['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex gap-2 align-items-center">
                                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                <select name="estado" class="form-select form-select-sm">
                                    <option value="pendiente" <?= $msg['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="leído" <?= $msg['estado'] === 'leído' ? 'selected' : '' ?>>Leído</option>
                                    <option value="respondido" <?= $msg['estado'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-dark">Guardar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($mensajes)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay mensajes disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<hr>
<hr>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal to add new record -->
    <div class="offcanvas offcanvas-end" id="add-new-record">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="exampleModalLabel">New Record</h5>
            <button
                type="button"
                class="btn-close text-reset"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="add-new-record pt-0 row g-2" id="form-add-new-record" onsubmit="return false">
                <div class="col-sm-12">
                    <label class="form-label" for="basicFullname">Full Name</label>
                    <div class="input-group input-group-merge">
                        <span id="basicFullname2" class="input-group-text"><i class="ti ti-user"></i></span>
                        <input
                            type="text"
                            id="basicFullname"
                            class="form-control dt-full-name"
                            name="basicFullname"
                            placeholder="John Doe"
                            aria-label="John Doe"
                            aria-describedby="basicFullname2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicPost">Post</label>
                    <div class="input-group input-group-merge">
                        <span id="basicPost2" class="input-group-text"><i class="ti ti-briefcase"></i></span>
                        <input
                            type="text"
                            id="basicPost"
                            name="basicPost"
                            class="form-control dt-post"
                            placeholder="Web Developer"
                            aria-label="Web Developer"
                            aria-describedby="basicPost2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicEmail">Email</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                        <input
                            type="text"
                            id="basicEmail"
                            name="basicEmail"
                            class="form-control dt-email"
                            placeholder="john.doe@example.com"
                            aria-label="john.doe@example.com" />
                    </div>
                    <div class="form-text">You can use letters, numbers & periods</div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicDate">Joining Date</label>
                    <div class="input-group input-group-merge">
                        <span id="basicDate2" class="input-group-text"><i class="ti ti-calendar"></i></span>
                        <input
                            type="text"
                            class="form-control dt-date"
                            id="basicDate"
                            name="basicDate"
                            aria-describedby="basicDate2"
                            placeholder="MM/DD/YYYY"
                            aria-label="MM/DD/YYYY" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label" for="basicSalary">Salary</label>
                    <div class="input-group input-group-merge">
                        <span id="basicSalary2" class="input-group-text"><i class="ti ti-currency-dollar"></i></span>
                        <input
                            type="number"
                            id="basicSalary"
                            name="basicSalary"
                            class="form-control dt-salary"
                            placeholder="12000"
                            aria-label="12000"
                            aria-describedby="basicSalary2" />
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>