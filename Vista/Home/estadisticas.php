<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadisticas</title>
</head>
<body>
    <div id="usersInfo">
        <h1>composición de usuarios</h1> 

        <div style="display: flex; align-items: center; width: 800px;">       
            <div style="position: relative; width: 400px; height: 300px;">
                <canvas id="graficoTorta"></canvas>
            </div>
            <div style="width: 200px; height: 200px; margin-left: 20px;">
                <canvas id="subgraficoTorta"></canvas>
            </div>
        </div>
    </div>
    
<script src="../js/chart.js"></script>
<script>
    $(document).ready(function () { 

        let graficoTorta = document.getElementById('graficoTorta').getContext('2d');
        let subGraficoTorta = document.getElementById('subgraficoTorta').getContext('2d');

        $.ajax({
                url: '../Action/enviarDatos.php', // Cambia esto a tu URL de destino
                method: 'POST',
                success: function (datos) {
                    // Procesa la respuesta (suponiendo que tienes un array de etiquetas y un array de datos)
                    // var etiquetas = datos.labels; // Asume que 'labels' es un array en la respuesta
                    // var datos = datos.data; // Asume que 'data' es un array en la respuesta

                    // var usuarios = datos.usuarios;
                    var usuariosRol = datos.usuariorol;
                    

                    // Contar los usuarios según el idrol
                    var contadorRoles = {
                        idrol1: 0,
                        idrol2: 0,
                        idrol3: 0
                    };

                    usuariosRol.forEach(function(item) {
                        if (item.idrol === 1) {
                            contadorRoles.idrol1++;
                        } else if (item.idrol === 2) {
                            contadorRoles.idrol2++;
                        } else if (item.idrol === 3) {
                            contadorRoles.idrol3++;
                        }
                    });

                    // Preparar datos para el gráfico
                    var etiquetas = ['administrador', 'deposito', 'clientes'];
                    var datosGrafico = [contadorRoles.idrol1, contadorRoles.idrol2, contadorRoles.idrol3];

                    console.log(usuariosActivos);
                    // Actualiza los datos del gráfico
                    // chart.data.labels = etiquetas;
                    // chart.data.datasets[0].data = datos;

                    var tortaChart = new Chart(graficoTorta, {
                        type: 'pie',
                        data: {
                            labels: etiquetas,
                            datasets: [{
                                label: 'Distribución de Usuarios por idrol',
                                data: datosGrafico,
                                backgroundColor: ['yellow', 'blue', 'green'] // Colores para cada idrol
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false, // Evita mantener el aspecto original y permite el ajuste
                            // aspectRatio: 1, // Define una relación de aspecto específica, ajusta este valor según tus necesidades
                            plugins: {
                                legend: {
                                    display: true
                                }
                            }
                        }
                    });

                    /* ------subgrafico--------- */
                    
                    var usuariosActivos = datos.cantUsuariosActivos;

                    // Contar usuarios activos y dados de baja
                    let activos = 0, dadosDeBaja = 0;

                    usuariosActivos.forEach(fecha => {
                        if (fecha === "0000-00-00 00:00:00") {
                            activos++;
                        } else {
                            dadosDeBaja++;
                        }
                    });

                    var subtortaChart = new Chart(subGraficoTorta, {
                        type: "pie", // Tipo de gráfico
                        data: {
                            labels: ["Usuarios Activos", "Usuarios Dados de Baja"], // Etiquetas
                            datasets: [{
                                label: "Estado de Usuarios",
                                backgroundColor: ["#4CAF50", "#F44336"], // Colores para cada categoría
                                data: [activos, dadosDeBaja] // Datos calculados
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top"
                                }
                            }
                        }
                    });

                    // Actualiza el gráfico para reflejar los nuevos datos
                    // chart.update();
                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener los datos:', error);
                }
            });

    });
</script>
</body>
</html>