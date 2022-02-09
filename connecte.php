<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Sessions</title>
</head>
<body>
    <a href="connecte.php?logout=1">Déconnexion</a>

    <?php
        session_start();
        $logout = $_GET['logout'] ?? null;
        if ($logout) {
            unset($_SESSION['user']);
        }
        if (!$_SESSION) {
            header('Location: index.php');
            }
    ?>
</body>
</html>