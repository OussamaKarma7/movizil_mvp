<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #1f4268;
            --brand-accent: #ef9a57;
            --brand-dark: #10253d;
            --brand-soft: #f4f7fb;
        }
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(239,154,87,0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(31,66,104,0.26), transparent 30%),
                linear-gradient(135deg, #edf2f7 0%, #dfe8f2 100%);
        }
        .auth-shell {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255,255,255,0.75);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(16, 37, 61, 0.18);
        }
        .brand-side {
            background: linear-gradient(180deg, #f9fbfd 0%, #eef4f9 100%);
            color: var(--brand-dark);
            padding: 48px 42px;
            height: 100%;
            position: relative;
        }
        .brand-side::after {
            content: "";
            position: absolute;
            inset: auto -70px -70px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(239,154,87,0.18), transparent 70%);
        }
        .brand-logo {
            width: 100%;
            max-width: 370px;
            display: block;
            margin-bottom: 24px;
        }
        .auth-form {
            padding: 52px 44px;
            background: rgba(255,255,255,0.92);
        }
        .eyebrow {
            color: var(--brand-primary);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .form-label {
            font-size: 0.92rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.45rem;
        }
        .form-control {
            min-height: 52px;
            border-radius: 14px;
            border: 1px solid #d9e2ec;
            padding: 0.85rem 1rem;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: rgba(31,66,104,0.5);
            box-shadow: 0 0 0 0.2rem rgba(31,66,104,0.12);
        }
        .btn-login {
            min-height: 52px;
            border-radius: 14px;
            font-weight: 700;
            background: var(--brand-primary);
            border-color: var(--brand-primary);
            letter-spacing: 0.02em;
        }
        .btn-login:hover {
            background: #173351;
            border-color: #173351;
        }
        .soft-panel {
            border: 1px solid rgba(31,66,104,0.08);
            border-radius: 18px;
            background: rgba(255,255,255,0.6);
            padding: 18px 18px 8px;
        }
        .feature-list {
            margin-top: 28px;
            padding: 0;
            list-style: none;
        }
        .feature-list li {
            margin-bottom: 14px;
            padding-left: 28px;
            position: relative;
            color: #334155;
        }
        .feature-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent));
        }
        @media (max-width: 991px) {
            .brand-side {
                display: none;
            }
            .auth-form {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="auth-shell">
                    <div class="row g-0">
                        <div class="col-lg-5">
                            <div class="brand-side">
                                <img src="<?php echo e(asset('uis-logo.png')); ?>" alt="Universal Invest Strategy" class="brand-logo">
                                <h3 class="fw-bold mb-2">Plateforme de gestion</h3>
                                <p class="mb-0 text-secondary">Un espace professionnel pour la domiciliation, la gestion des contrats, la facturation et le suivi client.</p>
                                <ul class="feature-list">
                                    <li>Suivi centralise des demandes et dossiers clients</li>
                                    <li>Generation des contrats et factures en quelques clics</li>
                                    <li>Interface claire inspiree des logiciels metiers</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="auth-form">
                                <p class="eyebrow mb-2">Connexion</p>
                                <h3 class="mb-2 fw-bold">Accedez a votre espace</h3>
                                <p class="text-muted mb-4">Connectez-vous pour gerer vos contrats, vos factures et votre activite en toute simplicite.</p>
                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
                                <?php endif; ?>
                                <div class="soft-panel">
                                    <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label class="form-label">Adresse email</label>
                                            <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" placeholder="nom@entreprise.com" required>
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mot de passe</label>
                                            <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Saisissez votre mot de passe" required>
                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-login w-100">Se connecter</button>
                                    </form>
                                </div>
                                <p class="mt-3 mb-0 text-center">
                                    Nouveau client ? <a class="fw-semibold" href="<?php echo e(route('register')); ?>">Creer un compte</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\saas-accounting\resources\views/auth/login.blade.php ENDPATH**/ ?>