<body>
<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf

    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">

            @error('nombre')
            <small class="text-danger">{{ $message }} </small>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="apellido">Apellidos:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido') }}">

            @error('apellido')
            <small class="text-danger">{{ $message }} </small>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">

            @error('email')
            <small class="text-danger">{{ $message }} </small>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password">

            @error('password')
            <small class="text-danger">{{ $message }} </small>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <br>
        <button type="submit" class="btn btn-primary">Crear Usuario Cliente</button>
        <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver</a>
    </div>
</form>

