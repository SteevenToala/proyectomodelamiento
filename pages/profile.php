<?php
include '../components/connect.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/header_profile_style.css">
    <link rel="stylesheet" href="../css/user_profile_style.css">
    <title>Perfil</title>
</head>

<body>
    <?php include 'components/user_header_profile.php' ?>

    <main>
        <h3>Perfil</h3>

        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
        $count_user_comments->execute([$user_id]);
        $total_user_comments = $count_user_comments->rowCount();
        $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
        $count_user_likes->execute([$user_id]);
        $total_user_likes = $count_user_likes->rowCount();
        ?>
        <div class="user-data">
            <p><?= $fetch_profile['name']; ?></p>
            <p>Comentarios: <span><?= $total_user_comments; ?></span></p>
            <p>Likes: <span><?= $total_user_likes; ?></span></p>
        </div>


        <form action="" method="post">
            <div class="input-data">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box"
                    maxlength="50">
            </div>
            <div class="input-data">
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" class="box"
                    maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="input-data">
                <label for="old-pass">Contraseña antigua</label>
                <input type="password" id="old-pass" name="old_pass" placeholder="contraseña actual" class="box"
                    maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="input-data">
                <label for="new-pass">Nueva contraseña</label>
                <input type="password" id="new-pass" name="new_pass" placeholder="nueva contraseña" class="box"
                    maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="input-data">
                <label for="confirm">Ingresa nuevamente la contraseña</label>
                <input type="password" id="confirm" name="confirm_pass" placeholder="vuelve a ingresar la nueva contraseña"
                    class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <input type="submit" value="Actualizar perfil" name="submit" class="btn">
        </form>
    </main>

    <script src="../js/admin_script.js"></script>
    <script>
        const divPage = document.getElementById('account');
        divPage.classList.add('on-page');
    </script>
</body>

</html>