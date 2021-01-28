<?php include ROOT . '/views/layout/_head.php'; ?>

<body>
    <h1>Страница входа</h1>

    <?php include ROOT . '/views/layout/_alert.php'; ?>

    <div>
        <form method="POST">
            <div>
                <label for="login">Логин</label>
                <input class="<?php if (isset($errors['login'])) : ?>is-invalid<?php endif; ?>" type="text" name="login" placeholder="Логин" autocomplete="on" autofocus>
                <?php if (isset($errors['login'])) : ?>
                    <p><?= $errors['login']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label for="password">Пароль</label>
                <input class="<?php if (isset($errors['password'])) : ?>is-invalid<?php endif; ?>" type="password" name="password" placeholder="Пароль" autocomplete="on">
                <?php if (isset($errors['password'])) : ?>
                    <p><?= $errors['password']; ?></p>
                <?php endif; ?>
            </div>
            <div>
                <button type="submit" name="sign_in">Войти</button>
            </div>
        </form>
    </div>

    <?php include ROOT . '/views/layout/_script.php'; ?>
</body>

</html>
