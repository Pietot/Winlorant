<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/signup.css" />
    <title>Formulaire d'inscription</title>
</head>
<body>
    <div class="form-container">
        <h2>Inscription</h2>
        <form action="/submit" method="post">
            <div class="username-tag-container">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="tag" placeholder="Tag" required>
            </div>
            <div>
                <input type="email" name="email" placeholder="Adresse e-mail" required><br>
            </div>
            <div>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
            </div>
            <div>
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
</body>
</html>
