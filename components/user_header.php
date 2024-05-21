<?php
if (isset($message)) {
	foreach ($message as $message) {
		echo '
	<div class="message" id="message">
		<div class="stat-bar"></div>
		<span>' . $message . '</span>
		<svg id="close-popup" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
  			<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
		</svg>
	</div>';
	}
}
?>

<header class="header">
	<section class="flex">

		<a href="home.php" class="logo">BLOGGER</a>

		<form action="#" method="POST" class="search-form">
			<input type="text" name="search_box" class="box" maxlength="100" placeholder="Buscar blogs" required>
			<button type="submit" class="search" name="search_btn">
				<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" fill="currentColor" width="16" height="16"
					viewBox="0 0 50 50">
					<path
						d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z">
					</path>
				</svg>
			</button>
		</form>

		<div class="profile">
			<?php
			$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
			$select_profile->execute([$user_id]);
			if ($select_profile->rowCount() > 0) {
				$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
				?>
				<p class="name"><?= $fetch_profile['name']; ?></p>
				<a href="../pages/profile.php" class="btn">Perfil</a>
				<a href="../components/user_logout.php" onclick="return confirm('Confirma la salida');"
					class="delete-btn">Desconectarse</a>
				<?php
			} else {
				?>
				<a href="login.php" class="option-btn">Iniciar sesión</a>
				<?php
			}
			?>
		</div>

		<div class="icons">
			<?php
			if ($select_profile->rowCount() > 0) {
				?>
				<div id="menu-btn" class="bars-icon icon-square">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list"
						viewBox="0 0 16 16">
						<path fill-rule="evenodd"
							d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
					</svg>
				</div>
				<div id="user-btn" class="user-icon icon-square">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
						class="bi bi-person-fill" viewBox="0 0 16 16">
						<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
					</svg>
				</div>
				<?php
			} else {
				?>
				<div class="flex-btn">
					<a href="login.php" class="option-btn">Login</a>
					<a href="register.php" class="option-btn">Registro</a>
				</div>
			<?php }
			?>
		</div>

		<nav class="navbar">
			<a href="home.php">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
					class="bi bi-caret-right-fill" viewBox="0 0 16 16">
					<path
						d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
				</svg>
				<span>Home</span>
			</a>
			<a href="all_category.php">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
					class="bi bi-caret-right-fill" viewBox="0 0 16 16">
					<path
						d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
				</svg>
				<span>Categorias</span>
			</a>
			
		</nav>

	</section>

</header>