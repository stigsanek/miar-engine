<h2>Профиль пользователя</h2>
<section>
    <h3>Общие сведения</h3>
    <p>Логин: <span><?= $user->getUserLogin(); ?></span></p>
    <p>Полное имя: <span><?= $user->getUserFullName(); ?></span></p>
    <p>Уровень доступа: <span><?= $user->getAccess(); ?></span></p>
</section>
