@extends('layouts.app', ['class' => 'bg-default login-page'])

@section('content')
    <style>
        body.login-page {
            background: #f3f6fa;
        }
        body.login-page .main-content {
            min-height: 100vh;
            background: transparent;
        }
        body.login-page .navbar-top {
            background: #179BAE !important;
        }
        body.login-page .navbar-top {
            display: none !important;
        }
        body.login-page footer {
            display: none;
        }
        body.login-page .navbar-top .navbar-brand img {
            max-height: 36px;
            width: auto;
        }
        .lims-auth {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2.25rem 0;
            overflow: hidden;
        }
        .lims-auth__particles {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .lims-auth__overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(900px 420px at 15% 25%, rgba(23, 155, 174, .14), rgba(23, 155, 174, 0) 62%),
                radial-gradient(780px 380px at 85% 70%, rgba(23, 155, 174, .10), rgba(23, 155, 174, 0) 62%),
                linear-gradient(180deg, rgba(255, 255, 255, .25), rgba(255, 255, 255, .00));
        }
        .lims-auth::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(900px 420px at 15% 25%, rgba(23, 155, 174, .12), rgba(23, 155, 174, 0) 62%),
                radial-gradient(780px 380px at 85% 70%, rgba(23, 155, 174, .10), rgba(23, 155, 174, 0) 62%),
                repeating-linear-gradient(0deg, rgba(31, 42, 55, .035) 0, rgba(31, 42, 55, .035) 1px, transparent 1px, transparent 28px),
                repeating-linear-gradient(90deg, rgba(31, 42, 55, .035) 0, rgba(31, 42, 55, .035) 1px, transparent 1px, transparent 28px);
            opacity: .9;
        }
        .lims-auth::after {
            content: '';
            position: absolute;
            z-index: 0;
            pointer-events: none;
            width: 820px;
            height: 820px;
            left: -420px;
            top: -520px;
            background: radial-gradient(circle at 35% 35%, rgba(23, 155, 174, .18), rgba(23, 155, 174, 0) 60%);
        }
        @media (max-width: 575.98px) {
            .lims-auth {
                min-height: 100vh;
                padding: 1.75rem 0;
            }
        }
        .lims-auth__card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .08);
            overflow: hidden;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, .58);
            border: 1px solid rgba(255, 255, 255, .70);
            -webkit-backdrop-filter: blur(16px);
            backdrop-filter: blur(16px);
        }
        .lims-auth__topbar {
            height: 8px;
            background: #179BAE;
        }
        .lims-auth__title {
            font-weight: 700;
            color: #2b2f33;
            margin: 0;
            font-size: 18px;
        }
        .lims-auth__subtitle {
            color: #6b7785;
            margin: .25rem 0 0;
            font-size: 12px;
        }
        .lims-auth__badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            background: rgba(23, 155, 174, .10);
            color: #0b6f7b;
            font-weight: 600;
            font-size: 11px;
        }
        .lims-auth .input-group-text {
            background: #fff;
            border-color: #dfe6ee;
            color: #5b6570;
            padding: .7rem .9rem;
        }
        .lims-auth .form-control {
            border-color: #dfe6ee;
            height: 44px;
            padding: .7rem .95rem;
            font-size: 12px !important;
        }
        .lims-auth .form-control::placeholder {
            color: #9aa6b2;
        }
        .lims-auth .form-control:focus {
            border-color: rgba(23, 155, 174, .65);
            box-shadow: 0 0 0 .2rem rgba(23, 155, 174, .14);
        }
        .lims-auth__toggle {
            cursor: pointer;
            background: #fff;
            border-color: #dfe6ee;
            color: #5b6570;
        }
        .lims-auth__toggle:hover {
            color: #179BAE;
        }
        .lims-auth__btn {
            background: #179BAE;
            border-color: #179BAE;
            border-radius: 10px;
            font-weight: 700;
            padding: .7rem 1.2rem;
            box-shadow: 0 12px 22px rgba(23, 155, 174, .22);
        }
        .lims-auth__btn:hover {
            background: #138a9a;
            border-color: #138a9a;
        }
        .lims-auth__link {
            color: #179BAE;
            font-weight: 600;
            font-size: 12px;
        }
        .lims-auth__link:hover {
            color: #138a9a;
        }
        .lims-auth__error {
            font-size: 11px;
        }
        .lims-auth__left {
            background: linear-gradient(180deg, rgba(23, 155, 174, .10), rgba(255, 255, 255, 0));
            position: relative;
        }
        .lims-auth__left::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(520px 260px at 20% 20%, rgba(23, 155, 174, .20), rgba(23, 155, 174, 0) 60%),
                radial-gradient(420px 240px at 85% 55%, rgba(23, 155, 174, .12), rgba(23, 155, 174, 0) 62%);
            opacity: .9;
        }
        .lims-auth__left h2 {
            margin: 0;
            font-weight: 800;
            color: #1f2a37;
            font-size: 22px;
            letter-spacing: .2px;
        }
        .lims-auth__left p {
            margin: .5rem 0 0;
            color: #5b6570;
            font-size: 12px;
            line-height: 1.6;
        }
        .lims-auth__steps {
            list-style: none;
            padding: 0;
            margin: 1.25rem 0 0;
        }
        .lims-auth__steps li {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .5rem 0;
            color: #2f3b47;
            font-size: 12px;
        }
        .lims-auth__steps .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(23, 155, 174, .95);
            box-shadow: 0 0 0 4px rgba(23, 155, 174, .12);
            margin-top: .25rem;
            flex: 0 0 auto;
        }
        .lims-auth__steps .text {
            color: #2f3b47;
        }
        .lims-auth__steps .muted {
            display: block;
            color: #6b7785;
            font-size: 11px;
            line-height: 1.45;
            margin-top: .15rem;
        }
    </style>

    <div class="lims-auth">
        <canvas id="lims-particles" class="lims-auth__particles" aria-hidden="true"></canvas>
        <div class="lims-auth__overlay" aria-hidden="true"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-11 col-lg-9 col-xl-8">
                    <div class="card lims-auth__card">
                        <div class="lims-auth__topbar"></div>
                        <div class="row no-gutters">
                            <div class="col-md-5 d-none d-md-flex lims-auth__left">
                                <div class="p-4 d-flex flex-column justify-content-between w-100" style="position: relative; z-index: 1;">
                                    <div>
                                        <span class="lims-auth__badge">
                                         <img src="{{ asset('') }}/icon_white.png" style="width: 120px !important; height:auto" />
                                        </span>
                                        <div class="mt-3">
                                            <h2>{{ __('LIMS') }}</h2>
                                            <p>{{ __('Masuk untuk akses pencatatan inspeksi, verifikasi, dan pelaporan hasil uji dengan tampilan yang konsisten dan rapi.') }}</p>
                                        </div>

                                        <ul class="lims-auth__steps">
                                            <li>
                                                <span class="dot"></span>
                                                <span class="text">
                                                    {{ __('Inspeksi material') }}
                                                    <span class="muted">{{ __('Pencatatan hasil inspeksi dan data batch') }}</span>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="dot"></span>
                                                <span class="text">
                                                    {{ __('Sampling & uji lab') }}
                                                    <span class="muted">{{ __('Input parameter uji, lampiran, dan hasil analisa') }}</span>
                                                </span>
                                            </li>
                                            <li>
                                                <span class="dot"></span>
                                                <span class="text">
                                                    {{ __('Verifikasi & approval') }}
                                                    <span class="muted">{{ __('Alur verifikasi untuk ketertelusuran dan audit') }}</span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        {{ config('app.name', 'LIMS') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="p-4 p-lg-5">
                                    <div class="mb-4">
                                        <p class="lims-auth__title">{{ __('Masuk') }}</p>
                                        <p class="lims-auth__subtitle">{{ __('Gunakan NIK dan kata sandi Anda') }}</p>
                                    </div>

                                    <form role="form" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="form-group{{ $errors->has('nik') ? ' has-danger' : '' }} mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                </div>
                                                <input class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" placeholder="{{ __('NIK') }}" type="text" name="nik" value="{{ old('nik') }}" required autofocus autocomplete="username">
                                            </div>
                                            @if ($errors->has('nik'))
                                                <span class="invalid-feedback lims-auth__error" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('nik') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }} mb-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                </div>
                                                <input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Kata sandi') }}" type="password" required autocomplete="current-password">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn lims-auth__toggle input-group-text js-toggle-password" aria-label="{{ __('Tampilkan kata sandi') }}">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback lims-auth__error" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-3 mb-4">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="lims-auth__link">{{ __('Lupa kata sandi?') }}</a>
                                            @else
                                                <span></span>
                                            @endif

                                            <button type="submit" class="btn btn-primary lims-auth__btn">{{ __('Masuk') }}</button>
                                        </div>

                                        <div class="text-center text-muted" style="font-size: 11px;">
                                            {{ __('Jika mengalami kendala, hubungi admin.') }}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        (function () {
            function prefersReducedMotion() {
                try {
                    return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                } catch (e) {
                    return false;
                }
            }

            function initParticles() {
                var canvas = document.getElementById('lims-particles');
                if (!canvas) return;
                var ctx = canvas.getContext('2d', { alpha: true });
                if (!ctx) return;

                var dpr = Math.min(2, window.devicePixelRatio || 1);
                var w = 0;
                var h = 0;
                var raf = null;
                var points = [];
                var mouse = { x: -9999, y: -9999, active: false };

                function resize() {
                    var rect = canvas.getBoundingClientRect();
                    w = Math.max(1, Math.floor(rect.width * dpr));
                    h = Math.max(1, Math.floor(rect.height * dpr));
                    canvas.width = w;
                    canvas.height = h;

                    var target = Math.max(44, Math.min(110, Math.floor((w * h) / 24000)));
                    points = [];
                    for (var i = 0; i < target; i++) {
                        points.push({
                            x: Math.random() * w,
                            y: Math.random() * h,
                            vx: (Math.random() - 0.5) * 0.42 * dpr,
                            vy: (Math.random() - 0.5) * 0.42 * dpr,
                            r: (1.1 + Math.random() * 1.4) * dpr,
                            a: 0.55 + Math.random() * 0.35,
                        });
                    }
                }

                function onMouseMove(e) {
                    var rect = canvas.getBoundingClientRect();
                    mouse.x = (e.clientX - rect.left) * dpr;
                    mouse.y = (e.clientY - rect.top) * dpr;
                    mouse.active = true;
                }

                function onMouseLeave() {
                    mouse.active = false;
                    mouse.x = -9999;
                    mouse.y = -9999;
                }

                function step() {
                    var maxDist = 150 * dpr;
                    var maxDist2 = maxDist * maxDist;

                    ctx.clearRect(0, 0, w, h);

                    for (var i = 0; i < points.length; i++) {
                        var p = points[i];

                        if (mouse.active) {
                            var dxm = p.x - mouse.x;
                            var dym = p.y - mouse.y;
                            var dm2 = dxm * dxm + dym * dym;
                            var hover = 140 * dpr;
                            if (dm2 < hover * hover) {
                                var dm = Math.sqrt(dm2) || 1;
                                var push = (1 - dm / hover) * 0.18 * dpr;
                                p.vx += (dxm / dm) * push;
                                p.vy += (dym / dm) * push;
                            }
                        }

                        p.x += p.vx;
                        p.y += p.vy;

                        var limit = 0.7 * dpr;
                        if (p.vx > limit) p.vx = limit;
                        if (p.vx < -limit) p.vx = -limit;
                        if (p.vy > limit) p.vy = limit;
                        if (p.vy < -limit) p.vy = -limit;

                        if (p.x < -maxDist) p.x = w + maxDist;
                        if (p.x > w + maxDist) p.x = -maxDist;
                        if (p.y < -maxDist) p.y = h + maxDist;
                        if (p.y > h + maxDist) p.y = -maxDist;
                    }

                    ctx.lineWidth = 1 * dpr;
                    ctx.lineCap = 'round';

                    for (var a = 0; a < points.length; a++) {
                        for (var b = a + 1; b < points.length; b++) {
                            var pa = points[a];
                            var pb = points[b];
                            var dx = pa.x - pb.x;
                            var dy = pa.y - pb.y;
                            var dist2 = dx * dx + dy * dy;
                            if (dist2 > maxDist2) continue;

                            var dist = Math.sqrt(dist2) || 1;
                            var strength = 1 - dist / maxDist;
                            var alpha = Math.min(0.18, Math.max(0, 0.10 * strength));
                            ctx.strokeStyle = 'rgba(23, 155, 174,' + alpha + ')';
                            ctx.beginPath();
                            ctx.moveTo(pa.x, pa.y);
                            ctx.lineTo(pb.x, pb.y);
                            ctx.stroke();
                        }
                    }

                    for (var k = 0; k < points.length; k++) {
                        var pt = points[k];
                        ctx.globalAlpha = pt.a;
                        ctx.fillStyle = 'rgba(23, 155, 174, 0.9)';
                        ctx.beginPath();
                        ctx.arc(pt.x, pt.y, pt.r, 0, Math.PI * 2);
                        ctx.fill();
                    }
                    ctx.globalAlpha = 1;

                    raf = requestAnimationFrame(step);
                }

                function stop() {
                    if (raf) cancelAnimationFrame(raf);
                    raf = null;
                }

                resize();

                window.addEventListener('resize', function () {
                    stop();
                    resize();
                    if (!prefersReducedMotion()) step();
                }, { passive: true });
                window.addEventListener('mousemove', onMouseMove, { passive: true });
                window.addEventListener('mouseleave', onMouseLeave, { passive: true });
                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) {
                        stop();
                    } else if (!prefersReducedMotion()) {
                        stop();
                        step();
                    }
                });

                if (!prefersReducedMotion()) step();
            }

            function toggle(input, button) {
                if (!input) return;
                var nextType = input.type === 'password' ? 'text' : 'password';
                input.type = nextType;
                if (!button) return;
                var icon = button.querySelector('i');
                if (!icon) return;
                icon.classList.remove('fa-eye', 'fa-eye-slash');
                icon.classList.add(nextType === 'password' ? 'fa-eye' : 'fa-eye-slash');
            }

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.js-toggle-password');
                if (!btn) return;
                var group = btn.closest('.input-group');
                var input = group ? group.querySelector('input[type="password"], input[type="text"]') : null;
                toggle(input, btn);
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initParticles, { once: true });
            } else {
                initParticles();
            }
        })();
    </script>
@endpush
