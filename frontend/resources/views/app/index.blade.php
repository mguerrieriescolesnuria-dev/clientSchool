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
            grid-template-columns: 1.35fr 0.95fr;
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

        .button-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 18px;
            background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
            color: var(--ink);
            border: 1px solid rgba(95, 111, 137, 0.2);
            box-shadow: 0 14px 30px rgba(30, 41, 59, 0.08);
        }

        .button-google:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(30, 41, 59, 0.12);
        }

        .button-google svg {
            width: 20px;
            height: 20px;
            flex: none;
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

        .message.is-temporary {
            animation: fade-away 4.8s ease forwards;
        }

        @keyframes fade-away {
            0%, 70% {
                opacity: 1;
                transform: translateY(0);
                max-height: 120px;
                margin-bottom: 16px;
            }

            100% {
                opacity: 0;
                transform: translateY(-8px);
                max-height: 0;
                margin-bottom: 0;
                padding-top: 0;
                padding-bottom: 0;
                border-width: 0;
            }
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

        .oauth-panel {
            padding: 28px;
        }

        .oauth-panel h2 {
            font-size: 34px;
            letter-spacing: -0.04em;
            margin-bottom: 12px;
        }

        .oauth-panel p {
            margin-bottom: 0;
        }

        .oauth-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 12px;
        }

        .oauth-list li {
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.85);
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .oauth-list strong {
            display: block;
            margin-bottom: 4px;
            color: var(--ink);
            font-size: 15px;
        }

        .oauth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: var(--teal-dark);
            font-size: 13px;
            font-weight: 700;
        }

        .oauth-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #10b981;
        }

        .oauth-note {
            font-size: 13px;
            color: var(--muted);
            margin-top: 8px;
        }

        .page.is-loading {
            opacity: 0.72;
            transition: opacity 0.18s ease;
        }

        .page-shell {
            transition: opacity 0.2s ease, transform 0.2s ease;
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
                <p>He preparat aquest client Laravel perquè es connecti a la meva API REST de students, teachers i subjects.</p>
                <p>També he afegit accés amb Google OAuth perquè el client pugui treballar amb els endpoints protegits del backend.</p>
                <div class="api-box">
                    API configurada a <strong>{{ $apiBaseUrl }}</strong>
                </div>
            </section>

            <aside class="panel oauth-panel">
                <span class="oauth-badge">OAuth amb Google actiu</span>
                <h2>Entra al client amb el teu compte de Google</h2>

                @if ($errors->any())
                    <div class="message error">{{ $errors->first() }}</div>
                @endif

                <p>El login bàsic ja no es mostra a la portada. Ara l’entrada principal es fa amb Google perquè el dashboard pugui usar directament l’API protegida.</p>

                <div style="margin: 22px 0 18px;">
                    <a href="{{ route('auth.google') }}" class="button button-google">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-.8 2.4-1.8 3.2l2.9 2.2c1.7-1.5 2.6-3.9 2.6-6.6 0-.6-.1-1.2-.2-1.7H12z"/>
                            <path fill="#34A853" d="M12 21c2.4 0 4.4-.8 5.9-2.2l-2.9-2.2c-.8.6-1.8 1-3 1-2.3 0-4.2-1.5-4.9-3.6l-3 .2v2.3C5.7 19.3 8.6 21 12 21z"/>
                            <path fill="#4A90E2" d="M7.1 14c-.2-.6-.3-1.3-.3-2s.1-1.4.3-2V7.7l-3-.2C3.4 8.9 3 10.4 3 12s.4 3.1 1.1 4.5l3-.5V14z"/>
                            <path fill="#FBBC05" d="M12 6.4c1.3 0 2.5.4 3.5 1.3l2.6-2.6C16.4 3.7 14.4 3 12 3 8.6 3 5.7 4.7 4.1 7.5l3 2.3c.7-2.1 2.6-3.4 4.9-3.4z"/>
                        </svg>
                        Entrar amb Google
                    </a>
                </div>

                <ul class="oauth-list">
                    <li>
                        <strong>Accés directe al dashboard</strong>
                        Quan Google valida la sessió, el client rep el token de l’API i entra al dashboard automàticament.
                    </li>
                    <li>
                        <strong>CRUD protegit</strong>
                        Els formularis de students, teachers i subjects ja queden autoritzats per crear, editar i eliminar.
                    </li>
                    <li>
                        <strong>Sessió més clara</strong>
                        Tot queda unificat: entres una vegada i ja treballes amb el client i el backend alhora.
                    </li>
                </ul>

                <p class="oauth-note">Si Google mostra un avís de prova, és normal en entorn local mentre el projecte està en mode de desenvolupament.</p>
            </aside>
        </div>
    </div>
@else
    <div class="page page-shell" data-dashboard-shell>
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
                <div class="message success is-temporary">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            @if (!$hasApiToken)
                <div class="message error">
                    Has entrat amb login bàsic del client. Per crear, editar o eliminar registres al backend protegit, entra també amb Google OAuth.
                    <a href="{{ route('auth.google') }}" style="font-weight: 700; color: inherit; margin-left: 6px;">Entrar amb Google</a>
                </div>
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
                                    data-spa-link
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
                            @if ($hasApiToken)
                                <a href="{{ route('app.dashboard', ['resource' => $resource]) }}" class="button button-teal" data-spa-link>Nou registre</a>
                            @endif
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
                                                @if ($hasApiToken)
                                                    <a href="{{ route('app.dashboard', ['resource' => $resource, 'edit' => $row['id']]) }}" data-spa-link>Editar</a>
                                                    <form method="POST" action="{{ route('resources.destroy', ['resource' => $resource, 'id' => $row['id']]) }}" class="inline-form" data-spa-form>
                                                        @csrf
                                                        <button type="submit" class="button button-light">Eliminar</button>
                                                    </form>
                                                @else
                                                    <span style="color: var(--muted); font-size: 13px;">Només lectura</span>
                                                @endif
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

                    @if ($hasApiToken)
                    <section class="form-card">
                        <div class="section-title">
                            <div>
                                <p style="margin-bottom: 4px;">Editor</p>
                                <h3>{{ $editing ? 'Editar registre' : 'Nou registre' }}</h3>
                            </div>
                            @if ($editing)
                                <a href="{{ route('app.dashboard', ['resource' => $resource]) }}" class="button button-light" data-spa-link>Cancel·lar</a>
                            @endif
                        </div>

                        <form method="POST" action="{{ $editing
                            ? route('resources.update', ['resource' => $resource, 'id' => $editing['id']])
                            : route('resources.store', ['resource' => $resource]) }}" class="stack" data-spa-form>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    (() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const getShell = () => document.querySelector('[data-dashboard-shell]');

        const hideTemporaryMessages = (scope = document) => {
            scope.querySelectorAll('.message.is-temporary').forEach((element) => {
                window.setTimeout(() => {
                    element.style.display = 'none';
                }, 5000);
            });
        };

        const replaceShell = (html, url, pushState = true) => {
            const parser = new DOMParser();
            const nextDocument = parser.parseFromString(html, 'text/html');
            const currentShell = getShell();
            const nextShell = nextDocument.querySelector('[data-dashboard-shell]');

            if (!currentShell || !nextShell) {
                window.location.assign(url);
                return;
            }

            currentShell.replaceWith(nextShell);
            document.title = nextDocument.title || document.title;

            if (pushState) {
                window.history.pushState({}, '', url);
            }

            hideTemporaryMessages(nextShell);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const loadPage = async (url, options = {}, pushState = true) => {
            const shell = getShell();

            if (!shell) {
                window.location.assign(url);
                return;
            }

            shell.classList.add('is-loading');

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(options.headers ?? {}),
                    },
                    credentials: 'same-origin',
                    method: options.method ?? 'GET',
                    body: options.body ?? undefined,
                });

                const html = await response.text();

                if (!response.ok) {
                    window.location.assign(url);
                    return;
                }

                replaceShell(html, url, pushState);
            } catch (error) {
                window.location.assign(url);
            } finally {
                const activeShell = getShell();
                if (activeShell) {
                    activeShell.classList.remove('is-loading');
                }
            }
        };

        document.addEventListener('click', (event) => {
            const link = event.target.closest('[data-spa-link]');

            if (!link || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return;
            }

            event.preventDefault();
            loadPage(link.href);
        });

        document.addEventListener('submit', (event) => {
            const form = event.target.closest('[data-spa-form]');

            if (!form) {
                return;
            }

            event.preventDefault();

            const formData = new FormData(form);

            if (csrfToken && !formData.has('_token')) {
                formData.append('_token', csrfToken);
            }

            loadPage(form.action, {
                method: form.method || 'POST',
                body: formData,
            });
        });

        window.addEventListener('popstate', () => {
            if (getShell()) {
                loadPage(window.location.href, {}, false);
            }
        });

        hideTemporaryMessages();
    })();
</script>
</body>
</html>
