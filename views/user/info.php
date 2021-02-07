<h2>Information</h2>
<section>
    <h3>General information</h3>
    <p>Login: <span><?= $user->getUserLogin(); ?></span></p>
    <p>Full name: <span><?= $user->getUserFullName(); ?></span></p>
    <p>Access level: <span><?= $user->getAccess(); ?></span></p>
</section>
