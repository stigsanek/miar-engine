<?php include_once  ROOT . '/views/layout/_head.php'; ?>

<body>
    <h1>Login page</h1>

    <?php include_once  ROOT . '/views/layout/_alert.php'; ?>

    <div>
        <form method="POST">
            <div>
                <label for="login">Login</label>
                <input class="<?php if (isset($errors['login'])) : ?>is-invalid<?php endif; ?>" type="text" name="login" placeholder="Login" autocomplete="on" autofocus>
                <?php if (isset($errors['login'])) : ?>
                    <p><?= $errors['login']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label for="password">Password</label>
                <input class="<?php if (isset($errors['password'])) : ?>is-invalid<?php endif; ?>" type="password" name="password" placeholder="Password" autocomplete="on">
                <?php if (isset($errors['password'])) : ?>
                    <p><?= $errors['password']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <button type="submit" name="sign_in">Sign in</button>
            </div>
        </form>
    </div>

    <?php include_once  ROOT . '/views/layout/_script.php'; ?>
</body>

</html>
