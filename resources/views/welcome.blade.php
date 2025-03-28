<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Nuestra Plataforma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .hero h1 {
            font-size: 3rem;
        }
        .btn-custom {
            background-color: #ff7eb3;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #ff4f80;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1 class="animate__animated animate__fadeInDown">Bienvenido a Nuestra Plataforma</h1>
        <p class="animate__animated animate__fadeInUp">Gestiona tus compras y proveedores con facilidad</p>
        <a href="{{ route('login') }}" class="btn btn-custom animate__animated animate__pulse animate__infinite">Iniciar Sesión</a>
    </div>
</body>
</html>
