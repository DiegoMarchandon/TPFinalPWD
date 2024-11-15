<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');
?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Mis Productos</h1>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" id="carritoTable">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Detalle</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- aca se insertaran los productos del carrito -->
                </tbody>
            </table>
            <p class="text-center" id="emptyMessage">No hay productos en el carrito.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        let carritoTable = document.getElementById('carritoTable').getElementsByTagName('tbody')[0];
        let emptyMessage = document.getElementById('emptyMessage');

        if (carrito.length > 0) {
            emptyMessage.style.display = 'none';
            carrito.forEach(function(producto) {
                let row = carritoTable.insertRow();
                row.insertCell(0).innerHTML = `<img src="${producto.prodIMG}" alt="${producto.prodNombre}" width="50">`;
                row.insertCell(1).innerText = producto.prodNombre;
                row.insertCell(2).innerText = producto.prodDetalle;
                row.insertCell(3).innerText = `$${producto.prodPrecio}.00`;
                row.insertCell(4).innerText = producto.prodCantSelec;
            });
        } else {
            carritoTable.style.display = 'none';
        }
    });
</script>
</body>
</html>

<?php
include_once('../estructura/footer.php');
?>