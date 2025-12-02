<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LECTONIC')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Colores Base */
            --white-bg: #ffffff;
            --dark-text: #222222;
            --light-text: #5a5a5a;

            /* Color de Acento */
            --accent-dark: #111111;
            --accent-border: #dddddd;

            /* Tipografía */
            --brand-font: "Playfair Display", serif;
            --main-font: "Lora", serif;
            --gold: #ad8516;
            --wood-shadow: rgba(90,60,30,0.15);
            --light-bg: #f7f5f2;
            --dark-gold-border: #a3873d;
        }

        body {
            font-family: var(--main-font);
            background: var(--white-bg);
            color: var(--dark-text);
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        /* --- HEADER (Contenedor vacío) --- */
        .lux-header {
            /* Eliminamos el padding para que la nav de la vista se pegue al borde */
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            min-height: 0;
        }

        /* --- MAIN CONTENT & TYPOGRAPHY --- */
        main {
            padding-top: 0; /* ¡Ajuste clave! Eliminamos el padding superior */
            text-align: center;
        }

        /* Estilos de la marca */
        .lux-brand {
            font-family: var(--brand-font);
            font-size: 2.5rem;
            font-weight: 500;
            color: var(--dark-text) !important;
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        /* Títulos usan Playfair Display */
        .page-header-text, .content-container h2 {
            font-family: var(--brand-font);
            font-weight: 400;
            color: var(--dark-text);
        }

        .page-header-text {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-top: 5rem;
            margin-bottom: 5rem;
        }

        .content-container {
            text-align: left;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* --- ESTILOS DE COMPONENTES (sin cambios) --- */
        .menu-toggle {
            font-size: 1.5rem;
            color: var(--dark-text);
            cursor: pointer;
        }
        .nav-links-desktop a {
            color: var(--light-text);
            font-family: var(--main-font);
            font-size: 1rem;
            font-weight: 400;
            padding: 0 0.8rem;
            text-transform: capitalize;
            transition: color 0.3s ease;
        }
        .nav-links-desktop a:hover {
            color: var(--accent-dark);
            text-decoration: underline;
            text-decoration-thickness: 1px;
        }
        .btn-primary {
            background: var(--gold);
            border: 1px solid var(--dark-gold-border);
            color: #ffffff;
            font-weight: 600;
            border-radius: 4px; /* Un poco más cuadrado */
            transition: background 0.2s, border-color 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-primary:hover {
            background: #b59035;
            border-color: #8d765d;
            filter: none; /* Desactivamos el filtro de brillo de Bootstrap para un control total */
        }
        .producto-price {
            color: #007000 !important;
            font-family: var(--main-font);
            font-weight: 600;
        }
        .filter-form {
            border: 1px solid var(--accent-border) !important;
            background-color: #fafafa;
        }
        .form-control, .form-select {
            border-radius: 0;
            border-color: var(--accent-border);
            font-family: var(--main-font);
            font-size: 0.9rem;
            color: var(--dark-text);
        }
        .producto-card {
            border: 1px solid var(--accent-border);
            border-radius: 0;
            box-shadow: none;
            transition: box-shadow 0.3s, transform 0.3s;
        }
        .producto-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 1rem 0;
            font-size: 0.8rem;
            color: #ccc;
            border-top: none;
            margin-top: 5rem;
        }

        /* MEDIA QUERIES */
        @media (max-width: 992px) {
            .page-header-text {
                font-size: 2.5rem;
            }
        }
    </style>

    @stack('head')
</head>

<body>

<header class="lux-header">
    {{-- EL HEADER ESTÁ VACÍO. LA NAV SE GESTIONA EN LA VISTA HOME --}}
</header>

<main>
    @yield('content')
    @yield('auth-content')
</main>

<footer>
    &copy; {{ date('Y') }} LECTONIC.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
