<?php include_once  ROOT . '/views/layout/_head.php'; ?>

<body>
    <!--Header start-->
    <header>
        <nav>
            <ul>
                <li class="<?php if ($tab === 'main') : ?>active<?php endif; ?>">
                    <a href="/">Главная</a>
                </li>
                <?php if ($user->isAdmin()) : ?>
                    <li class="<?php if ($tab === 'admin') : ?>active<?php endif; ?>">
                        <a href="/admin">Панель администратора</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul>
                <?php if ($user->isAuthUser()) : ?>
                    <li class="<?php if ($tab === 'user') : ?>active<?php endif; ?>">
                        <a href="/profile/info"><?= $user->getUserLogin(); ?></a>
                    </li>
                    <li><a href="/logout">Выйти</a></li>
                <?php else : ?>
                    <li><a href="/login">Войти</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <!--Header end-->
    <!--Main start-->
    <main>
        <div>
            <?php include_once  ROOT . '/views/layout/_alert.php'; ?>
            <!--Content start-->
            <?= $content; ?>
            <!--Content end-->
        </div>
    </main>
    <!--Main end-->
    <!--Footer start-->
    <footer>
        <p>&copy; <?= date('Y'); ?></p>
    </footer>
    <!--Footer end-->

    <?php include_once  ROOT . '/views/layout/_script.php'; ?>
</body>

</html>
