<?php $alerts = $user->getAlert(); ?>
<?php if (isset($alerts)) : ?>
    <?php foreach ($alerts as $alert) : ?>
        <p><?= $alert['type']; ?></p>
        <p><?= $alert['message']; ?></p>
    <?php endforeach; ?>
<?php endif; ?>
