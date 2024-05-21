<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;



?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css">
   <title>Categorias</title>

</head>

<body>

   <?php include '../components/user_header.php'; ?>
   <main>
      <section class="categories">

         <h1 class="heading">Categorias</h1>

         <div class="box-container">
            <div class="box"><span>01</span><a href="category.php?category=nature">naturaleza</a></div>
            <div class="box"><span>02</span><a href="category.php?category=education">educación</a></div>
            <div class="box"><span>03</span><a href="category.php?category=pets and animals">mascotas y animales</a>
            </div>
            <div class="box"><span>04</span><a href="category.php?category=technology">tecnología</a></div>
            <div class="box"><span>05</span><a href="category.php?category=fashion">moda</a></div>
            <div class="box"><span>06</span><a href="category.php?category=entertainment">entretenimiento</a></div>
            <div class="box"><span>07</span><a href="category.php?category=movies">películas</a></div>
            <div class="box"><span>08</span><a href="category.php?category=gaming">juegos</a></div>
            <div class="box"><span>09</span><a href="category.php?category=music">música</a></div>
            <div class="box"><span>10</span><a href="category.php?category=sports">deportes</a></div>
            <div class="box"><span>11</span><a href="category.php?category=news">noticias</a></div>
            <div class="box"><span>12</span><a href="category.php?category=travel">viajes</a></div>
            <div class="box"><span>13</span><a href="category.php?category=comedy">comedia</a></div>
            <div class="box"><span>14</span><a href="category.php?category=design and development">diseño y
                  desarrollo</a></div>
            <div class="box"><span>15</span><a href="category.php?category=food and drinks">comida y bebidas</a></div>
            <div class="box"><span>16</span><a href="category.php?category=lifestyle">estilo de vida</a></div>
            <div class="box"><span>17</span><a href="category.php?category=health and fitness">salud y fitness</a></div>
            <div class="box"><span>18</span><a href="category.php?category=business">negocios</a></div>
            <div class="box"><span>19</span><a href="category.php?category=shopping">compras</a></div>
            <div class="box"><span>20</span><a href="category.php?category=animations">animaciones</a></div>
            <div class="box"><span>21</span><a href="category.php?category=personal">personal</a></div>
         </div>

   </main>
   </section>
   <?php include '../components/footer.php'; ?>

   <script src="../js/script.js"></script>

</body>

</html>