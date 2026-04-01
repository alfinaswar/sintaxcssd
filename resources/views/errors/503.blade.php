<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 – Sistem Sedang Maintenance | SINTA-APP</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green-primary: #1a7a4a;
            --green-dark: #0f5433;
            --green-light: #e8f5ee;
            --green-accent: #25a565;
            --blue-light: #d6eaf8;
            --sky: #c8dff0;
            --text-dark: #0d2b1a;
            --text-muted: #5a7a68;
            --white: #ffffff;
        }

        html,
        body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sky);
            color: var(--text-dark);
            overflow: hidden;
        }

        /* Animated sky background */
        body {
            background: linear-gradient(180deg, #b8d4e8 0%, #cde4f0 40%, #daedf8 100%);
            position: relative;
        }

        /* Floating clouds */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 50px;
            animation: floatCloud linear infinite;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: inherit;
            border-radius: 50%;
        }

        .cloud-1 {
            width: 140px;
            height: 45px;
            top: 8%;
            left: -160px;
            animation-duration: 28s;
            animation-delay: 0s;
        }

        .cloud-1::before {
            width: 70px;
            height: 70px;
            top: -35px;
            left: 20px;
        }

        .cloud-1::after {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 65px;
        }

        .cloud-2 {
            width: 100px;
            height: 35px;
            top: 18%;
            left: -120px;
            animation-duration: 35s;
            animation-delay: -12s;
            opacity: 0.7;
        }

        .cloud-2::before {
            width: 55px;
            height: 55px;
            top: -28px;
            left: 15px;
        }

        .cloud-2::after {
            width: 40px;
            height: 40px;
            top: -20px;
            left: 48px;
        }

        .cloud-3 {
            width: 180px;
            height: 55px;
            top: 5%;
            left: -200px;
            animation-duration: 42s;
            animation-delay: -20s;
            opacity: 0.6;
        }

        .cloud-3::before {
            width: 90px;
            height: 90px;
            top: -45px;
            left: 30px;
        }

        .cloud-3::after {
            width: 65px;
            height: 65px;
            top: -32px;
            left: 85px;
        }

        .cloud-4 {
            width: 120px;
            height: 38px;
            top: 25%;
            left: -140px;
            animation-duration: 30s;
            animation-delay: -8s;
            opacity: 0.75;
        }

        .cloud-4::before {
            width: 65px;
            height: 65px;
            top: -32px;
            left: 20px;
        }

        .cloud-4::after {
            width: 45px;
            height: 45px;
            top: -22px;
            left: 58px;
        }

        @keyframes floatCloud {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(100vw + 250px));
            }
        }

        /* Ground strip */
        .ground {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 90px;
            background: linear-gradient(180deg, #8bc34a 0%, #689f38 100%);
            z-index: 1;
        }

        .ground::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            width: 100%;
            height: 8px;
            background: #a5d56a;
            border-radius: 0 0 0 0;
        }

        /* Road */
        .road {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background: #5a6472;
            z-index: 2;
        }

        .road::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 3px;
            background: repeating-linear-gradient(90deg, #f0c040 0, #f0c040 40px, transparent 40px, transparent 80px);
            transform: translateY(-50%);
        }

        /* Main layout */
        .page {
            position: relative;
            z-index: 10;
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        /* Left panel – login-style card */
        .panel-left {
            width: 420px;
            min-width: 380px;
            background: var(--white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 44px;
            position: relative;
            z-index: 20;
            box-shadow: 4px 0 32px rgba(0, 0, 0, 0.08);
        }

        /* Logo */
        .logo-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid var(--green-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            background: white;
        }

        .logo-cross {
            width: 52px;
            height: 52px;
            position: relative;
        }

        .logo-cross::before,
        .logo-cross::after {
            content: '';
            position: absolute;
            background: var(--green-primary);
            border-radius: 3px;
        }

        .logo-cross::before {
            width: 14px;
            height: 52px;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .logo-cross::after {
            width: 52px;
            height: 14px;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo-inner {
            position: absolute;
            width: 22px;
            height: 22px;
            background: #e53935;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        /* Error code badge */
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff3e0;
            border: 1.5px solid #ffb74d;
            color: #e65100;
            border-radius: 30px;
            padding: 6px 18px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 24px;
        }

        .error-badge .dot {
            width: 8px;
            height: 8px;
            background: #ff6f00;
            border-radius: 50%;
            animation: blink 1.2s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        /* Heading */
        .panel-left h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            line-height: 1.35;
            margin-bottom: 12px;
        }

        .panel-left p {
            font-size: 14px;
            color: var(--text-muted);
            text-align: center;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        /* Maintenance card */
        .maintenance-card {
            width: 100%;
            background: var(--green-light);
            border: 1.5px solid #b2dfcc;
            border-radius: 14px;
            padding: 22px 20px;
            margin-bottom: 28px;
        }

        .maintenance-card .row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .maintenance-card .icon-wrap {
            width: 42px;
            height: 42px;
            background: var(--green-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .maintenance-card .icon-wrap svg {
            width: 22px;
            height: 22px;
            fill: white;
        }

        .maintenance-card .text strong {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 4px;
        }

        .maintenance-card .text span {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Progress bar */
        .progress-wrap {
            width: 100%;
            margin-bottom: 28px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e0ede6;
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--green-accent), var(--green-primary));
            border-radius: 99px;
            animation: fillBar 3s ease forwards;
        }

        @keyframes fillBar {
            to {
                width: 68%;
            }
        }

        /* Back btn */
        .btn-back {
            width: 100%;
            padding: 14px;
            background: var(--green-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-back:hover {
            background: var(--green-dark);
        }

        .btn-back:active {
            transform: scale(0.98);
        }

        /* Footer label */
        .panel-footer {
            position: absolute;
            bottom: 20px;
            font-size: 11.5px;
            color: #aac4b5;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Right panel – branding */
        .panel-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 60px 120px;
            position: relative;
        }

        .brand-box {
            display: inline-block;
            border: 3px solid var(--text-dark);
            border-radius: 50px;
            padding: 8px 32px;
            font-size: 18px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: 1px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(4px);
        }

        .brand-title {
            font-size: 44px;
            font-weight: 800;
            color: #0d3340;
            text-align: center;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        /* Maintenance gear animation */
        .gear-anim {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .gear {
            animation: spin 4s linear infinite;
        }

        .gear-reverse {
            animation: spin 3s linear infinite reverse;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, 0.8);
            border-radius: 40px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            color: #0d3340;
        }

        .status-pill .pulse {
            width: 10px;
            height: 10px;
            background: #ff6f00;
            border-radius: 50%;
            position: relative;
        }

        .status-pill .pulse::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid #ff6f00;
            animation: ripple 1.5s ease-out infinite;
        }

        @keyframes ripple {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(1.8);
            }
        }

        /* Buildings row - same style as original */
        .buildings {
            position: fixed;
            bottom: 48px;
            left: 420px;
            right: 0;
            height: 160px;
            z-index: 5;
            overflow: hidden;
        }

        .buildings img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: bottom;
        }

        /* Simple CSS buildings as fallback */
        .building-row {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: flex-end;
            gap: 6px;
            padding: 0 20px;
        }

        .bld {
            border-radius: 3px 3px 0 0;
            flex-shrink: 0;
        }

        /* Warning tape decorative */
        .tape {
            position: fixed;
            top: 0;
            left: 420px;
            right: 0;
            height: 10px;
            background: repeating-linear-gradient(45deg,
                    #f0c040 0px, #f0c040 16px,
                    #1a1a1a 16px, #1a1a1a 32px);
            z-index: 20;
            opacity: 0.85;
        }

        @media (max-width: 768px) {
            .panel-left {
                width: 100%;
                min-width: unset;
            }

            .panel-right {
                display: none;
            }

            .buildings {
                left: 0;
            }

            .tape {
                left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Clouds -->
    <div class="clouds">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
        <div class="cloud cloud-4"></div>
    </div>

    <!-- Warning tape top -->
    <div class="tape"></div>

    <!-- Ground & road -->
    <div class="ground"></div>
    <div class="road"></div>

    <!-- Buildings (simple CSS) -->
    <div class="buildings">
        <div class="building-row">
            <!-- Buildings replicated with CSS shapes matching original style -->
            <div class="bld" style="width:50px;height:90px;background:#e8c87a;"></div>
            <div class="bld" style="width:70px;height:110px;background:#f0d08a;"></div>
            <div class="bld" style="width:90px;height:140px;background:#b0c8d8;"></div>
            <div class="bld" style="width:60px;height:80px;background:#c8d8a0;"></div>
            <div class="bld" style="width:110px;height:120px;background:#d8e8f0;"></div>
            <div class="bld" style="width:40px;height:70px;background:#f8d8a8;"></div>
            <div class="bld" style="width:80px;height:130px;background:#c0d0e0;"></div>
            <div class="bld" style="width:55px;height:90px;background:#e0c8b0;"></div>
            <div class="bld" style="width:95px;height:115px;background:#a8c8d8;"></div>
            <div class="bld" style="width:45px;height:75px;background:#d8c890;"></div>
            <div class="bld" style="width:75px;height:100px;background:#c8e0c8;"></div>
            <div class="bld" style="width:85px;height:125px;background:#b8d0e8;"></div>
            <div class="bld" style="width:60px;height:85px;background:#f0d8a0;"></div>
            <div class="bld" style="width:100px;height:140px;background:#c8d8e8;"></div>
            <div class="bld" style="width:50px;height:80px;background:#e8c890;"></div>
        </div>
    </div>

    <!-- Page layout -->
    <div class="page">

        <!-- LEFT: Error Card -->
        <div class="panel-left">

            <!-- Logo -->
            <div class="logo-wrap">
                <img src="{{ asset('assets/img/logo-rs.png') }}" alt="SINTA-APP Logo"
                    style="max-width: 90px; display: block; margin: 0 auto;">
            </div>

            <!-- Error badge -->
            <div class="error-badge">
                <span class="dot"></span>
                ERROR 503
            </div>

            <h1>Sistem Sedang<br>dalam Maintenance</h1>

            <p>
                Mohon maaf atas ketidaknyamanannya.<br>
                SINTA-APP sedang dalam proses pemeliharaan.<br>
                Kami akan segera kembali.
            </p>

            <!-- Info card -->
            <div class="maintenance-card">
                <div class="row">
                    <div class="icon-wrap">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.7 2.3a.7.7 0 00-.7.7v3a3 3 0 003 3h3a.7.7 0 000-1.4h-3a1.6 1.6 0 01-1.6-1.6V3a.7.7 0 00-.7-.7zM4 7a3 3 0 013-3h4.3v2A3.7 3.7 0 0015 9.7H17V17a3 3 0 01-3 3H7a3 3 0 01-3-3V7zm7 5a1 1 0 10-2 0 1 1 0 002 0zm3 0a1 1 0 10-2 0 1 1 0 002 0zm-6 4a1 1 0 10-2 0 1 1 0 002 0zm3 0a1 1 0 10-2 0 1 1 0 002 0zm3 0a1 1 0 10-2 0 1 1 0 002 0z" />
                        </svg>
                    </div>
                    <div class="text">
                        <strong>Pemeliharaan Sistem Terjadwal</strong>
                        <span>Tim teknis kami sedang melakukan pembaruan dan peningkatan sistem. Harap tunggu beberapa
                            saat.</span>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="progress-wrap">
                <div class="progress-label">
                    <span>Progress Maintenance</span>
                    <span id="pct">68%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <!-- Back button -->
            <button class="btn-back" onclick="window.location.reload()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 .49-4.44"></path>
                </svg>
                Coba Lagi
            </button>

            <div class="panel-footer">© SINTA-APP – Sistem Informasi Inventaris</div>
        </div>

        <!-- RIGHT: Branding -->
        <div class="panel-right">
            <div class="brand-box">SINTA-APP</div>
            <div class="brand-title">Sistem Informasi Inventaris</div>

            <!-- Animated gears -->
            <div class="gear-anim">
                <svg class="gear" width="52" height="52" viewBox="0 0 24 24" fill="#0f5433" opacity="0.75">
                    <path
                        d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.36.07-.72.07-1.08s-.03-.71-.07-1.08l2.32-1.82c.21-.16.26-.45.13-.69l-2.2-3.81a.537.537 0 00-.65-.24l-2.74 1.1c-.57-.44-1.19-.8-1.87-1.07l-.42-2.92A.54.54 0 0014 2h-4c-.27 0-.5.19-.54.46l-.42 2.92c-.68.27-1.3.63-1.87 1.07l-2.74-1.1a.54.54 0 00-.65.24L1.58 9.4c-.14.23-.09.53.13.69l2.32 1.82c-.04.37-.07.73-.07 1.09s.03.71.07 1.08L1.71 15.9c-.22.16-.27.46-.13.69l2.2 3.81c.13.24.41.32.65.24l2.74-1.1c.57.44 1.19.8 1.87 1.07l.42 2.92c.04.27.27.47.54.47h4c.27 0 .5-.2.54-.46l.42-2.92c.68-.27 1.3-.63 1.87-1.07l2.74 1.1c.24.08.52 0 .65-.24l2.2-3.81c.14-.23.08-.53-.13-.69l-2.32-1.82z" />
                </svg>
                <svg class="gear-reverse" width="36" height="36" viewBox="0 0 24 24" fill="#25a565"
                    opacity="0.75">
                    <path
                        d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.36.07-.72.07-1.08s-.03-.71-.07-1.08l2.32-1.82c.21-.16.26-.45.13-.69l-2.2-3.81a.537.537 0 00-.65-.24l-2.74 1.1c-.57-.44-1.19-.8-1.87-1.07l-.42-2.92A.54.54 0 0014 2h-4c-.27 0-.5.19-.54.46l-.42 2.92c-.68.27-1.3.63-1.87 1.07l-2.74-1.1a.54.54 0 00-.65.24L1.58 9.4c-.14.23-.09.53.13.69l2.32 1.82c-.04.37-.07.73-.07 1.09s.03.71.07 1.08L1.71 15.9c-.22.16-.27.46-.13.69l2.2 3.81c.13.24.41.32.65.24l2.74-1.1c.57.44 1.19.8 1.87 1.07l.42 2.92c.04.27.27.47.54.47h4c.27 0 .5-.2.54-.46l.42-2.92c.68-.27 1.3-.63 1.87-1.07l2.74 1.1c.24.08.52 0 .65-.24l2.2-3.81c.14-.23.08-.53-.13-.69l-2.32-1.82z" />
                </svg>
                <svg class="gear" width="44" height="44" viewBox="0 0 24 24" fill="#0f5433" opacity="0.6">
                    <path
                        d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.36.07-.72.07-1.08s-.03-.71-.07-1.08l2.32-1.82c.21-.16.26-.45.13-.69l-2.2-3.81a.537.537 0 00-.65-.24l-2.74 1.1c-.57-.44-1.19-.8-1.87-1.07l-.42-2.92A.54.54 0 0014 2h-4c-.27 0-.5.19-.54.46l-.42 2.92c-.68.27-1.3.63-1.87 1.07l-2.74-1.1a.54.54 0 00-.65.24L1.58 9.4c-.14.23-.09.53.13.69l2.32 1.82c-.04.37-.07.73-.07 1.09s.03.71.07 1.08L1.71 15.9c-.22.16-.27.46-.13.69l2.2 3.81c.13.24.41.32.65.24l2.74-1.1c.57.44 1.19.8 1.87 1.07l.42 2.92c.04.27.27.47.54.47h4c.27 0 .5-.2.54-.46l.42-2.92c.68-.27 1.3-.63 1.87-1.07l2.74 1.1c.24.08.52 0 .65-.24l2.2-3.81c.14-.23.08-.53-.13-.69l-2.32-1.82z" />
                </svg>
            </div>

            <!-- Status pill -->
            <div class="status-pill">
                <div class="pulse"></div>
                Maintenance sedang berlangsung…
            </div>
        </div>

    </div>

</body>

</html>
