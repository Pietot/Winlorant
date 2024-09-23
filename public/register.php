<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['tag'])) {
    include_once __DIR__ . '/../src/php/is_registered.php';
    include '../src/php/db.php';
    if (is_registered($db)) {
        header('Location: index.php');
    }
} else
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Winlorant</title>
    <link rel="icon" type="image/x-icon" href="assets/icon.svg">
    <link rel="stylesheet" type="text/css" href="css/register.css">
</head>

<body>
    <div class="form-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username-tag">Username & Tag</label>
                <div class="tag-container">
                    <input type="text" class="username " id="username" name="username" placeholder="Username" maxlength="16" required>
                    <input type="text" class="tag" id="tag" name="tag" placeholder="Tag" maxlength="5" required>
                </div>
                <div class="checkbox">
                    <input class="inp-cbx" id="privacy" type="checkbox" />
                    <label class="cbx" for="privacy">
                        <span>
                            <svg width="12px" height="10px">
                                <use xlink:href="#check-4"></use>
                            </svg>
                        </span>
                        <span>I agree to the <a href="privacy.php" target="_blank">Privacy Policy</a>
                            <svg class="inline-svg">
                                <symbol id="check-4" viewbox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </symbol>
                            </svg>
                        </span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-submit">Register</button>
            <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['username']) && isset($_POST['tag']) && isset($_POST['privacy'])) {
                include_once '../src/php/get_user_region.php';

                $username = $_POST['username'];
                $tag = $_POST['tag'];
                $region = get_user_region($username, $tag);

                if (!$region) {
                    echo "<p class='error'>Invalid username or tag</p>";
                    return;
                }

                $query = "SELECT * FROM users WHERE username = :username AND tag = :tag";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch();

                if (!$user) {
                    $query = "INSERT INTO users (username, tag, region) VALUES (:username, :tag, :region)";
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                    $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
                    $stmt->bindValue(':region', $region, PDO::PARAM_STR);
                    $stmt->execute();
                    include_once '../src/php/get_data_json.php';
                    include_once '../src/php/compress_json.php';
                    $json = get_data_json($username, $tag, $region);
                    $query = "SELECT id FROM users WHERE username = :username AND tag = :tag";
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                    $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
                    $stmt->execute();
                    $id = $stmt->fetch()['id'];
                    $json = json_encode($json, 0);
                    file_put_contents(__DIR__ . "/../src/json/$id.json.gz", gzencode($json));
                    compress($id);
                }

                setcookie('username', $username, [
                    'expires' => time() + 60 * 60 * 24 * 30,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                ]);
                setcookie('tag', $tag, [
                    'expires' => time() + 60 * 60 * 24 * 30,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                ]);
                setcookie('region', $region, [
                    'expires' => time() + 60 * 60 * 24 * 30,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                ]);

                header('Location: index.php');
            } else {
                echo "<p class='error'>Please accept the privacy policy</p>";
            }
        }
            ?>
        </form>
    </div>
</body>

</html>