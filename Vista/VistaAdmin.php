<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vista Admin</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-Shop Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ordenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Configuraciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row">
            <!-- Card: Total Products -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Productos Totales</h5>
                        <p class="card-text fs-3">150</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">ver todos los productos</a>
                    </div>
                </div>
            </div>
            <!-- Card: New Orders -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Nuevas Ordenes</h5>
                        <p class="card-text fs-3">24</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">ver todas las ordenes</a>
                    </div>
                </div>
            </div>
            <!-- Card: Registered Users -->
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios Registrados</h5>
                        <p class="card-text fs-3">320</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">Ver Usuarios</a>
                    </div>
                </div>
            </div>
            <!-- Card: Sales Revenue -->
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Reportes de Ventas</h5>
                        <p class="card-text fs-3">$12,540</p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="text-white text-decoration-none">Ver Reportes de Ventas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Manage Products Table -->
            <div class="col-md-12">
                <h3>Lista de productos</h3>
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
                            <td>Product A</td>
                            <td>$19.99</td>
                            <td>50</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Editar</button>
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Product B</td>
                            <td>$29.99</td>
                            <td>30</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Editar</button>
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Product C</td>
                            <td>$39.99</td>
                            <td>70</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Editar</button>
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>