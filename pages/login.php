<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   } else {
      $message[] = 'Email o contraseña incorrecta';
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
   <title>Inicio de sesión</title>

</head>

<body>
   <?php include '../components/user_header.php'; ?>

   <main style="justify-content: center;">
      <section class="form-container">
         <form action="" method="post">
            <h3>Iniciar sesión</h3>
            <input type="email" name="email" required placeholder="correo electronico" class="box" maxlength="50">
            <input type="password" name="pass" required placeholder="contraseña" class="box" maxlength="50">
            <input type="submit" value="Iniciar sesión" name="submit" class="btn" style="padding: .75rem;">
         </form>
      </section>
   </main>

   <?php include '../components/footer.php'; ?>

   <script src="../js/script.js"></script>
</body>

</html>