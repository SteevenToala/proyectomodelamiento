<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
	header('location:../pages/login.php');
}
if (isset($_POST['publish'])) {
	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	$title = $_POST['title'];
	$title = filter_var($title, FILTER_SANITIZE_STRING);
	$content = $_POST['content'];
	$content = filter_var($content, FILTER_SANITIZE_STRING);
	$category = $_POST['category'];
	$category = filter_var($category, FILTER_SANITIZE_STRING);
	$status = 'activo';
	$image = $_FILES['image']['name'];
	$image = filter_var($image, FILTER_SANITIZE_STRING);
	$image_size = $_FILES['image']['size'];
	$image_tmp_name = $_FILES['image']['tmp_name'];
	$image_folder = '../uploaded_img/' . $image;
	$select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND user_id = ?");
	$select_image->execute([$image, $admin_id]);
	if (isset($image)) {
		if ($select_image->rowCount() > 0 and $image != '') {
			$message[] = 'nombre de imagen repetido!';
		} elseif ($image_size > 2000000) {
			$message[] = 'el tamaño de la imagen es demasiado grande!';
		} else {
			move_uploaded_file($image_tmp_name, $image_folder);
		}
	} else {
		$image = '';
	}
	if ($select_image->rowCount() > 0 and $image != '') {
		$message[] = 'por favor, renombra tu imagen!';
	} else {
		$insert_post = $conn->prepare("INSERT INTO `posts`(user_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
		$insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
		$message[] = 'post publicado!';
	}
}
if (isset($_POST['draft'])) {
	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	$title = $_POST['title'];
	$title = filter_var($title, FILTER_SANITIZE_STRING);
	$content = $_POST['content'];
	$content = filter_var($content, FILTER_SANITIZE_STRING);
	$category = $_POST['category'];
	$category = filter_var($category, FILTER_SANITIZE_STRING);
	$status = 'inactivo';
	$image = $_FILES['image']['name'];
	$image = filter_var($image, FILTER_SANITIZE_STRING);
	$image_size = $_FILES['image']['size'];
	$image_tmp_name = $_FILES['image']['tmp_name'];
	$image_folder = '../uploaded_img/' . $image;
	$select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND user_id = ?");
	$select_image->execute([$image, $admin_id]);
	if (isset($image)) {
		if ($select_image->rowCount() > 0 and $image != '') {
			$message[] = 'nombre de imagen repetido!';
		} elseif ($image_size > 2000000) {
			$message[] = 'el tamaño de la imagen es demasiado grande!';
		} else {
			move_uploaded_file($image_tmp_name, $image_folder);
		}
	} else {
		$image = '';
	}
	if ($select_image->rowCount() > 0 and $image != '') {
		$message[] = 'por favor, renombra tu imagen!';
	} else {
		$insert_post = $conn->prepare("INSERT INTO `posts`(user_id, name, title, content, category, image, status) VALUES(?,?,?,?,?,?,?)");
		$insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
		$message[] = 'borrador guardado!';
	}
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>posts</title>
	<link rel="stylesheet" href="../css/admin_style.css">
	<link rel="stylesheet" href="../css/header_profile_style.css">
</head>

<body>
	<?php include '../components/admin_header.php' ?>
	<main>
		<section class="post-editor">
			<h1 class="heading">Crear post</h1>
			<form action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="name" value="<?= $fetch_profile['name']; ?>">
				<p>Título<span>*</span></p>
				<input type="text" name="title" maxlength="100" required placeholder="Titulo"
					class="box">
				<p>Contenido<span>*</span></p>
				<textarea name="content" class="box" required maxlength="10000" placeholder="Escribe algo..."
					cols="30" rows="10"></textarea>
				<p>Categoría<span>*</span></p>
				<select name="category" class="box" required>
					<option value="" selected disabled>-- selecciona la categoría* </option>
					<option value="nature">naturaleza</option>
					<option value="education">educación</option>
					<option value="pets and animals">mascotas y animales</option>
					<option value="technology">tecnología</option>
					<option value="fashion">moda</option>
					<option value="entertainment">entretenimiento</option>
					<option value="movies and animations">películas</option>
					<option value="gaming">juegos</option>
					<option value="music">música</option>
					<option value="sports">deportes</option>
					<option value="news">noticias</option>
					<option value="travel">viajes</option>
					<option value="comedy">comedia</option>
					<option value="design and development">diseño y desarrollo</option>
					<option value="food and drinks">comida y bebidas</option>
					<option value="lifestyle">estilo de vida</option>
					<option value="personal">personal</option>
					<option value="health and fitness">salud y fitness</option>
					<option value="business">negocios</option>
					<option value="shopping">compras</option>
					<option value="animations">animaciones</option>
				</select>
				<p>Imagen del post</p>
				<input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
				<div class="flex-btn">
					<input type="submit" value="Publicar post" name="publish" class="btn">
				</div>
			</form>
		</section>
	</main>
	<script src="../js/admin_script.js"></script>
</body>

</html>