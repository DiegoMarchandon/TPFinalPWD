<?php
// include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');
$ABMUsuario = new ABMUsuario;
// $session = new Session;

// $colUsers = $ABMUsuario->buscarArray(null);
// print_r($colUsers[0]['usnombre']);

// echo "<br>usuario actual:<br>";

// $userActual = $ABMUsuario->buscarArray($session->getUsuario());
/* 
print_r($userActual);

Array ( [idusuario] => 4 
    [usnombre] => ana567 
    [uspass] => 97a6d21df7c51e8289ac1a8c026aaac143e15aa1957f54f42e30d8f8a85c3a55 
    [usmail] => ana@ana.com.ar 
    [usdeshabilitado] => 0000-00-00 00:00:00 
    [mensajeoperacion] => )
*/
// echo $userActual['usnombre'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta</title>
</head>
<body>
<div class="container mt-1">
    <h2 class="text-center">Cambiar Datos de Usuario</h2>
    <form action="../Action/cambiarDatos.php" method="POST" id="modificarDatos">

        <!-- Nombre de Usuario -->

        <div class="form-floating m-3">
        <input type="text" class="form-control" name="nombreActual" id="nombreActual" placeholder="Ingrese su nombre de usuario actual">
        <label for="nombreActual" for="floatingUsername">Nombre de Usuario Actual</label>
        </div>

        <div class="form-floating m-3">
        <input type="text" class="form-control" name="nuevoNombre" id="nuevoNombre" placeholder="Ingrese su nuevo nombre de usuario">
        <label for="nuevoNombre" for="floatingUsername">Nuevo Nombre de Usuario</label>
        </div>

        <!-- Contraseña -->
        <div class=" bg-secondary p-2">
            <div class="form-floating m-3">
            <input type="text" class="form-control" name="contraseñaActual" id="contraseñaActual" placeholder="Ingrese su contraseña actual">
            <label for="contraseñaActual" for="floatingPassword">Contraseña Actual</label>
            </div>
    
            <div class="form-floating m-3">
            <input type="password" class="form-control" name="nuevaContraseña" id="nuevaContraseña" placeholder="Ingrese la nueva contraseña">
            <label for="nuevaContraseña" for="floatingPassword">Nueva Contraseña</label>
            </div>
    
            <div class="form-floating m-3">
            <input type="text" class="form-control" name="nuevaContraseñaConfirm" id="nuevaContraseñaConfirm" placeholder="verifique la nueva contraseña">
            <label for="contraNuevaConfirmada" for="floatingPassword">Confirmar Nueva Contraseña</label>
            </div>
        </div>

        <!-- Correo Electrónico -->

        <div class="form-floating m-3">
        <input type="email" class="form-control" name="emailActual" id="emailActual" placeholder="Ingrese su email actual">
        <label for="emailActual" for="floatingEmail">Email actual</label>
        </div>

        <div class="form-floating m-3">
        <input type="email" class="form-control" name="nuevoEmail" id="nuevoEmail" placeholder="Ingrese el nuevo email">
        <label for="nuevoEmail" for="floatingEmail">Nuevo Email</label>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Cambiar Datos</button>
    </form>
</div>

<script>
    $(document).ready(function(){
        
        
        /* variable que almacenará los datos obtenidos con ajax */
        var datos;
        
        $(document).on('click','.btn-primary',function(event){
            
            // console.log("hola")
            // evito el comportamiento por defecto del boton
            event.preventDefault();
            
            let actualName = $('#nombreActual').val();
            console.log("nombre actual ingresado: "+actualName);
            let newName = $('#nuevoNombre').val();
    
            let actualPass = $('#contraseñaActual').val();
            
            // hash de la contraseña actual ingresada por input (compararla con la almacenada en la variable datos)
            let passHash = CryptoJS.SHA256(actualPass).toString();
            console.log("contraseña hasheada ingresada: "+passHash);
    
            let newPass = $('#contraseñaActual').val();
            let confirmPass = $('#nuevaContraseñaConfirm').val();
    
            let actualEmail = $('#emailActual').val();
            console.log("email actual ingresado: "+actualEmail);
            
            let newEmail = $('#nuevoEmail').val();
            
            
            // creo la solicitud ajax que buscará los datos 
            $.ajax({
                // ruta que procesará la solicitud del servidor.
                url: '../Action/compararDatos.php', // Ruta al script en Action
                // tipo de solicitud HTTP.
                method: 'POST',
                // jQuery detecta que la respuesta del servidor tiene el tipo application/json en su encabezado, por lo que parsea automáticamente el JSON recibido en un objeto javascript
                success: function(data){
                    console.log(data.userActual['usnombre']);
                    datos = data;
                    // datos = JSON.parse(data);

                    if(data.userActual['usnombre'] === actualName){
                        $('#nombreActual').css('border', '2px solid green'); 
                        $('#nombreActual').next('label').text('Nombre de Usuario Actual').css('color', '');

                        console.log("nombres coincidentes");
                        
                    }else{
                        $('#nombreActual').css('border', '2px solid red'); 
                        $('#nombreActual').next('label').text('nombre de usuario incorrecto').css('color', 'red');
                        console.log("nombres no coinciden");
                    }
                    
                    if(passHash === data.userActual['uspass']){
                        $('#contraseñaActual').css('border', '4px solid green'); 
                        $('#contraseñaActual').next('label').text('Contraseña Actual').css('color', '');
                        console.log("contraseñas hasheadas coincidentes");
                    }else{
                        $('#contraseñaActual').css('border', '2px solid red'); 
                        $('#contraseñaActual').next('label').text('contraseña ingresada incorrecta').css('color', 'red');
                        console.log("contraseñas hasheadas distintas. La actual: "+data.userActual['uspass']+" la ingresada: "+passHash);
                    }
                    
                    if(actualEmail === data.userActual['usmail']){
                        $('#emailActual').css('border', '2px solid green'); 
                        $('#emailActual').next('label').text('Email Actual').css('color', '');
                        console.log("emails coincidentes");
                    }else{
                        $('#emailActual').css('border', '2px solid red'); 
                        $('#emailActual').next('label').text('email ingresado incorrecto').css('color', 'red');
                        console.log("emails distintos");
                    }

                    // si todos los datos ingresados por el usuario coinciden con los que tiene
                    if((actualEmail === data.userActual['usmail']) && (data.userActual['usnombre'] === actualName) && (passHash === data.userActual['uspass'])){
                        // y si los campos con datos nuevos no están vacíos
                        if(newName.length > 0 && newPass.length > 0 && confirmPass.length > 0 && newPass === confirmPass && newEmail.length > 0){
                            $('#modificarDatos').submit();
                        }
                    }

                },
                error: function() {
                    console.log('Error al recibir los datos.');
                }



            });

            
        });
        console.log("datos: ",datos);


    });
</script>
</body>
</html>
<?php 
include_once('../estructura/footer.php');
?>