<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tienda Laravel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tipografía -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Montserrat:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #c9a84b;
            --wood-shadow: rgba(90,60,30,0.15);
            --light-bg: #f7f5f2;
        }

        body {
            font-family: "Montserrat", sans-serif;
            background: var(--light-bg);
            color: #333;
        }

        /* NAVBAR moderna, clara, con un toque dorado */
        .lux-navbar {
            background: #ffffff;
            border-bottom: 2px solid rgba(201,168,75,0.4);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .lux-brand {
            font-family: "Playfair Display", serif;
            font-size: 1.5rem;
            color: #222 !important;
        }

        /* CONTENEDOR PRINCIPAL LIMPIO */
        main.container {
            margin-top: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px var(--wood-shadow);
        }

        /* TARJETAS MODERNAS CON TOQUE MADERA SUAVE */
        .card, .producto-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            box-shadow: 0 6px 20px var(--wood-shadow);
            transition: 0.2s;
        }
        .card:hover, .producto-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.2);
        }

        /* IMÁGENES */
        .producto-image {
            background: #fafafa;
            padding: 1rem;
        }
        .producto-image img {
            max-width: 100%;
            border-radius: 10px;
        }

        /* BOTONES */
        .btn-primary {
            background: linear-gradient(180deg, var(--gold), #b59035);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-primary:hover {
            filter: brightness(0.92);
        }

        .cart-btn {
            border: 1px solid var(--gold);
            color: var(--gold);
            background: transparent;
            border-radius: 8px;
        }
        .cart-btn:hover {
            background: rgba(201,168,75,0.1);
        }

        /* TITULOS */
        h1,h2,h3,h4,h5 {
            font-family: "Playfair Display", serif;
            color: #222;
        }

        /* FOOTER LIMPIO */
        footer.lux-footer {
            margin-top: 3rem;
            text-align: center;
            padding: 1rem;
            color: #555;
            font-size: 0.9rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
    </style>

    @stack('head')
</head>

<body>



<main class="container">
    @yield('content')
</main>

<footer class="lux-footer">
    <small>&copy; {{ date('Y') }} {{ config('app.name', 'Tienda Laravel') }} — Estilo moderno con detalles dorados</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>

