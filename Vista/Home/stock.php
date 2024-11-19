<?php
// echo "<h1>stock</h1>";
include_once('../estructura/headerSeguro.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
</head>
<body>
    
<h1 class="text-center font-monospace">Productos</h1>

<div class="container mt-5">
    <div class="table-responsive">
        <table border="1" id="dataTable" class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Notebook</th> 
                    <th>Detalle</th>
                    <th>Precio</th>
                    <th>Stock Actual</th>
                </tr>
            </thead>
            <tbody>
                <!-- acá se van a agregar las filas -->
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function(){

    // creo la solicitud ajax que buscará los datos de la BD
    $.ajax({
    
        url: '../Action/listarStockNets.php',
        method: 'POST',
        success: function(datos){
            var colNets = datos.colProds;
            // ejemplo: Accedo al nombre de la primer net de la colección
            console.log(colNets[0]);
            // almaceno el dato en formato HTML de cada net que vaya recorriendo dentro de una variable
            let registroTabla = ''; 
            colNets.forEach(net => {
                registroTabla += 
                    `<tr>
                    <td>${net['idproducto']}</td>
                    <td>${net['pronombre']}</td>
                    <td>${net['prodetalle']}</td>
                    <td>${net['precioprod']}</td>
                    <td>
                    <div class="input-group">
                        <input type="number" class="form-control stock-input" value="${net['procantstock']}" data-idproducto="${net['idproducto']}" min="0"/>
                        <button class="btn btn-primary update-btn" type="button" disabled>Actualizar</button>
                    </div>
                    </td>
                    </tr>`;
            });
            $('#dataTable tbody').html(registroTabla);

            // Manejar el evento de cambio en el input de stock
            $('.stock-input').on('input', function() {
                // Habilitar el botón correspondiente cuando el valor del input cambie
                $(this).siblings('.update-btn').prop('disabled', false);
            });
            
            
            // asocio el evento a botones usando "delegación de eventos"
            $('#dataTable').on('click','.update-btn',function(){
                const button = $(this); //botón específico clickeado
                const input = button.siblings('.stock-input'); // Input relacionado al botón
                const idproducto = input.data('idproducto');
                const nuevoStock = input.val();
            
                //  solicitud AJAX para actualizar el stock
                $.ajax({
                    url: '../Action/actualizarStock.php', // URL del script de actualización
                    method: 'POST',
                    data: {
                        idproducto: idproducto,
                        nuevoStock: nuevoStock
                    },
                    success: function(response) {
                        alert('Stock actualizado con éxito!');
                        button.prop('disabled', true); // Deshabilitar el botón después de actualizar
                        if(response.status === 'success'){
                            window.location.href = response.redirect;
                        }
                    },
                    error: function() {
                        alert('Error al actualizar el stock.');
                    }
                });
            })


        },
        error: function() {
            console.log('Error al recibir los datos.');
        }
    
    });

});
</script>
</body>
</html>