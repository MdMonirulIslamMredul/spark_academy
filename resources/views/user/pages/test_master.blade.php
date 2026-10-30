
@php
$company_info = DB::table('company_settings')->first();
$userID = Cookie::get('user_id');
$userName = Cookie::get('user_name');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Instant Theme Detection Script (Prevents White Flash) -->
    <script>
        (function() {
            var theme = localStorage.getItem('app_theme');
            if (theme === 'dark' || (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark-theme');
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark-theme');
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{optional($company_info)->company_name}}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(optional($company_info)->logo) }}">

    <!-- CSS
	============================================ -->
    @include('user.pages.inc.style')
    @stack('frontend_style')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/dark_mode.css') }}">
</head>

<body class="rbt-header-sticky">

    @include('user.pages.inc.header')

    <!-- Spark Academy Floating Support Widget (WhatsApp, Call, Messenger) -->
    <style>
        .spark-floating-support {
            position: fixed;
            bottom: 30px;
            right: 22px;
            top: auto;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            width: 48px;
            margin: 0;
            padding: 0;
            pointer-events: auto;
        }
        .spark-support-btn {
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            min-height: 48px !important;
            max-width: 48px !important;
            max-height: 48px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            color: #ffffff !important;
            font-size: 20px !important;
            line-height: 1 !important;
            text-decoration: none !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.22);
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        .spark-support-btn:hover {
            transform: scale(1.12);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.32);
            color: #ffffff !important;
        }
        .spark-support-btn i {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            font-size: 20px !important;
            margin: 0 !important;
            padding: 0 !important;
            top: 0 !important;
            position: static !important;
        }
        .spark-support-btn .support-label {
            position: absolute;
            right: 58px;
            background: #071126;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .spark-support-btn:hover .support-label {
            opacity: 1;
            visibility: visible;
            right: 54px;
        }
        .spark-btn-whatsapp {
            background: #25D366 !important;
        }
        .spark-btn-call {
            background: #ff7a00 !important;
        }
        .spark-btn-messenger {
            background: #0084FF !important;
        }
        @media (max-width: 768px) {
            .spark-floating-support {
                top: auto;
                bottom: 24px;
                right: 14px;
                width: 44px;
                gap: 10px;
            }
            .spark-support-btn {
                width: 44px !important;
                height: 44px !important;
                min-width: 44px !important;
                min-height: 44px !important;
                font-size: 18px !important;
            }
            .spark-support-btn i {
                font-size: 18px !important;
            }
        }
    </style>
    @php
        // Dynamic Support Channels from company_settings
        $callPhone = !empty($company_info->phone_1) ? $company_info->phone_1 : '01768240303';
        $cleanCall = preg_replace('/[^0-9]/', '', $callPhone) ?: '01768240303';

        $waNumber = !empty($company_info->whatsapp_number) ? $company_info->whatsapp_number : $callPhone;
        $cleanWa = preg_replace('/[^0-9]/', '', $waNumber) ?: '01768240303';
        if (strpos($cleanWa, '88') !== 0) {
            $cleanWa = '88' . ltrim($cleanWa, '0');
        }

        $messengerLink = !empty($company_info->messenger_link) ? $company_info->messenger_link : null;
        if (empty($messengerLink) && !empty($company_info->facebook)) {
            $fbUrl = trim($company_info->facebook);
            $parsed = parse_url($fbUrl);
            $path = trim($parsed['path'] ?? '', '/');
            if (!empty($path)) {
                $segments = explode('/', $path);
                $handle = end($segments);
                if (!empty($handle)) {
                    $messengerLink = 'https://m.me/' . $handle;
                }
            }
        }
        if (empty($messengerLink)) {
            $messengerLink = 'https://m.me/sparkacademyofficial';
        }
    @endphp
    <div class="spark-floating-support" aria-label="Support Contact Widget">
        <a href="https://wa.me/{{ $cleanWa }}" target="_blank" rel="noopener" class="spark-support-btn spark-btn-whatsapp" title="WhatsApp: {{ $waNumber }}">
            <i class="fab fa-whatsapp"></i>
            <span class="support-label">হোয়াটসঅ্যাপ ({{ $waNumber }})</span>
        </a>
        <a href="tel:{{ $cleanCall }}" class="spark-support-btn spark-btn-call" title="Call: {{ $callPhone }}">
            <i class="feather-phone-call"></i>
            <span class="support-label">সরাসরি কল ({{ $callPhone }})</span>
        </a>
        <a href="{{ $messengerLink }}" target="_blank" rel="noopener" class="spark-support-btn spark-btn-messenger" title="Messenger">
            <i class="fab fa-facebook-messenger"></i>
            <span class="support-label">মেসেঞ্জার পেইজ</span>
        </a>
    </div>

    @yield('content')

    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>
    <!-- Start Footer aera -->
    @include('user.pages.inc.footer')

    <!-- JS
============================================ -->
    @include('user.pages.inc.script')
    @stack('frontend_script')
</body>

</html>
