<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>clientSchool Frontend</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700" rel="stylesheet" />
    <style>
        :root {
            --ink: #172033;
            --muted: #5f6f89;
            --line: #d9e0ea;
            --warm: #d97706;
            --warm-dark: #b45309;
            --teal: #0f766e;
            --teal-dark: #115e59;
            --bg-a: #fff7e8;
            --bg-b: #edf9f5;
            --panel: rgba(255, 255, 255, 0.9);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            font-family: "Space Grotesk", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(217, 119, 6, 0.18), transparent 28%),
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.15), transparent 30%),
                linear-gradient(180deg, var(--bg-a) 0%, var(--bg-b) 55%, #f6f7fb 100%);
        }

        .page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 20px 40px;
        }

        .hero,
        .panel,
        .card,
        .table-card,
        .form-card {
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(25, 38, 60, 0.12);
            backdrop-filter: blur(10px);
        }

        .landing-grid,
        .dashboard-grid,
        .main-grid,
        .stats-grid {
            display: grid;
            gap: 24px;
        }

        .landing-grid {
            grid-template-columns: 1.5fr 0.9fr;
            align-items: center;
            min-height: calc(100vh - 56px);
        }

        .dashboard-grid {
            gap: 18px;
        }

        .main-grid {
            grid-template-columns: 170px 1fr;
            align-items: start;
        }

        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .hero,
        .panel,
        .table-card,
        .form-card {
            padding: 22px;
        }

        .eyebrow {
            display: inline-block;
            padding: 9px 15px;
            border-radius: 999px;
            border: 1px solid rgba(217, 119, 6, 0.4);
            color: var(--warm);
            background: rgba(255, 248, 235, 0.85);
            font-size: 13px;
            font-weight: 600;
        }

        h1, h2, h3 {
            margin: 0 0 10px;
            line-height: 1.05;
        }

        h1 {
            font-size: 42px;
            letter-spacing: -0.04em;
            margin-top: 18px;
        }

        h2 {
            font-size: 28px;
        }

        h3 {
            font-size: 20px;
        }

        p {
            margin: 0 0 12px;
            color: var(--muted);
            line-height: 1.65;
            font-size: 15px;
        }

        .api-box,
        .mini-user,
        .list-box {
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.82);
        }

        .button,
        button,
        .button-link {
            display: inline-block;
            border: 0;
            border-radius: 16px;
            padding: 11px 16px;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .button-warm {
            background: var(--warm);
            color: #fff;
        }

        .button-warm:hover {
            background: var(--warm-dark);
        }

        .button-teal {
            background: var(--teal);
            color: #fff;
        }

        .button-teal:hover {
            background: var(--teal-dark);
        }

        .button-light {
            background: #fff;
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        form {
            margin: 0;
        }

        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--ink);
            font-size: 14px;
        }

        input {
            width: 100%;
            border: 1px solid #cfd8e4;
            border-radius: 14px;
            padding: 11px 13px;
            font: inherit;
            font-size: 15px;
            background: #fff;
        }

        .message {
            padding: 13px 15px;
            border-radius: 16px;
            border: 1px solid var(--line);
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.88);
            font-size: 14px;
        }

        .message.error {
            border-color: #f0b8b8;
            color: #8f2d2d;
            background: #fff5f5;
        }

        .message.success {
            border-color: #b7e2d5;
            color: #16654f;
            background: #f2fffb;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 8px;
        }

        .resource-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .resource-links a {
            display: block;
            padding: 10px 12px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 12px;
            line-height: 1.2;
        }

        .resource-links a.active {
            border-color: rgba(217, 119, 6, 0.45);
            background: #fff6ea;
            color: var(--warm-dark);
            font-weight: 700;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--ink);
        }

        .card p {
            font-size: 12px;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            background: #fff;
            overflow: hidden;
            border-radius: 16px;
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #e6ecf3;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .actions a {
            color: var(--teal-dark);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .inline-form {
            display: inline;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .sidebar-panel {
            padding: 16px 14px;
        }

        .sidebar-panel h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .table-card h2 {
            font-size: 24px;
        }

        .form-card h3 {
            font-size: 18px;
        }

        @media (max-width: 920px) {
            .landing-grid,
            .main-grid,
            .stats-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 34px;
            }

            .topbar,
            .section-title {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
@if (!$user)
    <div class="page">
        <div class="landing-grid">
            <section class="hero">
                <span class="eyebrow">DAW M0613 · Client Laravel</span>
                <h1>clientSchool Frontend</h1>
                <p>Client Laravel connectat a la teva API REST de students, teachers i subjects.</p>
                <p>Per aquesta part tens un accés bàsic i un dashboard per crear, editar i eliminar registres.</p>
                <div class="api-box">
                    API configurada a <strong>{{ $apiBaseUrl }}</strong>
                </div>
            </section>

            <aside class="panel">
                <h2>Iniciar sessió</h2>

                @if ($errors->any())
                    <div class="message error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('auth.login') }}" class="stack">
                    @csrf
                    <div>
                        <label>Email</label>
                        <input name="email" type="email" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label>Contrasenya</label>
                        <input name="password" type="password">
                    </div>
                    <button type="submit" class="button button-warm">Iniciar sessió</button>
                </form>

                <h3 style="margin-top: 28px;">Crear usuari</h3>
                <form method="POST" action="{{ route('auth.register') }}" class="stack">
                    @csrf
                    <div>
                        <label>Nom</label>
                        <input name="name" type="text">
                    </div>
                    <div>
                        <label>Email</label>
                        <input name="email" type="email">
                    </div>
                    <div>
                        <label>Contrasenya</label>
                        <input name="password" type="password">
                    </div>
                    <button type="submit" class="button button-teal">Crear usuari i entrar</button>
                </form>
            </aside>
        </div>
    </div>
@else
    <div class="page">
        <div class="dashboard-grid">
            <section class="hero">
                <div class="topbar">
                    <div>
                        <span class="eyebrow">ClientSchool</span>
                        <h1 style="font-size: 38px; margin-top: 16px;">Dashboard</h1>
                        <p>Gestiona els recursos del backend i comprova al moment que queden guardats.</p>
                    </div>
                    <div class="stack" style="min-width: 240px;">
                        <div class="mini-user">
                            <strong>{{ $user->name }}</strong><br>
                            {{ $user->email }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="button button-light">Sortir</button>
                        </form>
                    </div>
                </div>
                <div class="api-box">API connectada a <strong>{{ $apiBaseUrl }}</strong></div>
            </section>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            @if (($resources[$resource]['error'] ?? null) !== null)
                <div class="message error">{{ $resources[$resource]['error'] }}</div>
            @endif

                    <div class="stats-grid">
                        @foreach (['students', 'teachers', 'subjects'] as $resourceName)
                    <div class="card" style="padding: 18px 20px;">
                        <p style="margin-bottom: 8px;">{{ ucfirst($resourceName) }}</p>
                        <div class="stat-number">{{ count($resources[$resourceName]['rows']) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="main-grid">
                <aside class="panel sidebar-panel">
                    <h3>Recursos</h3>
                    <ul class="resource-links">
                        @foreach (['students', 'teachers', 'subjects'] as $resourceName)
                            <li>
                                <a
                                    href="{{ route('app.dashboard', ['resource' => $resourceName]) }}"
                                    class="{{ $resource === $resourceName ? 'active' : '' }}"
                                >
                                    {{ ucfirst($resourceName) }} ({{ count($resources[$resourceName]['rows']) }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </aside>

                <div class="dashboard-grid" style="gap: 24px;">
                    <section class="table-card">
                        <div class="section-title">
                            <div>
                                <p style="margin-bottom: 4px;">Recurs actiu</p>
                                <h2>{{ ucfirst($resource) }}</h2>
                            </div>
                            <a href="{{ route('app.dashboard', ['resource' => $resource]) }}" class="button button-teal">Nou registre</a>
                        </div>

                        <table>
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
                                            <div class="actions">
                                                <a href="{{ route('app.dashboard', ['resource' => $resource, 'edit' => $row['id']]) }}">Editar</a>
                                                <form method="POST" action="{{ route('resources.destroy', ['resource' => $resource, 'id' => $row['id']]) }}" class="inline-form">
                                                    @csrf
                                                    <button type="submit" class="button button-light">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($fields) + 1 }}">No hi ha registres.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </section>

                    <section class="form-card">
                        <div class="section-title">
                            <div>
                                <p style="margin-bottom: 4px;">Editor</p>
                                <h3>{{ $editing ? 'Editar registre' : 'Nou registre' }}</h3>
                            </div>
                            @if ($editing)
                                <a href="{{ route('app.dashboard', ['resource' => $resource]) }}" class="button button-light">Cancel·lar</a>
                            @endif
                        </div>

                        <form method="POST" action="{{ $editing
                            ? route('resources.update', ['resource' => $resource, 'id' => $editing['id']])
                            : route('resources.store', ['resource' => $resource]) }}" class="stack">
                            @csrf
                            @foreach ($fields as $field)
                                <div>
                                    <label>{{ ucfirst($field) }}</label>
                                    <input
                                        name="{{ $field }}"
                                        type="text"
                                        value="{{ old($field, $editing[$field] ?? '') }}"
                                    >
                                </div>
                            @endforeach
                            <button type="submit" class="button {{ $editing ? 'button-teal' : 'button-warm' }}">
                                {{ $editing ? 'Guardar canvis' : 'Crear registre' }}
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endif
</body>
</html>
