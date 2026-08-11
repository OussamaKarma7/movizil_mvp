<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Client</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #1f4268;
            --brand-accent: #ef9a57;
            --brand-dark: #10253d;
        }
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(239,154,87,0.22), transparent 26%),
                radial-gradient(circle at bottom left, rgba(31,66,104,0.18), transparent 30%),
                linear-gradient(135deg, #eef3f8 0%, #dbe5ef 100%);
        }
        .auth-shell {
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid rgba(255,255,255,0.75);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(16, 37, 61, 0.18);
        }
        .brand-side {
            background: linear-gradient(180deg, #f9fbfd 0%, #eef4f9 100%);
            color: var(--brand-dark);
            padding: 42px 32px;
            height: 100%;
            position: relative;
        }
        .brand-side::after {
            content: "";
            position: absolute;
            inset: auto auto -70px -70px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(31,66,104,0.15), transparent 70%);
        }
        .brand-logo {
            width: 100%;
            max-width: 320px;
            display: block;
            margin-bottom: 24px;
        }
        .auth-form {
            padding: 48px 40px;
            background: rgba(255,255,255,0.92);
        }
        .form-control, .form-select, textarea {
            border-radius: 14px;
            min-height: 50px;
            border: 1px solid #d9e2ec;
            box-shadow: none;
            padding: 0.85rem 1rem;
        }
        textarea.form-control {
            min-height: 92px;
        }
        .form-control:focus, textarea.form-control:focus {
            border-color: rgba(31,66,104,0.5);
            box-shadow: 0 0 0 0.2rem rgba(31,66,104,0.12);
        }
        .btn-register {
            min-height: 52px;
            border-radius: 14px;
            font-weight: 700;
            background: var(--brand-primary);
            border-color: var(--brand-primary);
        }
        .btn-register:hover {
            background: #173351;
            border-color: #173351;
        }
        .eyebrow {
            color: var(--brand-primary);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .soft-panel {
            border: 1px solid rgba(31,66,104,0.08);
            border-radius: 18px;
            background: rgba(255,255,255,0.6);
            padding: 18px 18px 8px;
        }
        .info-list {
            margin-top: 22px;
            padding: 0;
            list-style: none;
        }
        .info-list li {
            margin-bottom: 12px;
            padding-left: 26px;
            position: relative;
            color: #334155;
            font-size: 0.95rem;
        }
        .info-list li::before {
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
            <div class="col-xl-11 col-lg-12">
                <div class="auth-shell">
                    <div class="row g-0">
                        <div class="col-lg-4">
                            <div class="brand-side">
                                <img src="<?php echo e(asset('uis-logo.png')); ?>" alt="Universal Invest Strategy" class="brand-logo">
                                <h4 class="fw-bold mb-2">Espace client moderne</h4>
                                <p class="text-secondary mb-0">Une experience claire et professionnelle pour vos demandes de domiciliation et le suivi de vos documents.</p>
                                <ul class="info-list">
                                    <li>Inscription simple et rapide</li>
                                    <li>Suivi des contrats et factures</li>
                                    <li>Gestion documentaire centralisee</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="auth-form">
                                <p class="eyebrow mb-2">Inscription client</p>
                                <h3 class="mb-2 fw-bold">Creer votre compte</h3>
                                <p class="text-muted mb-4">Renseignez vos informations pour acceder a votre portail client Universal Invest Strategy.</p>
                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
                                <?php endif; ?>
                                <div class="soft-panel">
                                    <form method="POST" action="<?php echo e(route('register.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <input type="text" name="first_name" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Prenom" value="<?php echo e(old('first_name')); ?>" required>
                                                <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="last_name" class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nom" value="<?php echo e(old('last_name')); ?>" required>
                                                <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="date" name="birth_date" class="form-control <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('birth_date')); ?>" required>
                                                <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="cin" class="form-control <?php $__errorArgs = ['cin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="CIN" value="<?php echo e(old('cin')); ?>" required>
                                                <?php $__errorArgs = ['cin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Telephone" value="<?php echo e(old('phone')); ?>" required>
                                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Email" value="<?php echo e(old('email')); ?>" required>
                                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-12">
                                                <textarea name="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Adresse complete" rows="2" required><?php echo e(old('address')); ?></textarea>
                                                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Mot de passe" required>
                                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer mot de passe" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-register w-100 mt-4">Creer mon compte</button>
                                    </form>
                                </div>
                                <p class="mt-3 mb-0 text-center">
                                    Deja inscrit ? <a class="fw-semibold" href="<?php echo e(route('login')); ?>">Se connecter</a>
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
<?php /**PATH C:\Users\o.karmaoui\Downloads\movizil\movizil\resources\views/auth/register.blade.php ENDPATH**/ ?>