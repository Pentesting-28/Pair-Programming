<x-layouts::clean :title="__('Seleccionar Arquitectura')">

    <style>
        /* ── Keyframes ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes floatBadge {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-2px); }
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%      { opacity: .4; }
        }

        .modes-header { animation: fadeInDown .65s cubic-bezier(.22,1,.36,1) both; }
        .modes-card   { animation: fadeInUp  .55s cubic-bezier(.22,1,.36,1) both; }
        .modes-card:nth-child(1) { animation-delay: .06s; }
        .modes-card:nth-child(2) { animation-delay: .14s; }
        .modes-card:nth-child(3) { animation-delay: .22s; }
        .modes-card:nth-child(4) { animation-delay: .30s; }
        .modes-info { animation: fadeInUp .65s cubic-bezier(.22,1,.36,1) .45s both; }

        /* ── Grid: 4 columns ── */
        .modes-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            width: 100%;
        }
        @media (max-width: 900px) {
            .modes-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 520px) {
            .modes-grid { grid-template-columns: 1fr; }
        }

        /* ── Card ── */
        .mode-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.35rem 1rem 1.15rem;
            border-radius: 1.1rem;
            overflow: hidden;
            text-decoration: none !important;
            transition: transform .32s cubic-bezier(.22,1,.36,1),
                        box-shadow .32s cubic-bezier(.22,1,.36,1),
                        border-color .32s ease;
            /* Light theme */
            background: #fff;
            border: 1px solid rgba(228,228,231,.7);
            color: inherit;
        }
        .dark .mode-card {
            background: rgba(39,39,42,.55);
            border-color: rgba(63,63,70,.5);
        }

        .mode-card--active:hover {
            transform: translateY(-5px) scale(1.02);
        }
        .mode-card--active:hover {
            box-shadow: 0 14px 36px -8px rgba(0,0,0,.08);
        }
        .dark .mode-card--active:hover {
            box-shadow: 0 14px 36px -8px rgba(0,0,0,.4);
        }

        /* ── Icon ── */
        .mode-icon {
            width: 2.75rem;
            height: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            margin-bottom: .7rem;
            transition: background .3s ease, color .3s ease, transform .3s ease;
        }
        .mode-card--active:hover .mode-icon {
            transform: scale(1.12) rotate(-3deg);
        }

        /* ── CTA ── */
        .mode-cta {
            width: 100%;
            padding: .45rem 0;
            border-radius: .6rem;
            font-size: .7rem;
            font-weight: 600;
            text-align: center;
            margin-top: auto;
            transition: background .28s ease, box-shadow .28s ease, transform .18s ease;
        }
        .mode-cta:active { transform: scale(.97); }

        /* ── Ribbon ── */
        .modes-ribbon {
            position: absolute;
            top: .55rem;
            right: -1.8rem;
            transform: rotate(45deg);
            padding: .15rem 2rem;
            font-size: .5rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #fff;
            z-index: 10;
            animation: floatBadge 2.8s ease-in-out infinite;
        }

        /* ── Shimmer ── */
        .modes-shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(161,161,170,.06) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        /* ── Title ── */
        .mode-title {
            font-size: .8rem;
            font-weight: 700;
            margin: 0 0 .25rem;
            color: #18181b;
        }
        .dark .mode-title { color: #fafafa; }

        .mode-title--muted { color: #a1a1aa; }
        .dark .mode-title--muted { color: #71717a; }

        /* ── Description ── */
        .mode-desc {
            font-size: .7rem;
            text-align: center;
            margin: 0 0 .9rem;
            line-height: 1.5;
            color: #71717a;
        }
        .dark .mode-desc { color: #a1a1aa; }

        .mode-desc--muted {
            color: rgba(161,161,170,.6);
            font-style: italic;
        }
        .dark .mode-desc--muted {
            color: rgba(161,161,170,.45);
        }

        /* ── Status dot ── */
        .mode-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            display: inline-block;
        }
        .mode-dot--pulse { animation: pulse-dot 1.5s ease-in-out infinite; }

        /* ── CTA themes ── */
        .mode-cta--active {
            background: #18181b;
            color: #fff;
        }
        .dark .mode-cta--active {
            background: rgba(250,250,250,.12);
            color: #fafafa;
        }
        .mode-cta--active:hover { background: var(--cta-hover, #18181b); }

        .mode-cta--disabled {
            border: 1px solid rgba(228,228,231,.5);
            background: rgba(244,244,245,.6);
            color: #a1a1aa;
        }
        .dark .mode-cta--disabled {
            border-color: rgba(63,63,70,.4);
            background: rgba(39,39,42,.4);
            color: #52525b;
        }

        /* ── Disabled card ── */
        .mode-card--disabled {
            opacity: .65;
            cursor: not-allowed;
            user-select: none;
        }

        /* ── Header badge ── */
        .modes-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .75rem;
            border-radius: 9999px;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: 1rem;
            background: rgba(99,102,241,.08);
            color: #6366f1;
        }
        .dark .modes-badge {
            background: rgba(99,102,241,.15);
            color: #a5b4fc;
        }

        /* ── Main heading ── */
        .modes-heading {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -.02em;
            margin: 0 0 .5rem;
            color: #18181b;
        }
        .dark .modes-heading { color: #fafafa; }

        /* ── Subheading ── */
        .modes-subheading {
            font-size: .85rem;
            max-width: 32rem;
            margin: 0 auto;
            line-height: 1.6;
            color: #71717a;
        }
        .dark .modes-subheading { color: #a1a1aa; }

        /* ── Info bar ── */
        .modes-info-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem .85rem;
            border-radius: 9999px;
            font-size: .7rem;
            font-weight: 500;
            letter-spacing: .02em;
            background: rgba(244,244,245,.7);
            border: 1px solid rgba(228,228,231,.5);
            color: #a1a1aa;
        }
        .dark .modes-info-pill {
            background: rgba(39,39,42,.5);
            border-color: rgba(63,63,70,.4);
            color: #71717a;
        }

        /* ── Card border hover themes ── */
        .mode-card--blue:hover  { border-color: rgba(96,165,250,.5) !important; }
        .mode-card--purple:hover { border-color: rgba(168,85,247,.45) !important; }

        .dark .mode-card--blue:hover  { border-color: rgba(96,165,250,.35) !important; }
        .dark .mode-card--purple:hover { border-color: rgba(168,85,247,.3) !important; }

        /* ── Icon backgrounds ── */
        .mode-icon--blue   { background: rgba(59,130,246,.08); color: #3b82f6; }
        .mode-icon--purple { background: rgba(147,51,234,.08); color: #9333ea; }
        .mode-icon--cyan   { background: rgba(6,182,212,.06);  color: #06b6d4; border: 1px solid rgba(6,182,212,.1); }
        .mode-icon--green  { background: rgba(16,185,129,.06); color: #10b981; border: 1px solid rgba(16,185,129,.1); }

        .dark .mode-icon--blue   { background: rgba(59,130,246,.12); color: #60a5fa; }
        .dark .mode-icon--purple { background: rgba(147,51,234,.12); color: #c084fc; }
        .dark .mode-icon--cyan   { background: rgba(6,182,212,.10);  color: #22d3ee; border-color: rgba(6,182,212,.15); }
        .dark .mode-icon--green  { background: rgba(16,185,129,.10); color: #34d399; border-color: rgba(16,185,129,.15); }

        /* ── Status label colors ── */
        .mode-status--blue   { color: #2563eb; }
        .mode-status--purple { color: #7c3aed; }
        .mode-status--cyan   { color: rgba(6,182,212,.55); }
        .mode-status--green  { color: rgba(16,185,129,.55); }

        .dark .mode-status--blue   { color: #60a5fa; }
        .dark .mode-status--purple { color: #c084fc; }
        .dark .mode-status--cyan   { color: rgba(34,211,238,.5); }
        .dark .mode-status--green  { color: rgba(52,211,153,.5); }
    </style>

    {{-- ── Auth/Profile Header ── --}}
    <div style="position:fixed;top:0;right:0;padding:1.5rem;z-index:50;">
        @auth
            <flux:dropdown position="bottom" align="end">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                        />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                                data-test="logout-button"
                            >
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        @else
            <div style="display:flex;align-items:center;gap:1rem;">
                <flux:button :href="route('login')" variant="ghost" size="sm">Log in</flux:button>
                @if (Route::has('register'))
                    <flux:button :href="route('register')" variant="primary" size="sm">Register</flux:button>
                @endif
            </div>
        @endauth
    </div>

    {{-- ── Main Content ── --}}
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:80vh;padding:3rem 1.5rem;">
        @auth
            <div style="max-width:62rem;width:100%;text-align:center;">

                {{-- Header --}}
                <div class="modes-header" style="margin-bottom:2.5rem;">
                    <div class="modes-badge">
                        <flux:icon name="cpu-chip" class="size-3" />
                        Multi-Arquitectura
                    </div>
                    <h1 class="modes-heading">¡Bienvenido al Ecosistema de Desarrollo!</h1>
                    <p class="modes-subheading">Selecciona la arquitectura base para gestionar la plataforma.</p>
                </div>

                {{-- ▬▬ 4-Column Grid ▬▬ --}}
                <div class="modes-grid">

                    {{-- ▸ MVC ─ Blue --}}
                    <a href="{{ route('switch.mode', 'mvc') }}" class="modes-card mode-card mode-card--active mode-card--blue">
                        <div class="mode-icon mode-icon--blue">
                            <flux:icon name="circle-stack" class="size-5" />
                        </div>
                        <h3 class="mode-title">Classic MVC</h3>
                        <div style="display:flex;align-items:center;gap:.3rem;margin-bottom:.6rem;">
                            <span class="mode-dot mode-dot--pulse" style="background:#3b82f6;"></span>
                            <span class="mode-status--blue" style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Disponible</span>
                        </div>
                        <p class="mode-desc">Blade y Controladores.<br>SEO y simplicidad.</p>
                        <div class="mode-cta mode-cta--active" style="--cta-hover:#2563eb;box-shadow:0 4px 12px rgba(59,130,246,.12);">
                            Activar MVC
                        </div>
                    </a>

                    {{-- ▸ Livewire ─ Purple --}}
                    <a href="{{ route('switch.mode', 'livewire') }}" class="modes-card mode-card mode-card--active mode-card--purple">
                        <div class="mode-icon mode-icon--purple">
                            <flux:icon name="bolt" class="size-5" />
                        </div>
                        <h3 class="mode-title">Livewire 4</h3>
                        <div style="display:flex;align-items:center;gap:.3rem;margin-bottom:.6rem;">
                            <span class="mode-dot mode-dot--pulse" style="background:#a855f7;"></span>
                            <span class="mode-status--purple" style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Disponible</span>
                        </div>
                        <p class="mode-desc">SPA reactiva sin JS.<br>Componentes en PHP.</p>
                        <div class="mode-cta mode-cta--active" style="--cta-hover:#7c3aed;box-shadow:0 4px 12px rgba(147,51,234,.12);">
                            Activar Livewire
                        </div>
                    </a>

                    {{-- ▸ React + Inertia ─ Cyan (Próximamente) --}}
                    <div class="modes-card mode-card mode-card--disabled">
                        <div class="modes-ribbon" style="background:#06b6d4;box-shadow:0 2px 8px rgba(6,182,212,.2);">Próximamente</div>
                        <div class="mode-icon mode-icon--cyan">
                            <svg class="size-5" viewBox="-11.5 -10.232 23 20.463" fill="currentColor"><circle r="2.05"/><g stroke="currentColor" fill="none" stroke-width="1"><ellipse rx="11" ry="4.2"/><ellipse rx="11" ry="4.2" transform="rotate(60)"/><ellipse rx="11" ry="4.2" transform="rotate(120)"/></g></svg>
                        </div>
                        <h3 class="mode-title mode-title--muted">React + Inertia</h3>
                        <div style="display:flex;align-items:center;gap:.3rem;margin-bottom:.6rem;">
                            <span class="mode-dot" style="background:rgba(6,182,212,.45);"></span>
                            <span class="mode-status--cyan" style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">En Desarrollo</span>
                        </div>
                        <p class="mode-desc mode-desc--muted">SPA con React y<br>Laravel vía Inertia.</p>
                        <div class="mode-cta mode-cta--disabled modes-shimmer">
                            Próximamente...
                        </div>
                    </div>

                    {{-- ▸ Vue 3 + Vite ─ Emerald (Próximamente) --}}
                    <div class="modes-card mode-card mode-card--disabled">
                        <div class="modes-ribbon" style="background:#10b981;box-shadow:0 2px 8px rgba(16,185,129,.2);">Próximamente</div>
                        <div class="mode-icon mode-icon--green">
                            <svg class="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M2 3h3.5L12 15l6.5-12H22L12 21 2 3zm4.5 0H10l2 3.67L14 3h3.5L12 13.5 6.5 3z"/></svg>
                        </div>
                        <h3 class="mode-title mode-title--muted">Vue 3 + Vite</h3>
                        <div style="display:flex;align-items:center;gap:.3rem;margin-bottom:.6rem;">
                            <span class="mode-dot" style="background:rgba(16,185,129,.45);"></span>
                            <span class="mode-status--green" style="font-size:.55rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">En Desarrollo</span>
                        </div>
                        <p class="mode-desc mode-desc--muted">Ecosistema Vue.js<br>moderno y ultra rápido.</p>
                        <div class="mode-cta mode-cta--disabled modes-shimmer">
                            Próximamente...
                        </div>
                    </div>
                </div>

                {{-- Info bar --}}
                <div class="modes-info" style="display:flex;justify-content:center;margin-top:1.5rem;">
                    <div class="modes-info-pill">
                        <flux:icon name="information-circle" class="size-3.5" />
                        <span>La arquitectura se puede alternar en caliente desde el menú lateral.</span>
                    </div>
                </div>
            </div>
        @else
            {{-- ── Public: Book Catalog ── --}}
            <div style="max-width:62rem;width:100%;margin:0 auto;">
                <div class="modes-header" style="text-align:center;margin-bottom:2.5rem;">
                    <div class="modes-badge" style="background:rgba(245,158,11,.08);color:#d97706;">
                        <flux:icon name="book-open" class="size-3" />
                        Catálogo Público
                    </div>
                    <h1 class="modes-heading">Biblioteca Central</h1>
                    <p class="modes-subheading">Explora nuestro catálogo público. Inicia sesión para gestionar el contenido.</p>
                </div>

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;">
                    @php
                        $books = \App\Models\Book::with('autor')->latest()->take(6)->get();
                    @endphp

                    @forelse($books as $book)
                        <flux:card class="flex flex-col h-full bg-white dark:bg-zinc-900 shadow-sm border-zinc-100 dark:border-zinc-800 hover:shadow-md transition-shadow duration-300">
                            <div class="flex-1">
                                <flux:heading size="lg" class="mb-1">{{ $book->titulo }}</flux:heading>
                                <flux:text variant="subtle" class="mb-4">Por {{ $book->autor->nombre_completo }}</flux:text>
                                <flux:text size="sm" class="line-clamp-3">
                                    {{ $book->descripcion ?? 'Parte de nuestra colección histórica.' }}
                                </flux:text>
                            </div>
                            <div class="mt-5 pt-5 border-t border-zinc-50 dark:border-zinc-800 flex justify-between items-center">
                                <flux:badge variant="subtle" size="sm">{{ $book->año_publicacion }}</flux:badge>
                                <flux:button variant="ghost" size="sm">Ficha Completa</flux:button>
                            </div>
                        </flux:card>
                    @empty
                        <div style="grid-column:1/-1;padding:3rem 0;">
                            <flux:card class="text-center p-12 bg-zinc-50 dark:bg-zinc-900/50 border-dashed">
                                <flux:icon name="book-open" class="size-12 mx-auto text-zinc-300 mb-4" />
                                <flux:heading size="lg">Catálogo Vacío</flux:heading>
                                <flux:text>Todavía no se han registrado libros en la base de datos.</flux:text>
                            </flux:card>
                        </div>
                    @endforelse
                </div>

                <div style="margin-top:3rem;border-radius:1rem;padding:1.75rem;text-align:center;background:rgba(239,246,255,.06);border:1px solid rgba(59,130,246,.1);">
                    <h2 class="modes-heading" style="font-size:1.1rem;margin-bottom:.35rem;">¿Eres Administrador?</h2>
                    <p class="modes-subheading" style="margin-bottom:1.25rem;">Inicia sesión para acceder a los paneles de gestión.</p>
                    <div style="display:flex;justify-content:center;gap:1rem;">
                        <flux:button :href="route('login')" variant="primary">Iniciar Sesión</flux:button>
                        <flux:button :href="route('register')" variant="ghost">Crear Cuenta</flux:button>
                    </div>
                </div>
            </div>
        @endauth
    </div>

</x-layouts::clean>
