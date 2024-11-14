<?php
include_once '../../configuracion.php';

$abmProducto = new ABMProducto();
$productos = $abmProducto->buscar(null);

?>

<?php include_once("../estructura/headerSeguro.php"); ?>
<div class="container mt-5">
    <h1 class="text-center">Productos</h1>
    <div class="row">
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto->getPronombre(); ?></h5>
                        <p class="card-text"><?php echo $producto->getProdetalle(); ?></p>
                        <p class="card-text">Precio: $<?php echo $producto->getPrecioprod(); ?></p>
                        <p class="card-text">Stock: <?php echo $producto->getProcantstock(); ?></p>
                        <button class="btn btn-primary agregar-carrito" data-id="<?php echo $producto->getIdproducto(); ?>">Agregar al Carrito</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include_once("../estructura/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona todos los botones con la clase 'agregarcarrito'
    var botonesAgregarCarrito = document.querySelectorAll('.agregar-carrito');

    // Recorre cada botn y agrega un evento 'click'
    botonesAgregarCarrito.forEach(function(boton) {
        boton.addEventListener('click', function() {
            // Obtiene el ID del producto del atributo 'data-id del boton
            var idProducto = this.getAttribute('data-id');

            // Crea una nueva solicitud AJAX
            var ajax = new XMLHttpRequest();
            ajax.open('POST', 'agregarCarrito.php', true); // Configura la solicitud para enviar datos al archivo 'agregarCarrito.php' usando el metodo POST
            ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Establece el tipo de contenido de la solicitud
                                                                                        //application/x-www-form-urlencoded es el tipo de contenido predeterminado para enviar formularios HTML (genera menos problemas pa mi que el json)

            // Define una funcion que se ejecutara cuando la solicitud cambie de estado
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4 && ajax.status === 200) { //el 4 es para ver si la operacion ha finalizado y el 200 para ver si la respuesta del servidor es correcta
                    // Muestra una alerta cuando el producto se ha agregado al carrito
                    alert('Producto agregado al carrito');
                }
            };

            // Envia la solicitud con los datos del producto (ID y cantidad)
            ajax.send('idproducto=' + idProducto + '&cantidad=1');
            
            //ajax.send(JSON.stringify(producto)); se puede enviar un objeto JSON pero hay que modificar un par de cositas y no me funco a la primera xD
        });
    });
});
</script>