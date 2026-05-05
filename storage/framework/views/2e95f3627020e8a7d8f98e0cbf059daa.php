
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finexa — <?php echo $__env->yieldContent('title'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #06101f;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Background glow effect */
        .bg-glow {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 700px;
            background: radial-gradient(ellipse at center, rgba(0,212,255,0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Glassmorphism Auth Card */
        .auth-card {
            width: 100%;
            max-width: 500px;
            background: rgba(10, 37, 64, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1;
            overflow: hidden;
            padding: 2.5rem;
            margin: 2rem;
            animation: fadeInUp 0.6s ease both;
        }
        
        .auth-card.register-card { max-width: 700px; }

        .brand-header { text-align: center; margin-bottom: 2rem; }
        .brand-header a { text-decoration: none; }
        .brand-logo { font-size: 1.8rem; font-weight: 800; color: #00d4ff; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 8px;}
        .brand-tagline { font-size: 0.85rem; color: rgba(255, 255, 255, 0.5); margin-top: 5px; }

        /* Form Controls Overrides */
        .form-label { font-size: 0.85rem; font-weight: 500; color: rgba(255, 255, 255, 0.7); margin-bottom: 0.3rem;}
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.25s;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(0, 212, 255, 0.5);
            box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.1);
            color: #fff;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.3); }
        .input-group-text, .btn-outline-secondary {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }
        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #0099cc);
            color: #06101f;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s;
            box-shadow: 0 4px 15px rgba(0, 212, 255, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.35);
            color: #06101f;
        }

        /* Checkbox */
        .form-check-input { background-color: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); }
        .form-check-input:checked { background-color: #00d4ff; border-color: #00d4ff; }

        /* Links */
        a { color: #00d4ff; text-decoration: none; transition: 0.2s; }
        a:hover { color: #fff; }
        
        .text-muted { color: rgba(255,255,255,0.5) !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>

    <?php echo $__env->yieldContent('content'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH D:\Finexa\resources\views/layouts/auth.blade.php ENDPATH**/ ?>