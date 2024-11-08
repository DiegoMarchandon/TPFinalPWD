<?php
// include_once('estructura/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vista Cliente</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
</head>
<body>
    <h1 class=" text-center">vista Registrado</h1>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">E-Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mi cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Nuestros productos</h1>
        <div class="row">
            <!-- Product Card 1 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Producto 1</h5>
                        <p class="card-text">acá iría una breve descripción del producto 1. </p>
                        <p class="text-success">$19.99</p>
                        <a href="#" class="btn btn-primary">Agregar al carrito</a>
                    </div>
                </div>
            </div>
            <!-- Product Card 2 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Producto 2</h5>
                        <p class="card-text">acá iría una breve descripción del producto 2.</p>
                        <p class="text-success">$29.99</p>
                        <a href="#" class="btn btn-primary">Agregar al carrito</a>
                    </div>
                </div>
            </div>
            <!-- Product Card 3 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product 3">
                    <div class="card-body">
                        <h5 class="card-title">Product 3</h5>
                        <p class="card-text">acá iría una breve descripción del producto 3.</p>
                        <p class="text-success">$39.99</p>
                        <a href="#" class="btn btn-primary">Agregar al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// include_once('estructura/footer.php');
?>