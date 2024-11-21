<?php include_once("../estructura/header.php"); ?>
    <div class="container mt-5">
        <h1 class="text-center">Registrar Usuario</h1>
        <form id="registroForm" action="../Action/actionRegistrarUsuario.php" method="post" onsubmit="return verificarRegistro(event)" novalidate>
            <div class="form-group">
                <label for="usnombre">Nombre:</label>
                <input type="text" id="usnombre" name="usnombre" class="form-control" required>
                <div class="invalid-feedback">
                    El nombre solo debe contener letras y no puede tener espacios.
                </div>
            </div>
            <div class="form-group">
                <label for="uspass">Contraseña:</label>
                <input type="password" id="uspass" name="uspass" class="form-control" required>
                <div class="invalid-feedback">
                    La contraseña no puede estar vacía ni contener espacios.
                </div>
            </div>
            <div class="form-group">
                <label for="usmail">Email:</label>
                <input type="email" id="usmail" name="usmail" class="form-control" required>
                <div class="invalid-feedback">
                    Por favor, ingrese un email válido.
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
        </form>
    </div>

<script>
function verificarRegistro(event) { 
    event.preventDefault(); // Evitar el envío del formulario

    const form = document.getElementById('registroForm');
    const usnombre = document.getElementById('usnombre').value.trim();
    const uspassField = document.getElementById('uspass');
    const uspass = uspassField.value.trim();
    const usmailField = document.getElementById('usmail');
    const usmail = usmailField.value.trim();

    // Validar que el nombre solo contenga letras sin espacios
    const usnombreRegex = /^[a-zA-Z]+$/;
    if (!usnombreRegex.test(usnombre)) {
        document.getElementById('usnombre').classList.add('is-invalid');
        return false;
    } else {
        document.getElementById('usnombre').classList.remove('is-invalid');
        document.getElementById('usnombre').classList.add('is-valid');
    }

    // Validar que la contraseña no esté vacía y no contenga espacios
    if (uspass === '' || /\s/.test(uspass)) {
        uspassField.classList.add('is-invalid');
        return false;
    } else {
        uspassField.classList.remove('is-invalid');
        uspassField.classList.add('is-valid');
    }

    // Validar que el email sea válido
    if (!usmailField.checkValidity()) {
        usmailField.classList.add('is-invalid');
        return false;
    } else {
        usmailField.classList.remove('is-invalid');
        usmailField.classList.add('is-valid');
    }

    form.submit(); // Enviar el formulario si todas las validaciones son correctas
}
</script>

<?php include_once("../estructura/footer.php"); ?>