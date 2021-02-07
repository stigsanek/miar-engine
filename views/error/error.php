<?php include_once  ROOT . '/views/layout/_head.php'; ?>

<body>
    <h1>Error <?= $error['code']; ?></h1>
    <p><?= $error['msg']; ?></p>
</body>

</html>
