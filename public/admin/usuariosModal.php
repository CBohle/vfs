<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="tituloModalUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formUsuario" method="post" action="usuariosGuardar.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalUsuario">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="usuario_id">
                <div class="mb-2">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>
                <div class="mb-2">
                    <label>Apellido</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" required>
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-2">
                    <label>Rol</label>
                    <select class="form-select" name="rol" id="rol" required>
                        <option value="admin">Admin</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" name="activo" id="activo" checked>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
