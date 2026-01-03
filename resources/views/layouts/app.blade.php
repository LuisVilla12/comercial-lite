<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema Comercial Lite')</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body>

<nav class="mb-4 bg-blue-900 py-4 px-10 text-white">
    <div class="container flex justify-between">
        <a class="navbar-brand" href="/">Comercial Lite</a>
        <a class="text-white" href="/clientes">Catalogo Clientes</a>
        <a class="text-white" href="/proveedores">Catalogo Proveedores</a>
    </div>
</nav>

<div class="container w-5/6 mx-auto">
    @yield('content')
</div>

</body>
</html>
