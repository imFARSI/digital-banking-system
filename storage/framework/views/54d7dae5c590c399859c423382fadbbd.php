<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finexa — Secure Digital Banking</title>
    <meta name="description" content="Finexa is a modern digital banking platform offering secure transfers, loans, savings, and rewards.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #06101f;
            color: #fff;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .finexa-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(6, 16, 31, 0.85);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .finexa-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #00d4ff;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .finexa-logo i { margin-right: 8px; }
        .nav-links a {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: #fff; }
        .btn-nav-login {
            background: transparent;
            border: 1px solid rgba(0,212,255,0.4);
            color: #00d4ff !important;
            border-radius: 6px;
            padding: 0.4rem 1.2rem;
            transition: all 0.2s !important;
        }
        .btn-nav-login:hover {
            background: rgba(0,212,255,0.1) !important;
            border-color: #00d4ff !important;
        }
        .btn-nav-register {
            background: #00d4ff;
            color: #06101f !important;
            border-radius: 6px;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            transition: all 0.2s !important;
        }
        .btn-nav-register:hover {
            background: #00b8d9 !important;
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 7rem 0 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero-bg-glow {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 700px;
            background: radial-gradient(ellipse at center, rgba(0,212,255,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0,212,255,0.1);
            border: 1px solid rgba(0,212,255,0.3);
            border-radius: 100px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            color: #00d4ff;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.6s ease both;
        }
        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.7s ease 0.1s both;
        }
        .hero-title span { color: #00d4ff; }
        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.65);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.7s ease 0.2s both;
        }
        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.7s ease 0.3s both;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, #00d4ff, #0099cc);
            color: #06101f;
            font-weight: 700;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(0,212,255,0.3);
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,212,255,0.45);
            color: #06101f;
        }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            font-weight: 600;
            padding: 0.85rem 2rem;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.25s;
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.4);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── HERO CARD VISUAL ── */
        .hero-card-visual {
            position: relative;
            animation: fadeInRight 0.8s ease 0.2s both;
        }
        .bank-card {
            background: linear-gradient(135deg, #0A2540 0%, #1a3a5c 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative;
            overflow: hidden;
        }
        .bank-card::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(0,212,255,0.07);
        }
        .bank-card-chip {
            width: 45px; height: 35px;
            background: linear-gradient(135deg, #f0c040, #d4a017);
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .bank-card-number {
            font-size: 1.2rem;
            letter-spacing: 3px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .bank-card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .bank-card-name { font-size: 0.8rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; }
        .bank-card-balance { font-size: 1.4rem; font-weight: 700; color: #00d4ff; }
        .floating-stat {
            position: absolute;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 0.8rem 1.2rem;
            font-size: 0.85rem;
        }
        .floating-stat-1 { top: -20px; right: -30px; }
        .floating-stat-2 { bottom: 20px; left: -30px; }
        .stat-label { color: rgba(255,255,255,0.5); font-size: 0.7rem; text-transform: uppercase; }
        .stat-value { color: #00d4ff; font-weight: 700; font-size: 1rem; }

        /* ── FEATURES ── */
        .features-section {
            padding: 6rem 0;
            background: rgba(255,255,255,0.02);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .section-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #00d4ff;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }
        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .section-sub { color: rgba(255,255,255,0.55); max-width: 480px; margin: 0 auto 3rem; }
        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            background: rgba(0,212,255,0.04);
            border-color: rgba(0,212,255,0.2);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .feature-icon {
            width: 52px; height: 52px;
            background: rgba(0,212,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #00d4ff;
            margin-bottom: 1.2rem;
            transition: all 0.3s;
        }
        .feature-card:hover .feature-icon {
            background: rgba(0,212,255,0.2);
            transform: scale(1.1);
        }
        .feature-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .feature-desc { color: rgba(255,255,255,0.5); font-size: 0.88rem; line-height: 1.6; }

        /* ── STATS BAR ── */
        .stats-bar {
            padding: 4rem 0;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .stat-item { text-align: center; }
        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #00d4ff;
            line-height: 1;
        }
        .stat-desc { color: rgba(255,255,255,0.5); font-size: 0.85rem; margin-top: 0.4rem; }

        /* ── CTA SECTION ── */
        .cta-section {
            padding: 6rem 0;
            text-align: center;
        }
        .cta-box {
            background: linear-gradient(135deg, rgba(0,212,255,0.08) 0%, rgba(10,37,64,0.6) 100%);
            border: 1px solid rgba(0,212,255,0.2);
            border-radius: 24px;
            padding: 4rem 3rem;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ── FOOTER ── */
        .finexa-footer {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 2rem;
            text-align: center;
            color: rgba(255,255,255,0.35);
            font-size: 0.82rem;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="finexa-nav">
        <a href="/" class="finexa-logo"><i class="bi bi-bank2"></i>FINEXA</a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="<?php echo e(route('login')); ?>" class="btn-nav-login">Login</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-nav-register ms-2">Open Account</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section">
        <div class="hero-bg-glow"></div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check-fill"></i> Bank-grade Security
                    </div>
                    <h1 class="hero-title">
                        Banking Made<br><span>Simple & Secure</span>
                    </h1>
                    <p class="hero-subtitle">
                        Finexa gives you the power to manage your money, transfer funds, apply for loans, and earn rewards — all from one intelligent digital platform.
                    </p>
                    <div class="hero-cta">
                        <a href="<?php echo e(route('register')); ?>" class="btn-hero-primary">
                            <i class="bi bi-person-plus me-2"></i>Open Free Account
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="btn-hero-outline">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-card-visual float-anim">
                        <div class="bank-card">
                            <div class="bank-card-chip"></div>
                            <div class="bank-card-number">FNX•  ••••  ••••  4391</div>
                            <div class="bank-card-bottom">
                                <div>
                                    <div class="bank-card-name">Account Holder</div>
                                    <div style="font-weight:600; font-size:0.95rem;">Your Name Here</div>
                                </div>
                                <div class="bank-card-balance">৳ 24,580.00</div>
                            </div>
                        </div>
                        <div class="floating-stat floating-stat-1">
                            <div class="stat-label">Today's Transactions</div>
                            <div class="stat-value">+৳ 3,200</div>
                        </div>
                        <div class="floating-stat floating-stat-2">
                            <div class="stat-label">Reward Points</div>
                            <div class="stat-value">⭐ 1,420 pts</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <section class="stats-bar">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-number">10K+</div>
                    <div class="stat-desc">Happy Customers</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-number">৳5M+</div>
                    <div class="stat-desc">Transactions Processed</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-desc">System Uptime</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-number">256bit</div>
                    <div class="stat-desc">AES Encryption</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center">
                <div class="section-label">Everything You Need</div>
                <h2 class="section-title">Powerful Banking Features</h2>
                <p class="section-sub">From everyday transactions to long-term savings plans — Finexa has you covered.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-arrow-left-right"></i></div>
                        <div class="feature-title">Instant Transfers</div>
                        <div class="feature-desc">Send money to any Finexa account instantly with full ACID-compliant security and automatic confirmations.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-cash-coin"></i></div>
                        <div class="feature-title">Loans & Financing</div>
                        <div class="feature-desc">Apply for personal or business loans with flexible repayment plans and transparent interest rates.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-piggy-bank"></i></div>
                        <div class="feature-title">Smart Savings</div>
                        <div class="feature-desc">Open DPS or FDR accounts and watch your money grow with competitive interest rates.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="feature-title">Digital Cards</div>
                        <div class="feature-desc">Request debit and credit cards. Freeze or unfreeze them instantly from your dashboard.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-star-fill"></i></div>
                        <div class="feature-title">Rewards Program</div>
                        <div class="feature-desc">Earn points on every transaction and redeem them for cashback and exclusive offers.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-receipt"></i></div>
                        <div class="feature-title">Bill Payments</div>
                        <div class="feature-desc">Pay utility bills and mobile recharges in seconds without ever leaving your account.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <h2 class="section-title mb-3">Ready to Get Started?</h2>
                <p style="color:rgba(255,255,255,0.6); margin-bottom: 2rem;">Join thousands of customers who trust Finexa for their daily banking needs. It only takes 2 minutes.</p>
                <a href="<?php echo e(route('register')); ?>" class="btn-hero-primary" style="font-size:1.05rem; padding: 1rem 2.5rem;">
                    <i class="bi bi-person-plus me-2"></i>Create Your Free Account
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="finexa-footer">
        <p class="mb-1"><strong style="color:rgba(255,255,255,0.5);">FINEXA</strong> — Secure Digital Banking</p>
        <p class="mb-0">© <?php echo e(date('Y')); ?> Finexa. All rights reserved. &nbsp;|&nbsp; <a href="<?php echo e(route('login')); ?>" style="color:rgba(255,255,255,0.4); text-decoration:none;">Login</a> &nbsp;|&nbsp; <a href="<?php echo e(route('register')); ?>" style="color:rgba(255,255,255,0.4); text-decoration:none;">Register</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\Finexa\resources\views/welcome.blade.php ENDPATH**/ ?>