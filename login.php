<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo de barco */
        body {
            background-image: url('./assets/image/imagen_logo_.jpeg');
            /* Reemplaza por la URL de tu imagen */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            /* Color del texto en todo el body */
        }

        .login-container {
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            /* Fondo blanco semi-transparente */
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 250px;
            height: auto;
        }

        .btn-primary {
            background-color: #007bff;
            /* Azul */
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Azul oscuro */
        }

        .form-control {
            border-radius: 5px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #f79c42;
            /* Color melón */
        }

        /* Color de las etiquetas */
        label {
            color: #007bff;
            /* Azul */
        }

        /* Botón de "Iniciar Sesión" */
        .btn-login {
            background-color: #f79c42;
            /* Color melón */
            border: none;
            font-weight: bold;
            color: white;
            border-radius: 5px;
        }

        .btn-login:hover {
            background-color: #ff7f50;
            /* Color melón más brillante */
        }

        /* Mensajes de error */
        .text-danger {
            font-size: 12px;
            display: none;
        }

    </style>
</head>

<body>

    <div class="login-container">
        <!-- Logo -->
        <div class="logo-container">
            <img src="./assets/image/logo_nombre.png"  alt="Logo"   > <!-- Cambia esta URL por la de tu logo -->
        </div>

        <h3 class="text-center mb-4" style="color: #007bff;">Inicio de Sesión</h3>
        <form id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario">
                <div class="text-danger" id="usernameError">Por favor, ingresa tu usuario.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Ingresa tu contraseña">
                <div class="text-danger" id="passwordError">Por favor, ingresa tu contraseña.</div>
            </div>

            <div id="error-message" class="alert alert-danger" style="display: none;">
                Usuario o contraseña incorrectos.
            </div>

            <button type="button" class="btn btn-login w-100" onclick="inicio_de_sesion()">Iniciar Sesión</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir jQuery desde un CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/ajax.js"></script>

    

</body>
</html>