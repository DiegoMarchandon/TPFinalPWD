<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Correo</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function(){
            emailjs.init("dgc2Clo0soEeyxVnL"); // Inicializa EmailJS con tu clave pública
        })();

        document.addEventListener('DOMContentLoaded', function () {
            emailjs.send('service_ot7ycz5', 'template_fpc9kkb', {
                to_name: 'andres',
                to_email: 'prueba.aemv@gmail.com', //aca pone tu mail
                from_name: 'E-Commerce Team',
                message: 'Usted ha confirmado una compra. Este pendiente a la respuesta de la misma, en breve le notificaremos.'
            }).then(function(response) {
                console.log('SUCCESS!', response.status, response.text);
            }, function(error) {
                console.log('FAILED...', error);
            });
        });
    </script>
</head>
<body>
    <h1>Enviando correo...</h1>
</body>
</html>