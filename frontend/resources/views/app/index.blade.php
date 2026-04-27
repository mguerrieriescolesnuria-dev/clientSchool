<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>clientSchool Frontend</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div
        id="app"
        class="min-h-screen"
        data-authenticated="{{ $user ? 'true' : 'false' }}"
        data-user='@json($user ? ["name" => $user->name, "email" => $user->email, "avatar" => $user->avatar] : null)'
    >
        @if (!$user)
            <main class="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-10">
                <section class="grid w-full gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="fade-up rounded-[2rem] border border-white/50 bg-white/55 p-8 shadow-2xl shadow-amber-950/10 backdrop-blur lg:p-12">
                        <p class="mb-4 inline-flex rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700">
                            DAW M0613 · Client SPA en Laravel
                        </p>
                        <h1 class="max-w-2xl text-4xl font-bold tracking-tight text-slate-900 lg:text-6xl">
                            ClientSchool Frontend
                        </h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                            Client SPA amb Laravel que es connecta a la teva API REST de students, teachers i subjects.
                            L'accés queda protegit amb OAuth de GitHub.
                        </p>
                        <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                            <a href="{{ route('oauth.github.redirect') }}" class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-6 py-3 font-semibold text-white transition hover:bg-amber-700">
                                Entrar amb GitHub
                            </a>
                            <div class="rounded-2xl border border-slate-200 bg-white/80 px-6 py-3 text-sm text-slate-600">
                                API configurada a <code>{{ $apiBaseUrl }}</code>
                            </div>
                        </div>
                    </div>

                    <aside class="fade-up glass-panel rounded-[2rem] border border-white/60 p-8">
                        <h2 class="text-lg font-semibold text-slate-900">Què inclou</h2>
                        <ul class="mt-6 space-y-4 text-slate-600">
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">OAuth amb GitHub via Socialite</li>
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">CRUD de students, teachers i subjects</li>
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">Proxy Laravel cap al backend existent</li>
                            <li class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">SPA simple amb JavaScript natiu</li>
                        </ul>
                    </aside>
                </section>
            </main>
        @else
            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <section class="fade-up glass-panel overflow-hidden rounded-[2rem] border border-white/50">
                    <div class="border-b border-slate-200/80 px-6 py-5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] text-slate-500">ClientSchool</p>
                                <h1 class="mt-2 text-3xl font-bold text-slate-900">Panell del client SPA</h1>
                                <p class="mt-2 text-slate-600">Gestiona els recursos del backend sense tocar la Part 1.</p>
                            </div>
                            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                                <div class="rounded-2xl border border-slate-200 bg-white/75 px-4 py-3 text-sm">
                                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-slate-500">{{ $user->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                                        Sortir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[260px_1fr]">
                        <aside class="space-y-4">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white/70 p-4">
                                <p class="text-sm font-medium text-slate-500">Recursos</p>
                                <div id="resource-nav" class="mt-4 space-y-2"></div>
                            </div>
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white/70 p-4">
                                <p class="text-sm font-medium text-slate-500">API</p>
                                <p class="mt-2 break-all text-sm text-slate-700">{{ $apiBaseUrl }}</p>
                            </div>
                        </aside>

                        <section class="space-y-6">
                            <div id="alert-box" class="hidden rounded-2xl border px-4 py-3 text-sm"></div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/80 p-5">
                                    <p class="text-sm text-slate-500">Students</p>
                                    <p id="students-count" class="mt-3 text-3xl font-bold text-slate-900">0</p>
                                </div>
                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/80 p-5">
                                    <p class="text-sm text-slate-500">Teachers</p>
                                    <p id="teachers-count" class="mt-3 text-3xl font-bold text-slate-900">0</p>
                                </div>
                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/80 p-5">
                                    <p class="text-sm text-slate-500">Subjects</p>
                                    <p id="subjects-count" class="mt-3 text-3xl font-bold text-slate-900">0</p>
                                </div>
                            </div>

                            <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/80 p-5">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p id="resource-label" class="text-sm uppercase tracking-[0.25em] text-slate-500"></p>
                                            <h2 id="resource-title" class="mt-2 text-2xl font-bold text-slate-900"></h2>
                                        </div>
                                        <button id="create-button" type="button" class="rounded-2xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-800">
                                            Nou registre
                                        </button>
                                    </div>
                                    <div id="loading-box" class="mt-6 hidden rounded-2xl bg-slate-100 px-4 py-3 text-sm text-slate-600">
                                        Carregant dades...
                                    </div>
                                    <div class="mt-6 overflow-x-auto">
                                        <table class="min-w-full text-left text-sm">
                                            <thead id="table-head" class="border-b border-slate-200 text-slate-500"></thead>
                                            <tbody id="table-body" class="divide-y divide-slate-200/80"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="rounded-[1.5rem] border border-slate-200 bg-white/80 p-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Editor</p>
                                            <h3 id="form-title" class="mt-2 text-xl font-bold text-slate-900">Nou registre</h3>
                                        </div>
                                        <button id="reset-button" type="button" class="text-sm font-medium text-slate-500 hover:text-slate-700">
                                            Netejar
                                        </button>
                                    </div>
                                    <form id="resource-form" class="mt-6 space-y-4"></form>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </main>
        @endif
    </div>
</body>
</html>
