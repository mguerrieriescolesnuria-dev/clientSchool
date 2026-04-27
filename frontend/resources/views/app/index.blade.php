<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>clientSchool Frontend</title>
</head>
<body>
@if (!$user)
    <h1>clientSchool Frontend</h1>
    <p>Client Laravel connectat a l’API REST de students, teachers i subjects.</p>
    <p>API configurada a: <strong>{{ $apiBaseUrl }}</strong></p>

    @if ($errors->any())
        <p><strong>Error:</strong> {{ $errors->first() }}</p>
    @endif

    <h2>Iniciar sessió</h2>
    <form method="POST" action="{{ route('auth.login') }}">
        @csrf
        <p>
            <label>Email</label><br>
            <input name="email" type="email" value="{{ old('email') }}">
        </p>
        <p>
            <label>Contrasenya</label><br>
            <input name="password" type="password">
        </p>
        <button type="submit">Iniciar sessió</button>
    </form>

    <h2>Crear usuari</h2>
    <form method="POST" action="{{ route('auth.register') }}">
        @csrf
        <p>
            <label>Nom</label><br>
            <input name="name" type="text">
        </p>
        <p>
            <label>Email</label><br>
            <input name="email" type="email">
        </p>
        <p>
            <label>Contrasenya</label><br>
            <input name="password" type="password">
        </p>
        <button type="submit">Crear usuari i entrar</button>
    </form>
@else
    <h1>Dashboard</h1>
    <p>Usuari: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
    <p>API: <strong>{{ $apiBaseUrl }}</strong></p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sortir</button>
    </form>

    @if (session('success'))
        <p><strong>{{ session('success') }}</strong></p>
    @endif

    @if (session('error'))
        <p><strong>{{ session('error') }}</strong></p>
    @endif

    @if (($resources[$resource]['error'] ?? null) !== null)
        <p><strong>{{ $resources[$resource]['error'] }}</strong></p>
    @endif

    <h2>Recursos</h2>
    <ul>
        @foreach (['students', 'teachers', 'subjects'] as $resourceName)
            <li>
                <a href="{{ route('app.dashboard', ['resource' => $resourceName]) }}">{{ ucfirst($resourceName) }}</a>
                ({{ count($resources[$resourceName]['rows']) }})
            </li>
        @endforeach
    </ul>

    <h2>{{ ucfirst($resource) }}</h2>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                @foreach ($fields as $field)
                    <th>{{ $field }}</th>
                @endforeach
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($resources[$resource]['rows'] as $row)
                <tr>
                    @foreach ($fields as $field)
                        <td>{{ $row[$field] ?? '' }}</td>
                    @endforeach
                    <td>
                        <a href="{{ route('app.dashboard', ['resource' => $resource, 'edit' => $row['id']]) }}">Editar</a>
                        <form method="POST" action="{{ route('resources.destroy', ['resource' => $resource, 'id' => $row['id']]) }}" style="display:inline;">
                            @csrf
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($fields) + 1 }}">No hi ha registres.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>{{ $editing ? 'Editar registre' : 'Nou registre' }}</h2>
    <form method="POST" action="{{ $editing
        ? route('resources.update', ['resource' => $resource, 'id' => $editing['id']])
        : route('resources.store', ['resource' => $resource]) }}">
        @csrf
        @foreach ($fields as $field)
            <p>
                <label>{{ $field }}</label><br>
                <input
                    name="{{ $field }}"
                    type="text"
                    value="{{ old($field, $editing[$field] ?? '') }}"
                >
            </p>
        @endforeach
        <button type="submit">{{ $editing ? 'Guardar canvis' : 'Crear registre' }}</button>
    </form>

    @if ($editing)
        <p><a href="{{ route('app.dashboard', ['resource' => $resource]) }}">Cancel·lar edició</a></p>
    @endif
@endif
</body>
</html>
