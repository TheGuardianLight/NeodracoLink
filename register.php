<!--
  ~ Copyright (c) 2024 - Veivneorul. This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License (BY-NC-ND 4.0).
  -->

<?php
global $dbConfig, $config;
require 'vendor/autoload.php';
require 'php/api_config.php';

$message = '';
$messageType = 'default';

// Enregistrement de l'utilisateur
if(isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';

    if(!empty($username) && !empty($password) && !empty($firstName) && !empty($lastName) && !empty($email)){
        $connection = getDbConnection($dbConfig);

        // Check if the user already exists
        $records = $connection->prepare('SELECT username FROM users WHERE username = :username OR email = :email');
        $records->bindParam(':username', $username);
        $records->bindParam(':email', $email);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        if(!empty($results)){
            $message = "Erreur : Utilisateur déjà existant";
        } else {
            $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
            $stmt->bindParam(':password', $hashedPassword);

            if($stmt->execute()) {
                $stmt = $connection->prepare("INSERT INTO user_info (username, first_name, last_name, email) VALUES (:username, :firstName, :lastName, :email)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':lastName', $lastName);
                $stmt->bindParam(':email', $email);
                if($stmt->execute()){
                    $message = 'Nouvel utilisateur créé avec succès. Vous allez être redirigé vers la page de connexion.';
                    $messageType = 'success';

                    // Redirigé vers login.php après 5 secondes
                    header("refresh:5;url=login.php");
                } else {
                    $message = 'Désolée, il y a eu une erreur en créant votre compte';
                    $messageType = 'danger';
                }

            } else {
                $message = 'Désolée, il y a eu une erreur en créant votre compte';
                $messageType = 'danger';
            }
        }
    } else {
        $message = "Erreur : veuillez remplir tous les champs";
        $messageType = 'warning';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="styles.css" rel="stylesheet"/>
    <?php require 'php/favicon.php' ?>
</head>

<body>
<?php if ($config['allowSignup'] == "true"): ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <h2 class="text-center my-4">Inscription</h2>
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur">
                    </div>

                    <div class="form-group mt-2">
                        <label for="firstName">Prénom</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Prénom">
                    </div>

                    <div class="form-group mt-2">
                        <label for="lastName">Nom</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Nom">
                    </div>

                    <div class="form-group mt-2">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>

                    <div class="form-group mt-2">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary" name="register">S'inscrire</button>
                    </div>
                </form>
                <?php if(!empty($message)): ?>
                    <?php
                    $messageTypeMap = [
                        'success' => 'alert-success',
                        'danger' => 'alert-danger',
                        'warning' => 'alert-warning',
                        'default' => 'alert-info', // Attribuez une classe par défaut ici
                    ];

                    function getAlertDivClass($messageTypeMap, $messageType)
                    {
                        if (!isset($messageTypeMap[$messageType])) {
                            throw new \InvalidArgumentException("Invalid message type: $messageType");
                        }

                        return $messageTypeMap[$messageType];
                    }

                    if (!empty($message) && array_key_exists($messageType, $messageTypeMap)):
                        $class = getAlertDivClass($messageTypeMap, $messageType);
                        ?>

                        <div class="alert <?= $class ?>" role="alert">
                            <p class="text-center"><?= $message ?></p>
                        </div>

                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else:?>
    <div class="card text-center">
        <div class="card-header bg-danger text-white">
            <h3>Avertissement</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Erreur !</h4>
                <p>Inscription interdite.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

</body>

</html>