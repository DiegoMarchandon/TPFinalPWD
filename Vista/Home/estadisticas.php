<?php
include_once '../../configuracion.php';
include_once('../estructura/headerSeguro.php');
// creo un objeto de la clase abmUsuarioRol para llamar a la funcion verificarRolUsuario
$abmUsuarioRol = new ABMUsuarioRol(); 

// Obtener el ID del usuario en la sesion para verificar si tiene permisos
$idUsuarioActual = $session->getUsuario()->getIdUsuario();

// Verificar si el usuario tiene permisos para acceder a esta página (el 1 es el administrador o sea que le estoy
// diciendo que si el usuario no es administrador lo redirija al login)
$usuarioPermitido = $abmUsuarioRol->verificarRolUsuario($idUsuarioActual, 1);
if (!$usuarioPermitido) {
    header('Location: ../Home/login.php');
    exit();
}

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
        <h1>Composición de Usuarios</h1> 

        <div style="display: flex; align-items: center; width: 800px;">       
            <div style="width: 400px; height: 300px;">
                <canvas id="graficoTorta"></canvas>
            </div>
            <div style="width: 200px; height: 200px; margin-left: 20px;">
                <canvas id="subgraficoTorta"></canvas>
            </div>
        </div>
    </div>

    <div id="comprasInfo">
        <h1>Estados de Compras</h1>
        <div style="width: 400px; height: 600px">
            <canvas id="graficoBarras"></canvas>
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

                    console.log(datos.colCompraEstados);

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

                    /* ------subgrafico de tortas--------- */
                    
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


                    /* ------ grafico de barras ------- */
                    var compraEstados = datos.colCompraEstados;

                    var estados = ["Iniciada","Aceptada","Enviada","Cancelada"];
                    var colores = ['rgba(75, 220, 192, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(255, 99, 132, 0.6)'];

                    // Procesar los datos para contar las ocurrencias
                    var conteo = { 1: 0, 2: 0, 3: 0, 4: 0 }; // Inicializamos el conteo con 0 para todos los estados

                    // proceso los datos para contar las ocurrencias
                    compraEstados.forEach(function (valor) {
                        conteo[valor] = (conteo[valor] || 0) + 1;
                    });

                    var datasets = estados.map(function(estado, index) {
                        return {
                            label: estado, // Nombre del estado
                            data: [conteo[index + 1] || 0], // Valor para el estado. Usamos index + 1 porque los valores son 1-4
                            backgroundColor: colores[index], // Color asociado
                            borderColor: colores[index].replace('0.6', '1'), // Borde más intenso
                            borderWidth: 1
                        };
                    });

                    // var datos = Object.values(conteo);  // [número de ocurrencias de cada valor]

                    // Crear el gráfico de barras
                    var graficoBarras = document.getElementById('graficoBarras').getContext('2d');
                    var barrasChart = new Chart(graficoBarras, {
                        type: 'bar',
                        data: {
                            labels: ["Estados"], // Etiqueta genérica para agrupar los datos
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true, // Muestra la leyenda con colores por estado
                                    position: 'top'
                                },
                                tooltip: {
                                    enabled: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });

                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener los datos:', error);
                }
            });

    });
</script>
</body>
</html>