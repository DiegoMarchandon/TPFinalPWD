<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Deposito</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-Shop Depósito</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ordenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Shipping</a> <!-- preparar, gestionar y actualizar pedidos -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Depósito Dashboard</h1>
        <div class="row">
            <!-- Card: Total Orders to Process -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body">
                        <!-- órdenes de compra que han sido confirmadas por el cliente, pero aún no han sido preparadas para el envío -->
                        <h5 class="card-title">Ordenes para Procesar</h5>
                        <p class="card-text fs-3">12</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">Ver Ordenes</a>
                    </div>
                </div>
            </div>
            <!-- Card: Total Products in Stock -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Productos Totales en Stock</h5>
                        <p class="card-text fs-3">250</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">Gestionar Stock</a>
                    </div>
                </div>
            </div>
            <!-- Card: Pending Shipments -->
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-4">
                    <div class="card-body">
                        <!-- órdenes que han sido procesadas (preparadas y empaquetadas) pero que aún no han sido enviadas al cliente -->
                         <!-- órdenes para procesar serían pedidos que todavía no fueron preparados y requieren acciones internas, y los envíos pendientes son aquellos que están listos para ser despachados -->
                        <h5 class="card-title">Envíos Pendientes</h5>
                        <p class="card-text fs-3">5</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">Preparar Envíos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Manage Stock Table -->
            <div class="col-md-12">
                <h3>Gestionar Stock</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Producto A</td>
                            <td>$19.99</td>
                            <td>
                                <input type="number" class="form-control form-control-sm" value="50" min="0">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">Actualizar Stock</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Producto B</td>
                            <td>$29.99</td>
                            <td>
                                <input type="number" class="form-control form-control-sm" value="30" min="0">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">Actualizar Stock</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Producto C</td>
                            <td>$39.99</td>
                            <td>
                                <input type="number" class="form-control form-control-sm" value="70" min="0">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">Actualizar Stock</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Orders Management Table -->
            <div class="col-md-12">
                <h3>Orders to Process</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden ID</th>
                            <th>Nombre del Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>John Doe</td>
                            <td>Producto A</td>
                            <td>3</td>
                            <td><span class="badge bg-warning">Pendiente</span></td>
                            <td>
                                <button class="btn btn-sm btn-success">Enviar</button>
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>Jane Smith</td>
                            <td>Producto B</td>
                            <td>1</td>
                            <td><span class="badge bg-warning">Pendiente</span></td>
                            <td>
                                <button class="btn btn-sm btn-success">Enviar</button>
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>Mark Johnson</td>
                            <td>Producto C</td>
                            <td>5</td>
                            <td><span class="badge bg-warning">Pendiente</span></td>
                            <td>
                                <button class="btn btn-sm btn-success">Enviar</button>
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>