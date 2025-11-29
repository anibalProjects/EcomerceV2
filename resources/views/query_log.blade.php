<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Log</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #f4f4f4;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .actions {
            margin-top: 20px;
        }

        a {
            margin-right: 15px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>

<body>
    <h1>Executed Queries</h1>

    @if(empty($queries))
        <p>No queries captured (or session expired).</p>
    @else
        <ul>
            @foreach($queries as $query)
                <li>
                    <strong>Query:</strong> {{ $query['query'] }} <br>
                    <strong>Bindings:</strong> {{ implode(', ', $query['bindings']) }} <br>
                    <strong>Time:</strong> {{ $query['time'] }}ms
                </li>
            @endforeach
        </ul>
    @endif

    <div class="actions">
        <a href="{{ route('logout') }}">Logout</a>
        <a href="{{ route('preferencias.index') }}">Go to Preferences</a>
    </div>
</body>

</html>