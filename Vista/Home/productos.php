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
$ABMCompraitm = new ABMCompraItem; #una vez que confirmemos que el cliente tiene un carrito
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

        /* arreglo que contendrá los elementos agregados al carrito */
        let colProductos = [];

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
                // console.log(productos);
            
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
                                        <small class="text-muted cantStock">Stock disponible: ` + producto.procantstock + ` unidades</small>
                                    </div>
                                    <select class="form-select cantidadSelect">
                                        ` + itemStock(producto.procantstock) + `
                                    </select>
                                </div>
                                
                                

                                <button class="btn btn-primary m-2">Agregar al carrito</button>
                            </div>
                        </div>
                    </div>`
                    );
                })
            }

        });

        $(document).on('click','.btn-primary',function(event){
            // evito el comportamiento por defecto del boton
            event.preventDefault();

            /* extraigo de la etiqueta los datos que necesito */

            // buscamos el ancestro .card, y desde .card buscamos la imagen y extraemos el atributo 
            var card = $(this).closest('.card');
            var productoIMG = card.find('.card-img-top').attr('src');
            console.log("productoIMG: "+productoIMG);
            // busco el hermano de $(this) con la clase card-title. De la colección de 1 elemento retornada, accedo al primero, y extraigo el texto.
            let productoNombre = $(this).siblings('.card-title').first().text();
            console.log("productoNombre: "+productoNombre);
            let productoDetalle = $(this).siblings('.card-text').first().text();
            console.log("productoDetalle: "+productoDetalle);
            let precioTexto = $(this).siblings('.text-success').first().text();
            console.log("precioTexto: "+precioTexto);
            // creo una expresión regular con el propósito de extraer el precio específico del texto del elemento 
            let productoPrecioRegex = precioTexto.match(/\$(\d+)\.00/);
            // como .match() devuelve un array si hay coincidencias (indice 0 para el string completo, indice 1 para la coincidencia), verifico que, en caso de no ser null, convierto el valor en posición 1 a entero en base 10.
            let productoPrecio = productoPrecioRegex ? parseInt(productoPrecioRegex[1], 10) : 0;
            console.log("precioNum: "+productoPrecio);

            // cantidad total de stock (sin descontar la seleccionada por el cliente)
            let productoCantStock = card.find('.cantStock').text().match(/Stock disponible:\s*(\d+)\s*unidades/);
            console.log("productoCantStock: "+productoCantStock);

            // obtenemos la cantidad seleccionada por el cliente
            let productoCantSelec = card.find('.cantidadSelect').val();
            console.log("productoCantSelec: "+productoCantSelec);

            // creo el objeto del producto que será almacenado en el carrito
            let objProducto = {

                prodNombre: productoNombre,
                prodDetalle: productoDetalle,
                prodPrecio: productoPrecio,
                prodCantStock: productoCantStock,
                prodCantSelec: productoCantSelec,
                prodIMG: productoIMG
            };

            // lo agrego a mi arreglo de productos que irán al carrito
            colProductos.push(objProducto);

            // lo convierto a formato JSON y los imprimo por consola solamente para ir visualizándolos
            let productosJson = JSON.stringify(colProductos);
            console.log("productos enviados: ",productosJson);
            // envío el arreglo JSON al servidor con AJAX
            $.ajax({
                url: 'carrito.php', //URL al script PHP que recibirá los datos
                method: 'POST', // lo hacemos a través de POST porque estamos enviando datos
                contentType: 'application/json', // indicamos que estamos enviando JSON
                data: productosJson,
                success: function(response){
                    // procesamos la respuesta del servidor
                    console.log('Datos enviados correctamente',response);
                },
                error: function() {
                    console.log('Error al enviar los productos.');
                }
            });
        });
    });
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>