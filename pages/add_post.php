<?php
include '../components/connect.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
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
	$select_image->execute([$image, $user_id]);
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
		$insert_post->execute([$user_id, $name, $title, $content, $category, $image, $status]);
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
	<?php include '../pages/components/user_header_profile.php' ?>
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
					<option value="Naturaleza">naturaleza</option>
					<option value="Educacion">educación</option>
					<option value="Mascotas y animales">mascotas y animales</option>
					<option value="Tecnologia">tecnología</option>
					<option value="Moda">moda</option>
					<option value="Entretenimiento">entretenimiento</option>
					<option value="Peliculas">películas</option>
					<option value="Juegos">juegos</option>
					<option value="Musica">música</option>
					<option value="Deportes">deportes</option>
					<option value="Noicias">noticias</option>
					<option value="Viaje">viajes</option>
					<option value="Comedia">comedia</option>
					<option value="Diseño y desarrollo">diseño y desarrollo</option>
					<option value="Comida y bebida">comida y bebidas</option>
					<option value="Estilo de vida">estilo de vida</option>
					<option value="Personal">personal</option>
					<option value="Salud y fitness">salud y fitness</option>
					<option value="Negocios">negocios</option>
					<option value="Compras">compras</option>
					<option value="Animaciones">animaciones</option>
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