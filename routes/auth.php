<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// GLOBAL DEBUG LOG
file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Auth routes file loaded at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

Route::get('/login', function () {
    file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Login GET route hit\n", FILE_APPEND);
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Login POST route hit\n", FILE_APPEND);

    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    DB::enableQueryLog();

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Sincronizar cookies a la BD
        $user = Auth::user();
        $preferences = ['tema', 'moneda', 'paginacion'];

        // DEBUG LOG
        file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth.log', "Login Request Inputs: " . json_encode($request->all()) . "\n", FILE_APPEND);

        foreach ($preferences as $key) {
            // Priorizar el valor del formulario, si no existe, usar cookie
            $value = $request->input($key) ?? Cookie::get($key);

            file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth.log', "Processing $key: Value = " . ($value ?? 'NULL') . "\n", FILE_APPEND);

            if ($value) {
                $user->preferences()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        $queries = DB::getQueryLog();
        return redirect()->route('query-log')->with('queries', $queries);
    }

    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
    ]);
});

Route::get('/register', function () {
    file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Register GET route hit\n", FILE_APPEND);
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Register POST route hit. Inputs: " . json_encode($request->all()) . "\n", FILE_APPEND);

    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'tema' => 'nullable|string|in:claro,oscuro',
            'moneda' => 'nullable|string|in:USD,EUR',
            'paginacion' => 'nullable|integer|in:10,20,50',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        file_put_contents('c:\Users\thesi\.gemini\antigravity\scratch\debug_auth_global.log', "Validation Failed: " . json_encode($e->errors()) . "\n", FILE_APPEND);
        throw $e;
    }

    DB::enableQueryLog();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Auth::login($user);

    // Sincronizar cookies a la BD
    $preferences = ['tema', 'moneda', 'paginacion'];
    foreach ($preferences as $key) {
        // Priorizar el valor del formulario, si no existe, usar cookie
        $value = $request->input($key) ?? Cookie::get($key);

        if ($value) {
            $user->preferences()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    $queries = DB::getQueryLog();
    return redirect()->route('query-log')->with('queries', $queries);
})->name('register');

Route::get('/query-log', function () {
    $queries = session('queries', []);
    return view('query_log', ['queries' => $queries]);
})->name('query-log'); // Removed middleware('auth') for debugging

Route::any('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
