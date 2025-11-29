<div>
    <h1>Login</h1>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="tema">Tema:</label>
            <select name="tema" id="tema">
                <option value="claro">Claro</option>
                <option value="oscuro">Oscuro</option>
            </select>
        </div>

        <div>
            <label for="moneda">Moneda:</label>
            <select name="moneda" id="moneda">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>

        <div>
            <label for="paginacion">Paginación:</label>
            <select name="paginacion" id="paginacion">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>

        <button type="submit">Login</button>
    </form>
    <a href="{{ route('register') }}">Register</a>
    <a href="{{ route('logout') }}">Logout</a>
    <a href="{{ route('preferencias.index') }}">Preferencias</a>
</div>