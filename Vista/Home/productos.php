<?php
include_once '../../configuracion.php';
// poner variable $_SESSION para verificar si hay un usuario logueado
// darle funcionalidad al boton de agregar carrito y usar $_SESSION de referencia
// 
include_once('../estructura/headerSeguro.php');

/* if (isset($_SESSION['userConectadoRol']) && $_SESSION['userConectadoRol'] == 'cliente') { #si existe la clave userConectadoRol, es porque hay un usuario que inició sesión
    include_once('../estructura/headerSeguro.php');
}else{
    include_once('../estructura/header.php');
} */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
</head>
<body>
<div class="container mt-4">
        <h1 class="text-center mb-4">Nuestros productos</h1>
        <div class="row mb-5" id="prodContainer">
            <!-- estructura de ejemplo: -->
            <!-- 
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product 3">
                    <div class="card-body">
                        <h5 class="card-title">Producto 3</h5>
                        <p class="card-text">acá iría una breve descripción del producto 3.</p>
                        <p class="text-success">$39.99</p>
                        <a href="#" class="btn btn-primary">Agregar al carrito</a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

<script>
    $(document).ready(function(){

        // coleccion de imagenes de notebooks
        var colIMGS = [
            '../imagenes/notebookIMG1.jpg',
            '../imagenes/notebookIMG2.jpg',
            '../imagenes/notebookIMG3.jpg',
            '../imagenes/notebookIMG4.jpg',
            '../imagenes/notebookIMG5.jpg'
        ];

        $.ajax({
            // ruta que procesará la solicitud del servidor.
            url: '../Action/buscarProductos.php', // Ruta al script en Action
            // tipo de solicitud HTTP. GET implicará que los datos se enviarán en la URL
            method: 'GET',

            success: function(data){
                var productos = JSON.parse(data);
                console.log(productos);
            
                // Limpiar anteriores
                $('#prodContainer').empty();
            
                /* función js para mostrar  */
                function itemStock(cantStock){
                    let opciones = '';
                    for(var i = 0; i < cantStock; i++){
                        opciones += `<option value="${i}">${i}</option>`;
                    }
                    return opciones;
                }

                // Mostrar nuevas sugerencias
                productos.forEach(function(producto, index){

                    // Determinar la imagen cíclica usando el operador de módulo
                    var imgSrc = colIMGS[index % colIMGS.length]; // Rotará a través de las imágenes


                    $('#prodContainer').append(`
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="${imgSrc}" class="card-img-top" alt="Product 1">
                            <div class="card-body">
                                <h5 class="card-title">`+producto.pronombre+`</h5>
                                <p class="card-text">`+producto.prodetalle+`</p>
                                <p class="text-success">precio (unidad): $`+producto.precioprod+`.00</p>
                                <div class="form-group">
                                    <div class=" d-flex justify-content-between">
                                        <label for="cantidadSelect">Cantidad:</label>
                                        <small class="text-muted">Stock disponible: ` + producto.procantstock + ` unidades</small>
                                    </div>
                                    <select class="form-select" class="cantidadSelect">
                                        ` + itemStock(producto.procantstock) + `
                                    </select>
                                </div>
                                
                                

                                <a href="#" class="btn btn-primary m-2">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>`
                    );
                })
            }




        });

    });
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>