<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
	header('location:../pages/login.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/admin_style.css">
	<link rel="stylesheet" href="../css/header_profile_style.css">
	<title>Dashboard</title>
</head>

<body>
	<?php include '../components/admin_header.php' ?>

	<main>
		<section class="dashboard">
			<h1 class="heading">Dashboard</h1>
			<div class="box-container">
				<div class="box">
					<?php
					$select_posts = $conn->prepare("SELECT * FROM `posts` WHERE user_id = ?");
					$select_posts->execute([$admin_id]);
					$numbers_of_posts = $select_posts->rowCount();
					?>
					<h3>Publicaciones añadidas</h3>
					<p><?= $numbers_of_posts; ?></p>
					<a href="add_posts.php" class="btn">Añadir nueva publicación</a>
				</div>												
								
			</div>
		</section>
	</main>

	<script src="../js/admin_script.js"></script>
</body>

</html>