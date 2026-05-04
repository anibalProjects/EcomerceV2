<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LECTONIC')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
    /* Dark Mode Variables */
    --dark-bg: #0a0a0a;
    --dark-surface: #121212;
    --dark-card: #1a1a1a;
    --dark-border: #2a2a2a;
    --light-text: #e8e6e3;
    --muted-text: #8a8a8a;
    --gold: #ad8516;
    --gold-light: #c9a227;
    --gold-dark: #8a6a10;
    --success-dark: #22c55e;
    --brand-font: "Playfair Display", serif;
    --main-font: "Lora", serif;
    --nav-border: #2a2a2a;
}

body {
    font-family: var(--main-font);
    background: var(--dark-bg);
    color: var(--light-text);
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
    border-bottom: 1px solid var(--dark-border);
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
    color: var(--light-text) !important;
    margin: 0;
    padding: 0;
    white-space: nowrap;
}

.nav-links-desktop a {
    color: var(--muted-text);
    font-family: var(--main-font);
    font-size: 1rem;
    font-weight: 400;
    padding: 0 0.8rem;
    text-transform: capitalize;
    transition: color 0.3s ease;
}

.nav-links-desktop a:hover {
    color: var(--light-text);
}

/* MAIN CONTENT */
main {
    padding-top: 0;
    text-align: center;
}

.page-header-text,
.content-container h2 {
    font-family: var(--brand-font);
    font-weight: 400;
    color: var(--light-text);
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

hr {
    border-color: var(--dark-border);
    opacity: 0.5;
}

/* FORMS */
.filter-form {
    border: 1px solid var(--dark-border) !important;
    background-color: var(--dark-surface) !important;
    border-radius: 4px;
}

.form-control,
.form-select {
    background-color: var(--dark-card) !important;
    border: 1px solid var(--dark-border) !important;
    color: var(--light-text) !important;
    font-family: var(--main-font);
    font-size: 0.9rem;
}

.form-control:focus,
.form-select:focus {
    background-color: var(--dark-card) !important;
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 2px rgba(173, 133, 22, 0.2) !important;
    color: var(--light-text) !important;
}

.form-control::placeholder {
    color: var(--muted-text) !important;
}

.form-label,
label {
    color: var(--muted-text);
    font-size: 0.9rem;
}

/* CARDS */
.producto-card,
.card {
    background-color: var(--dark-card) !important;
    border: 1px solid var(--dark-border) !important;
    border-radius: 0;
    transition: box-shadow 0.3s, transform 0.3s;
}

.producto-card:hover,
.card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    transform: translateY(-2px);
}

.card-body {
    background-color: var(--dark-card) !important;
}

.card-footer {
    background-color: var(--dark-card) !important;
    border-top: 1px solid var(--dark-border) !important;
}

.card-title {
    color: var(--light-text) !important;
    font-family: var(--brand-font);
}

.producto-price {
    color: var(--success-dark) !important;
    font-family: var(--main-font);
    font-weight: 600;
}

.producto-image {
    background-color: var(--dark-surface);
}

.producto-image > div {
    border-color: var(--dark-border) !important;
    background-color: var(--dark-surface);
}

/* BUTTONS */
.btn-primary {
    background: var(--gold) !important;
    border: 1px solid var(--gold-dark) !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: var(--gold-light) !important;
    border-color: var(--gold) !important;
    box-shadow: 0 0 20px rgba(173, 133, 22, 0.3);
}

.btn-primary:disabled {
    background: var(--dark-border) !important;
    border-color: var(--dark-border) !important;
    color: var(--muted-text) !important;
}

.btn-secondary {
    background: var(--dark-surface) !important;
    border: 1px solid var(--dark-border) !important;
    color: var(--light-text) !important;
}

.btn-secondary:hover {
    background: var(--dark-card) !important;
    border-color: var(--muted-text) !important;
}

.btn-outline-danger {
    border-color: #dc3545 !important;
    color: #dc3545 !important;
    background: transparent !important;
}

.add-cart-btn {
    font-family: var(--main-font);
}

/* DROPDOWN */
.dropdown-menu {
    background-color: var(--dark-card) !important;
    border: 1px solid var(--dark-border) !important;
}

.dropdown-item {
    color: var(--light-text) !important;
}

.dropdown-item:hover {
    background-color: var(--dark-surface) !important;
    color: var(--gold-light) !important;
}

.dropdown-item.text-danger {
    color: #ef4444 !important;
}

/* PAGINATION */
.pagination {
    font-family: var(--main-font);
}

.page-link {
    background-color: var(--dark-card) !important;
    border: 1px solid var(--dark-border) !important;
    color: var(--light-text) !important;
}

.page-link:hover {
    background-color: var(--dark-surface) !important;
    color: var(--gold-light) !important;
}

.page-item.active .page-link {
    background-color: var(--gold) !important;
    border-color: var(--gold-dark) !important;
    color: #fff !important;
}

.page-item.disabled .page-link {
    background-color: var(--dark-surface) !important;
    color: var(--muted-text) !important;
}

/* ALERTS */
.alert-warning {
    background-color: rgba(173, 133, 22, 0.15) !important;
    border-color: var(--gold-dark) !important;
    color: var(--gold-light) !important;
}

/* TEXT UTILITIES */
.text-dark {
    color: var(--light-text) !important;
}

.text-muted {
    color: var(--muted-text) !important;
}

.text-success {
    color: var(--success-dark) !important;
}

/* LINKS */
a.text-decoration-none.text-dark {
    color: var(--light-text) !important;
}

a.text-decoration-none.text-dark:hover {
    color: var(--gold-light) !important;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 1rem 0;
    font-size: 0.8rem;
    color: var(--muted-text);
    margin-top: 5rem;
    border-top: 1px solid var(--dark-border);
}

/* CHECKBOX */
input[type="checkbox"] {
    accent-color: var(--gold);
}

/* SCROLLBAR */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--dark-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--dark-border);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--muted-text);
}

/* RESPONSIVE */
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