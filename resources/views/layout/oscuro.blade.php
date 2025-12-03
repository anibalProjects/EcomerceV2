<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LECTONIC')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Colores base invertidos para Modo Oscuro */
            --dark-bg: #1A1A1A;          /* Negro profundo para el fondo */
            --medium-bg: #2C2C2C;        /* Gris oscuro para contenedores/filtros */
            --light-text: #E0E0E0;       /* Texto principal claro */
            --subtle-text: #AAAAAA;      /* Texto secundario gris claro */

            --accent-dark: #FFFFFF;      /* El acento más oscuro ahora es blanco para contraste */
            --accent-border: #444444;    /* Borde gris oscuro */

            /* Acentos dorados/lujo (se mantienen igual) */
            --brand-font: "Playfair Display", serif;
            --main-font: "Lora", serif;
            --gold: #ad8516;
            --dark-gold-border: #a3873d;
        }

        body {
            font-family: var(--main-font);
            background: var(--dark-bg); /* Fondo oscuro */
            color: var(--light-text);   /* Texto claro */
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        /* NAVBAR */
        .nav-wrapper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--accent-border); /* Borde sutil oscuro */
        }

        .nav-centered {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .nav-centered span {
            pointer-events: auto;
        }

        .lux-brand {
            font-family: var(--brand-font);
            font-size: 2.5rem;
            font-weight: 500;
            color: var(--light-text) !important; /* Marca clara */
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        .nav-links-desktop a {
            color: var(--subtle-text); /* Enlaces sutiles */
            font-family: var(--main-font);
            font-size: 1rem;
            font-weight: 400;
            padding: 0 0.8rem;
            text-transform: capitalize;
            transition: color 0.3s ease;
        }

        .nav-links-desktop a:hover {
            color: var(--accent-dark); /* Hover se vuelve blanco puro */
            text-decoration: underline;
            text-decoration-thickness: 1px;
        }

        /* MAIN CONTENT */
        main {
            padding-top: 0;
            text-align: center;
        }

        .page-header-text, .content-container h2 {
            font-family: var(--brand-font);
            font-weight: 400;
            color: var(--light-text); /* Títulos claros */
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

        /* FORMS & CARDS */
        .filter-form {
            border: 1px solid var(--accent-border) !important;
            background-color: var(--medium-bg); /* Contenedor de filtro oscuro */
        }

        .form-control, .form-select {
            border-radius: 0;
            border-color: var(--accent-border);
            background-color: var(--medium-bg); /* Fondo de input oscuro */
            color: var(--light-text); /* Texto de input claro */
            font-family: var(--main-font);
            font-size: 0.9rem;
        }
        .form-control::placeholder {
            color: var(--subtle-text);
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--medium-bg);
            box-shadow: 0 0 0 0.15rem rgba(255, 255, 255, 0.1); /* Sombra clara sutil */
            border-color: var(--light-text);
        }

        /* Checkboxes y Radio buttons (generalmente necesitan overrides específicos de Bootstrap) */
        input[type="checkbox"] + span {
            color: var(--light-text);
        }

        .producto-card {
            border: 1px solid var(--accent-border);
            background-color: var(--medium-bg); /* Fondo de tarjeta oscuro */
            border-radius: 0;
            box-shadow: none;
            transition: box-shadow 0.3s, transform 0.3s;
        }

        .producto-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* Sombra más fuerte */
            transform: translateY(-2px);
        }

        .producto-price {
            color: #4CAF50 !important; /* Verde adaptado al oscuro */
            font-family: var(--main-font);
            font-weight: 600;
        }

        /* BOTONES (Dorados/Acento) */
        .btn-primary {
            background: var(--gold);
            border: 1px solid var(--dark-gold-border);
            color: var(--dark-text); /* Texto oscuro sobre dorado */
            font-weight: 600;
            border-radius: 4px;
            transition: background 0.2s, border-color 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .btn-primary:hover {
            background: #b59035;
            border-color: #8d765d;
        }

        /* PAGINATION */
        .pagination {
            font-family: var(--main-font);
        }

        .page-link {
            color: var(--light-text);
            border: 1px solid var(--accent-border);
            background-color: var(--medium-bg); /* Fondo de paginación oscuro */
        }

        .page-link:hover {
            color: var(--light-text);
            background-color: #383838;
        }

        .page-item.active .page-link {
            background-color: var(--gold);
            border-color: var(--dark-gold-border);
            color: var(--dark-text); /* Texto oscuro en el activo */
        }

        footer {
            text-align: center;
            padding: 1rem 0;
            font-size: 0.8rem;
            color: #666; /* Texto de footer más oscuro que el texto principal */
            margin-top: 5rem;
        }

        @media (max-width: 992px) {
            .page-header-text {
                font-size: 2.5rem;
            }
        }
    </style>

    @stack('head')
</head>

<body>

<header>
    {{-- EL HEADER ESTÁ VACÍO. NAV SE GESTIONA EN LA VISTA --}}
</header>

<main>
    @yield('content')
</main>

<footer>
    &copy; {{ date('Y') }} LECTONIC.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
