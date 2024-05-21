<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['user_id'];

if (!isset($admin_id)) {
	header('location:../pages/login.php');
}

if (isset($_POST['delete'])) {

	$p_id = $_POST['post_id'];
	$p_id = filter_var($p_id, FILTER_SANITIZE_STRING);
	$delete_image = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
	$delete_image->execute([$p_id]);
	$fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
	if ($fetch_delete_image['image'] != '') {
		unlink('../uploaded_img/' . $fetch_delete_image['image']);
	}
	$delete_post = $conn->prepare("DELETE FROM `posts` WHERE id = ?");
	$delete_post->execute([$p_id]);
	$delete_comments = $conn->prepare("DELETE FROM `comments` WHERE post_id = ?");
	$delete_comments->execute([$p_id]);
	$message[] = "Post borrado exitosamente!";

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
	<title>Posts</title>

</head>

<body>

	<?php include '../components/admin_header.php' ?>

	<section class="show-posts">

		<h1 class="heading">Tus posts</h1>

		<div class="box-container">

			<?php
			$select_posts = $conn->prepare("SELECT * FROM `posts` WHERE user_id = ?");
			$select_posts->execute([$admin_id]);
			if ($select_posts->rowCount() > 0) {
				while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
					$post_id = $fetch_posts['id'];

					$count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
					$count_post_comments->execute([$post_id]);
					$total_post_comments = $count_post_comments->rowCount();

					$count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
					$count_post_likes->execute([$post_id]);
					$total_post_likes = $count_post_likes->rowCount();

					?>
					<form method="post" class="box">
						<input type="hidden" name="post_id" value="<?= $post_id; ?>">
						<?php if ($fetch_posts['image'] != '') { ?>
							<img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">
						<?php } ?>
						
						<a href="#">
							<div class="title"><?= $fetch_posts['title']; ?></div>
							<div class="posts-content"><?= $fetch_posts['content']; ?></div>
						</a>				
						
					</form>
					<?php
				}
			} else {
				echo '<p class="empty">no existen post aun! <a href="add_posts.php" class="btn" style="margin-top:1.5rem;">crear post</a></p>';
			}
			?>

		</div>


	</section>
	<script src="../js/admin_script.js"></script>

</body>

</html>