<?php
	$current_user = "";

	if(isset($_SESSION['id'])) {
		$current_user = $_SESSION['fullname'];
		$user_id = $_SESSION['id'];
	}
?>

<nav class="nav">
	<div class="nav__content">
		<a href="<?= BASE_URL; ?>" class="nav__logo">
			<span>Schneider Electric</span>
		</a>

		<p class="nav__heading">Schneider SmartGen</p>

		<ul class="nav__links">
			<?php if($current_user): ?>
				<li><span class="pill pill-orange pill-outline user-name"><?= $current_user; ?></span></li>
				<li><a href="<?= BASE_URL; ?>/account.php" data-toggle="tooltip" title="Edit your account details."><i class="fa fa-user"></i></a></li>
				<li><a href="<?= BASE_URL; ?>modules/logout.php" data-toggle="tooltip" title="Sign out"><i class="fa fa-sign-out"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>
</nav>