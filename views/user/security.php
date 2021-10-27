<h2>Security</h2>
<section>
    <form method="POST">
        <h3>Change password</h3>
        <div>
            <label for="cur_password">Current password</label>
            <input id="cur_password" class="<?php if (isset($errors['cur_password'])) : ?>is-invalid<?php endif; ?>" type="password" name="cur_password" placeholder="Current password" autocomplete="new-password" autofocus>
            <?php if (isset($errors['cur_password'])) : ?>
                <p><?= $errors['cur_password']; ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label for="new_password">New password</label>
            <input id="new_password" class="<?php if (isset($errors['new_password'])) : ?>is-invalid<?php endif; ?>" type="password" name="new_password" aria-describedby="new_password_help" placeholder="New password" autocomplete="new-password">
            <?php if (isset($errors['new_password'])) : ?>
                <p><?= $errors['new_password']; ?></p>
            <?php endif; ?>
            <small id="new_password_help">Password must be 8–20 characters long, contain numbers, uppercase and lowercase letters and special characters (!@#$%^&*)</small>
        </div>
        <div>
            <label for="new_password_repeat">Confirm new password</label>
            <input id="new_password_repeat" class="<?php if (isset($errors['new_password_repeat'])) : ?>is-invalid<?php endif; ?>" type="password" name="new_password_repeat" aria-describedby="new_password_repeat_help" placeholder="Confirm new password" autocomplete="new-password">
            <?php if (isset($errors['new_password_repeat'])) : ?>
                <p><?= $errors['new_password_repeat']; ?></p>
            <?php endif; ?>
            <small id="new_password_repeat_help">Repeat the password again</small>
        </div>
        <div>
            <button type="submit" name="change">Change</button>
        </div>
    </form>
</section>
