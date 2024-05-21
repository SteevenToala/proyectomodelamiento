<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
} else {
	$user_id = '';
}

include '../components/like_post.php';

$get_id = $_GET['post_id'];

if (isset($_POST['add_comment'])) {

	$admin_id = $_POST['user_id'];
	$admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
	$user_name = $_POST['user_name'];
	$user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
	$comment = $_POST['comment'];
	$comment = filter_var($comment, FILTER_SANITIZE_STRING);

	$verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND admin_id = ? AND user_id = ? AND user_name = ? AND comment = ?");
	$verify_comment->execute([$get_id, $admin_id, $user_id, $user_name, $comment]);

	if ($verify_comment->rowCount() > 0) {
		$message[] = 'Comentario agregado!';
	} else {
		$insert_comment = $conn->prepare("INSERT INTO `comments`(post_id, admin_id, user_id, user_name, comment) VALUES(?,?,?,?,?)");
		$insert_comment->execute([$get_id, $admin_id, $user_id, $user_name, $comment]);
		$message[] = 'Comentario agregado correctamente!';
	}

}

if (isset($_POST['edit_comment'])) {
	$edit_comment_id = $_POST['edit_comment_id'];
	$edit_comment_id = filter_var($edit_comment_id, FILTER_SANITIZE_STRING);
	$comment_edit_box = $_POST['comment_edit_box'];
	$comment_edit_box = filter_var($comment_edit_box, FILTER_SANITIZE_STRING);

	$verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment = ? AND id = ?");
	$verify_comment->execute([$comment_edit_box, $edit_comment_id]);

	if ($verify_comment->rowCount() > 0) {
		$message[] = 'Comentario editado!';
	} else {
		$update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
		$update_comment->execute([$comment_edit_box, $edit_comment_id]);
		$message[] = 'Comentario editado correctamente!';
	}
}

if (isset($_POST['delete_comment'])) {
	$delete_comment_id = $_POST['comment_id'];
	$delete_comment_id = filter_var($delete_comment_id, FILTER_SANITIZE_STRING);
	$delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
	$delete_comment->execute([$delete_comment_id]);
	$message[] = 'Comentario eliminado correctamente!';
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
	<link rel="stylesheet" href="../css/style_header.css">
	<title>Ver post</title>

</head>

<body>
	<?php include '../components/user_header.php'; ?>

	<main id="main">
		<?php
		if (isset($_POST['open_edit_box'])) {
			$comment_id = $_POST['comment_id'];
			$comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);
			?>
			<section class="comment-edit-form">
				<p>edit your comment</p>
				<?php
				$select_edit_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
				$select_edit_comment->execute([$comment_id]);
				$fetch_edit_comment = $select_edit_comment->fetch(PDO::FETCH_ASSOC);
				?>
				<form action="" method="POST">
					<input type="hidden" name="edit_comment_id" value="<?= $comment_id; ?>">
					<textarea name="comment_edit_box" required cols="30" rows="10"
						placeholder="please enter your comment"><?= $fetch_edit_comment['comment']; ?></textarea>
					<button type="submit" class="inline-btn" name="edit_comment">edit comment</button>
					<div class="inline-option-btn"
						onclick="window.location.href = 'view_post.php?post_id=<?= $get_id; ?>';">
						cancel edit</div>
				</form>
			</section>
			<?php
		}
		?>


		<section class="posts-container">

			<div class="box-container">

				<?php
				$select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? AND id = ?");
				$select_posts->execute(['activo', $get_id]);
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
							<input type="hidden" name="admin_id" value="<?= $fetch_posts['user_id']; ?>">
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
									<a class="link-post">
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
								<a type="submit" name="like_post">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
										class="bi bi-suit-heart-fill" viewBox="0 0 16 16" style="<?php if ($confirm_likes->rowCount() > 0) {
											echo 'color:var(--red);';
										} ?>  ">
										<path
											d="M 4 1 c 2.21 0 4 1.755 4 3.92 C 8 2.755 9.79 1 12 1 s 4 1.755 4 3.92 c 0 3.263 -3.234 4.414 -7 10.08 a 0.693 0.413 0 0 1 -2 0 C 3.234 9.334 0 8.183 0 4.92 C 0 2.755 1.79 1 4 1" />
									</svg>
									<span>(<?= $total_post_likes; ?>)</span>
								</a>
							</div>
						</form>
						<?php
					}
				} else {
					echo '<p class="empty">Sin post!</p>';
				}
				?>
			</div>

		</section>

		<section class="comments-container">
			<div class="comments">
				<p class="comment-title">Comentarios</p>
				<div class="user-comments-container">
					<?php
					$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
					$select_comments->execute([$get_id]);
					if ($select_comments->rowCount() > 0) {
						while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
							?>
							<div class="show-comments" style="<?php if ($fetch_comments['user_id'] == $user_id) {
								echo 'order:-1;';
							} ?>">
								<div class="comment-user">
									<div id="user-btn" class="user-icon icon-square">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
											class="bi bi-person-fill" viewBox="0 0 16 16">
											<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
										</svg>
									</div>
									<div>
										<a class="author"
											href="author_posts.php?author=<?= $fetch_comments['user_name']; ?>"><?= $fetch_comments['user_name']; ?></a>
										<div class="date"><?= $fetch_comments['date']; ?></div>
									</div>
								</div>
								<div class="comment-box" style="<?php if ($fetch_comments['user_id'] == $user_id) {
									echo 'color:var(--light-color); background:var(--text);';
								} ?>">
									<?= $fetch_comments['comment']; ?>
									<?php
									if ($fetch_comments['user_id'] == $user_id) {
										?>
										<form action="" method="POST" class="options">
											<input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
											<button type="submit" name="open_edit_box" class="icon-pen">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
													class="bi bi-pencil-fill" viewBox="0 0 16 16">
													<path
														d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
												</svg>
											</button>
											<button type="submit" name="delete_comment" class="icon-menu"
												onclick="return confirm('Confirme la eliminación');">
												<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
													class="bi bi-trash-fill" viewBox="0 0 16 16">
													<path
														d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
												</svg>
											</button>
										</form>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
					} else {
						echo '<p class="empty">Sin comentarios!</p>';
					}
					?>
				</div>
			</div>

			<div class="add-comment-cont">
				<?php
				if ($user_id != '') {
					?>
					<p class="comment-title">
						<span>Comentar como:</span>
						<span><?= $fetch_profile['name']; ?></span>
					</p>
					<?php
					$select_admin_id = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
					$select_admin_id->execute([$get_id]);
					$fetch_admin_id = $select_admin_id->fetch(PDO::FETCH_ASSOC);
					?>
					<form action="" method="post" class="add-comment">
						<input type="hidden" name="user_id" value="<?= $fetch_admin_id['user_id']; ?>">
						<input type="hidden" name="user_name" value="<?= $fetch_profile['name']; ?>">
						<textarea name="comment" maxlength="1000" class="comment-box" cols="30" rows="10"
							placeholder="Escribe tu comentario" required></textarea>
						<input type="submit" value="Publicar" class="inline-btn" name="add_comment">
					</form>
					<?php
				} else {
					?>
					<div class="add-comment">
						<p>Inicia sesión para poder comentar</p>
						<a href="login.php" class="inline-btn">Iniciar sesión</a>
					</div>
					<?php
				}
				?>
			</div>
		</section>
	</main>
	<?php include '../components/footer.php'; ?>

	<script src="../js/header_script.js"></script>
	<script src="../js/script.js"></script>


</body>

</html>