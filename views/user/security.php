<h2>Безопасность</h2>
<section>
    <form method="POST">
        <h3>Изменение пароля</h3>
        <div>
            <label for="cur_password">Текущий пароль</label>
            <input class="<?php if (isset($errors['cur_password'])) : ?>is-invalid<?php endif; ?>" type="password" name="cur_password" placeholder="Новый пароль" autocomplete="new-password" autofocus>
            <?php if (isset($errors['cur_password'])) : ?>
                <p><?= $errors['cur_password']; ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="new_password">Новый пароль</label>
            <input class="<?php if (isset($errors['new_password'])) : ?>is-invalid<?php endif; ?>" type="password" name="new_password" aria-describedby="new_password_help" placeholder="Новый пароль" autocomplete="new-password">
            <?php if (isset($errors['new_password'])) : ?>
                <p><?= $errors['new_password']; ?></p>
            <?php endif; ?>
            <small id="new_password_help">Пароль должен быть длиной 8 - 20 символов, состоять из цифр, латинских прописных и строчных букв, а также специальных символов (!@#$%^&*)</small>
        </div>
        <div>
            <label for="new_password_repeat">Подтвердите пароль</label>
            <input class="<?php if (isset($errors['new_password_repeat'])) : ?>is-invalid<?php endif; ?>" type="password" name="new_password_repeat" aria-describedby="new_password_repeat_help" placeholder="Подтвердите пароль" autocomplete="new-password">
            <?php if (isset($errors['new_password_repeat'])) : ?>
                <p><?= $errors['new_password_repeat']; ?></p>
            <?php endif; ?>
            <small id="new_password_repeat_help">Повторите пароль еще раз</small>
        </div>
        <div>
            <button type="submit" name="change">Изменить</button>
        </div>
    </form>
</section>
