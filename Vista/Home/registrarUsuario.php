<?php include_once("../estructura/header.php"); ?>
    <div class="container mt-5">
        <h1 class="text-center">Registrar Usuario</h1>
        <form id="registroForm" action="../Action/actionRegistrarUsuario.php" method="post" onsubmit="return hashPassword()">
            <div class="form-group">
                <label for="usnombre">Nombre:</label>
                <input type="text" name="usnombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="uspass">Contraseña:</label>
                <input type="password" id="uspass" name="uspass" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="usmail">Email:</label>
                <input type="email" name="usmail" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
        </form>
    </div>
<?php include_once("../estructura/footer.php"); ?>

