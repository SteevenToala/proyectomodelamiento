<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   if ($pass != $cpass) {
      $message[] = 'Las contraseñas no coincide';
   } else {
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);

      if ($select_user->rowCount() > 0) {
         $message[] = 'Email ya esta registrado';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $pass]);

         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);

         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="../css/style_post.css">

   <title>Registrarse</title>

</head>

<body>
   <?php include '../components/user_header.php'; ?>

   <main style="justify-content: center;">
      <section class="form-container">

         <form action="" method="post">
            <h3>Registrarse</h3>
            <input type="text" name="name" required placeholder="nombre" class="box" maxlength="50">
            <input type="email" name="email" required placeholder="correo electronico" class="box" maxlength="50">
            <input type="password" name="pass" required placeholder="contraseña" class="box" maxlength="50">
            <input type="password" name="cpass" required placeholder="reingresa la contraseña" class="box"
               maxlength="50">
            <input type="submit" value="Registrate" name="submit" class="btn">
         </form>

      </section>
   </main>

   <?php include '../components/footer.php'; ?>

   <script src="../js/script.js"></script>

</body>

</html>