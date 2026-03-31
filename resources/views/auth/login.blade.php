@extends('layouts.app', ['class' => 'bg-default login-page'])

@section('content')
    <style>
        body.login-page {
            background: radial-gradient(1200px 600px at 15% 20%, rgba(23, 155, 174, .25), rgba(13, 18, 28, 0) 55%),
                        radial-gradient(900px 500px at 85% 30%, rgba(43, 110, 255, .18), rgba(13, 18, 28, 0) 55%),
                        linear-gradient(180deg, #0b1020 0%, #070b14 100%);
            min-height: 100vh;
        }
        body.login-page .navbar-top {
            background: transparent !important;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 30;
        }
        body.login-page footer {
            display: none;
        }
        .qc-auth {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .qc-auth__particles {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .qc-auth__overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background:
                radial-gradient(900px 500px at 18% 85%, rgba(23, 155, 174, .20), rgba(0, 0, 0, 0) 55%),
                radial-gradient(900px 500px at 82% 15%, rgba(255, 255, 255, .06), rgba(0, 0, 0, 0) 60%),
                linear-gradient(180deg, rgba(7, 11, 20, .70), rgba(7, 11, 20, .82));
        }
        .qc-auth__content {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 6rem 0 3.5rem;
        }
        .qc-shell {
            border-radius: 26px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 30px 90px rgba(0, 0, 0, .55);
            background: rgba(255, 255, 255, .06);
            -webkit-backdrop-filter: blur(18px);
            backdrop-filter: blur(18px);
        }
        .qc-shell__grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            min-height: 520px;
        }
        @media (max-width: 991.98px) {
            .qc-shell__grid {
                grid-template-columns: 1fr;
            }
        }
        .qc-brand {
            padding: 2.25rem 2.25rem 2rem;
            background:
                radial-gradient(700px 420px at 18% 22%, rgba(23, 155, 174, .32), rgba(23, 155, 174, 0) 62%),
                radial-gradient(520px 320px at 80% 60%, rgba(43, 110, 255, .18), rgba(43, 110, 255, 0) 62%),
                linear-gradient(180deg, rgba(255, 255, 255, .06), rgba(255, 255, 255, .02));
            border-right: 1px solid rgba(255, 255, 255, .10);
        }
        @media (max-width: 991.98px) {
            .qc-brand {
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, .10);
            }
        }
        .qc-brand__top {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-bottom: 1.5rem;
        }
        .qc-brand__mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .16);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .25);
        }
        .qc-brand__mark i {
            color: #8ff2ff;
            font-size: 18px;
        }
        .qc-brand__title {
            color: rgba(255, 255, 255, .96);
            font-weight: 700;
            letter-spacing: .2px;
            margin: 0;
            font-size: 18px;
        }
        .qc-brand__subtitle {
            color: rgba(255, 255, 255, .72);
            margin: 0;
            font-size: 12px;
        }
        .qc-brand__headline {
            color: rgba(255, 255, 255, .96);
            font-weight: 700;
            margin: 0 0 .6rem;
            font-size: 28px;
            line-height: 1.2;
        }
        .qc-brand__desc {
            color: rgba(255, 255, 255, .74);
            margin: 0 0 1.25rem;
            font-size: 12px;
            line-height: 1.7;
        }
        .qc-highlights {
            display: grid;
            grid-template-columns: 1fr;
            gap: .65rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .qc-highlights li {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .7rem .85rem;
            border-radius: 14px;
            background: rgba(0, 0, 0, .18);
            border: 1px solid rgba(255, 255, 255, .10);
        }
        .qc-highlights .qc-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(23, 155, 174, .9);
            box-shadow: 0 0 0 4px rgba(23, 155, 174, .20);
        }
        .qc-highlights .qc-hl-text {
            color: rgba(255, 255, 255, .78);
            font-size: 11px;
            line-height: 1.4;
        }
        .qc-form {
            padding: 2.25rem 2.25rem 2rem;
        }
        .qc-card {
            border-radius: 22px !important;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .05);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .35);
            -webkit-backdrop-filter: blur(18px);
            backdrop-filter: blur(18px);
        }
        .qc-card .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            background: transparent;
        }
        .qc-form__title {
            color: rgba(255, 255, 255, .92);
            font-weight: 700;
            margin: 0;
            font-size: 18px;
        }
        .qc-form__hint {
            color: rgba(255, 255, 255, .65);
            margin: .25rem 0 0;
            font-size: 11px;
        }
        .qc-input .input-group-text {
            background: rgba(0, 0, 0, .18) !important;
            border: 1px solid rgba(255, 255, 255, .12) !important;
            color: rgba(255, 255, 255, .82) !important;
        }
        .qc-input .form-control {
            background: rgba(0, 0, 0, .18) !important;
            border: 1px solid rgba(255, 255, 255, .12) !important;
            color: rgba(255, 255, 255, .92) !important;
            height: 38px;
            font-size: 12px !important;
        }
        .qc-input .form-control::placeholder {
            color: rgba(255, 255, 255, .60);
        }
        .qc-input .form-control:focus {
            border-color: rgba(143, 242, 255, .65) !important;
            box-shadow: 0 0 0 .2rem rgba(23, 155, 174, .18) !important;
        }
        .qc-btn {
            border-radius: 14px;
            font-weight: 700;
            letter-spacing: .2px;
            padding: .65rem 1rem;
            box-shadow: 0 14px 30px rgba(23, 155, 174, .24);
        }
        .qc-links a {
            color: rgba(255, 255, 255, .78);
        }
        .qc-links a:hover {
            color: rgba(143, 242, 255, .95);
        }
        .qc-invalid {
            color: rgba(255, 175, 195, .95);
        }
    </style>

    <div class="qc-auth">
        <canvas id="qc-particles" class="qc-auth__particles" aria-hidden="true"></canvas>
        <div class="qc-auth__overlay" aria-hidden="true"></div>

        <div class="qc-auth__content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-9">
                        <div class="qc-shell">
                            <div class="qc-shell__grid">
                                <div class="qc-brand">
                                    <div class="qc-brand__top">
                                        <div class="qc-brand__mark">
                                            <i class="ni ni-atom"></i>
                                        </div>
                                        <div>
                                            <p class="qc-brand__title">{{ config('app.name', 'LIMS QC') }}</p>
                                            <p class="qc-brand__subtitle">{{ __('Quality Control Laboratory System') }}</p>
                                        </div>
                                    </div>

                                    <h1 class="qc-brand__headline">{{ __('Sign in to continue') }}</h1>
                                    <p class="qc-brand__desc">
                                        {{ __('Secure access to QC workflows, traceability, and lab-ready reporting. Designed for reliability, clarity, and compliance.') }}
                                    </p>

                                    <ul class="qc-highlights">
                                        <li>
                                            <span class="qc-dot"></span>
                                            <span class="qc-hl-text">{{ __('Role-based access and audit-ready sessions') }}</span>
                                        </li>
                                        <li>
                                            <span class="qc-dot"></span>
                                            <span class="qc-hl-text">{{ __('Fast data entry with consistent validation') }}</span>
                                        </li>
                                        <li>
                                            <span class="qc-dot"></span>
                                            <span class="qc-hl-text">{{ __('Modern UI built for daily QC operations') }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="qc-form d-flex align-items-center">
                                    <div class="w-100">
                                        <div class="card qc-card shadow border-0">
                                            <div class="card-header py-4">
                                                <p class="qc-form__title">{{ __('Login') }}</p>
                                                <p class="qc-form__hint">{{ __('Enter your NIK and password') }}</p>
                                            </div>
                                            <div class="card-body px-lg-5 py-lg-4">
                                                <form role="form" method="POST" action="{{ route('login') }}">
                                                    @csrf

                                                    <div class="form-group qc-input{{ $errors->has('nik') ? ' has-danger' : '' }} mb-3">
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                            </div>
                                                            <input class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" placeholder="{{ __('NIK') }}" type="text" name="nik" value="{{ old('nik') }}" required autofocus autocomplete="username">
                                                        </div>
                                                        @if ($errors->has('nik'))
                                                            <span class="invalid-feedback qc-invalid" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('nik') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group qc-input{{ $errors->has('password') ? ' has-danger' : '' }}">
                                                        <div class="input-group input-group-alternative">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                            </div>
                                                            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required autocomplete="current-password">
                                                        </div>
                                                        @if ($errors->has('password'))
                                                            <span class="invalid-feedback qc-invalid" style="display: block;" role="alert">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mt-4 qc-links">
                                                        @if (Route::has('password.request'))
                                                            <a href="{{ route('password.request') }}" class="small">{{ __('Forgot password?') }}</a>
                                                        @else
                                                            <span></span>
                                                        @endif

                                                        <button type="submit" class="btn btn-primary qc-btn">{{ __('Sign in') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-center qc-links">
                                            <small class="text-white-50">{{ __('Having trouble? Contact your QC admin.') }}</small>
                                        </div>
                                    </div>
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
    <script src="{{ asset('js/particles-lab.js') }}"></script>
@endpush
