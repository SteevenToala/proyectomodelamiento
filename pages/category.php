<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
} else {
	$user_id = '';
}
;

if (isset($_GET['category'])) {
	$category = $_GET['category'];
} else {
	$category = '';
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<title>Categoria</title>

</head>

<body>

	<?php include '../components/user_header.php'; ?>

	<main>
		<section class="posts-container">

			<h1 class="heading">Posts de la categoria</h1>

			<div class="box-container">

				<?php
				$select_posts = $conn->prepare("SELECT * FROM `posts` WHERE category = ? and status = ?");
				$select_posts->execute([$category, 'active']);
				if ($select_posts->rowCount() > 0) {
					while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

						$post_id = $fetch_posts['id'];

						$count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
						$count_post_comments->execute([$post_id]);
						$total_post_comments = $count_post_comments->rowCount();

						$count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
						$count_post_likes->execute([$post_id]);
						$total_post_likes = $count_post_likes->rowCount();

						$confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
						$confirm_likes->execute([$user_id, $post_id]);
						?>
						<form class="box" method="post">
							<input type="hidden" name="post_id" value="<?= $post_id; ?>">
							<input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
							<div class="post-admin">
								<div id="user-btn" class="user-icon icon-square">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
										class="bi bi-person-fill" viewBox="0 0 16 16">
										<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
									</svg>
								</div>
								<div>
									<a
										href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
									<div class="date"><?= $fetch_posts['date']; ?></div>
								</div>
							</div>
							<div class="post-content">
								<?php
								if ($fetch_posts['image'] != '') {
									?>
									<div class="post-cont-image">
										<img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
									</div>
									<?php
								} else {
									?>
									<div class="post-cont-image">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
											class="bi bi-file-earmark-text" viewBox="0 0 16 16">
											<path
												d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
											<path
												d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
										</svg>
									</div>
									<?php
								}
								?>
								<div class="post-info">
									<a href="view_post.php?post_id=<?= $post_id; ?>" class="link-post">
										<div class="post-title"><?= $fetch_posts['title']; ?></div>
										<span class="post-text content-150"><?= $fetch_posts['content']; ?></span>
									</a>
									<a href="category.php?category=<?= $fetch_posts['category']; ?>" class="post-cat">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
											class="bi bi-bookmark-fill" viewBox="0 0 16 16">
											<path
												d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2" />
										</svg>
										<span><?= $fetch_posts['category']; ?></span>
									</a>
								</div>
							</div>
							<div class="icons">
								<a href="view_post.php?post_id=<?= $post_id; ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
										class="bi bi-chat-fill" viewBox="0 0 16 16">
										<path
											d="M8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6-.097 1.016-.417 2.13-.771 2.966-.079.186.074.394.273.362 2.256-.37 3.597-.938 4.18-1.234A9 9 0 0 0 8 15" />
									</svg>
									<span>(<?= $total_post_comments; ?>)</span></a>
								<a type="submit" name="like_post">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
										class="bi bi-suit-heart-fill" viewBox="0 0 16 16" style="<?php if ($confirm_likes->rowCount() > 0) {
											echo 'color:var(--red);';
										} ?>  ">
										<path
											d="M 4 1 c 2.21 0 4 1.755 4 3.92 C 8 2.755 9.79 1 12 1 s 4 1.755 4 3.92 c 0 3.263 -3.234 4.414 -7 10.08 a 0.693 0.413 0 0 1 -2 0 C 3.234 9.334 0 8.183 0 4.92 C 0 2.755 1.79 1 4 1" />
									</svg>
									<span>(<?= $total_post_likes; ?>)</span></a>
							</div>
						</form>
						<?php
					}
				} else {
					echo '<p class="empty">No se encontraron posts para la categoria</p>';
				}
				?>
			</div>
		</section>
	</main>

	<?php include '../components/footer.php'; ?>

	<script src="../js/script.js"></script>

</body>

</html>