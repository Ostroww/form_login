<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Sessions</title>
</head>
<body>

    <?php
        require 'helpers.php';

        $db = new PDO('mysql:host=localhost;port=3306;dbname=exo-session;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $login = $_POST['newuser'] ?? null;
        $pwd = $_POST['newpwd'] ?? null;
        $cpwd = $_POST['confirmpwd'] ?? null;

        
        $errors = [];
        
        if (isset($_POST['signin'])) {

            
            
            if (!empty($_POST)) {
                $query = $db->prepare("SELECT * FROM users WHERE login = :login");
                $query->execute(['login' => $login]);
                $result = $query->fetch();

                if ($result) {
                    $errors[] = 'Le nom d\'utilisateur existe déjà.';
                }

                if ($pwd != $cpwd) {
                    $errors[] = 'Les mots de passe ne correspondent pas.';
                }
                
                if (strlen($login) < 1) {
                    $errors[] = 'Le login ne peut pas être vide.';
                }
                if (strlen($pwd) < 1) {
                    $errors[] = 'Le mot de passe ne peut pas être vide.';
                }
                
                if (empty($errors)) {
                    $query = $db->prepare('INSERT INTO users (login, pwd) VALUES (:login, :pwd)');
                    $query->execute([
                        'login' => $login, 'pwd' => $pwd,
                    ]);
                    echo 'SUCCES !';
                }
                
            }
        }
        
        session_start();
        if (isset($_POST['connexion'])) {
            $login = $_POST['user'];
            $pwd = $_POST['pwd'];
            $query = $db->prepare("SELECT * FROM users WHERE login = :login AND pwd = :pwd");
            $query->execute([
                'login' => $login, 'pwd' => $pwd,
            ]);
            $resultat = $query->fetch();
            if ($resultat) {
                $_SESSION['user'] = $login;
                if ($_SESSION['user']) {
                    echo 'CEST BON';
                    header('Location: connecte.php');
                }
            } else {
                $errors[] = 'Identifiants incorrects';
            }
            /*if (!empty($_POST)) {
                if ($_POST['user'] == 'admin' && $_POST['pwd'] == 'admin') {
                    $_SESSION['user'] = $_POST['user'];
                } else {
                    $errors[] = "Identifiants incorrects";
                }
            }*/
        
        }
        
        foreach ($errors as $error) {
        echo $error;
    }
    ?>

    <form method="post">
        <input type="text" name="user" placeholder="Login">
        <input type="password" name="pwd" placeholder="Mot de passe">
        <button name="connexion">Connexion</button>
    </form>
    
    <br><br>
    
    <form method="post">
        <input type="text" name="newuser" placeholder="Login">
        <input type="password" name="newpwd" placeholder="Mot de passe">
        <input type="password" name="confirmpwd" placeholder="Confirmer mot de passe">
        <button name="signin">Inscription</button>
    </form>
</body>
</html>