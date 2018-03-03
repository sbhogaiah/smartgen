<?php 
	session_start();
	
	// IF ALREADY LOGGED IN
	if(isset($_SESSION['id'])) {
		$session_username = $_SESSION['username'];
		$session_fullname = $_SESSION['fullname'];
		$session_userid = $_SESSION['id'];
		$session_role = $_SESSION['role'];
	}

	include_once 'includes/head.php';
?>

<body class="home">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<div class="home__content">
		<div class="home__top">
			<div class="home__heading">
				<h1>Welcome to Schneider Electric</h1>
				<p>World Class Testing Infrastructure</p>
			</div>
		</div>
		
		<div class="home__bottom">
			<div class="login-box">
			<?php if (!isset($session_role)): ?>		
<!-- 				<div class="login-box__heading">
					<h2>Login</h2>
					<p>Choose your role</p>
				</div> -->

				<div class="login-box__buttons">
					<ul>
						<li>
							<a href="#" data-login-role="production">
								<img src="<?= BASE_URL; ?>img/production.png" alt="Production Icon">
							</a>
						</li>
						<li>
							<a href="#" data-login-role="testing">
								<img src="<?= BASE_URL; ?>img/testing.png" alt="Testing Icon">
							</a>
						</li>
						<li>
							<a href="#" data-login-role="finishing">
								<img src="<?= BASE_URL; ?>img/finishing.png" alt="Finishing Icon">
							</a>
						</li>
						<li>
							<a href="#" data-login-role="packaging">
								<img src="<?= BASE_URL; ?>img/packaging.png" alt="Packaging Icon">
							</a>
						</li>
						<li>
							<a href="#" data-login-role="admin">
								<img src="<?= BASE_URL; ?>img/admin.png" alt="Admin Icon">
							</a>
						</li>
						<li>
							<a href="#" data-login-role="guest">
								<img src="<?= BASE_URL; ?>img/dashboard.png" alt="Guest Icon">
							</a>
						</li>
					</ul>

				</div>
				
				<div class="login-box__form">
					<button class="login-box__form-close">
						<i class="fa fa-angle-left"></i> Close
					</button>
					<div class="login-box__form-heading">
						<h4>Enter your credentials</h4>
						<p class="pill pill-blue pill-outline">Role Selected: <b class="login-box__form-role"></b></p>
					</div>
					<form action="<?= BASE_URL; ?>modules/login.php" method="POST" class="form" id="loginForm">
						<div class="form__group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" placeholder="Your Id" data-validation="required">
						</div>
						<div class="form__group">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" placeholder="Password" data-validation="required">
						</div>
						<div class="form__group">
							<a id="reset_pass_link" href="reset_password.php">Forgot Password?</a>
						</div>
						<div class="form__group">
							<input type="hidden" name="role" id="loginRole" value="null">
							<button type="submit" class="btn btn-orange btn-outline">Login</button>
						</div>
					</form>
				</div>
			<?php else: ?>
				<div class="logged-in-box">
					<h3>You are already logged in.</h3>
					<p>
						<?php if ($_SESSION['role']!='guest' && $_SESSION['role']!='admin'): ?>
						<a class="tt-c btn btn-blue" href="<?= BASE_URL.$session_role.'.php'; ?>" class="btn btn-blue">Back to <?= $session_role; ?></a> 
						<?php else: ?>
						<a class="tt-c btn btn-blue" href="<?= BASE_URL.'admin_charts.php'; ?>" class="btn btn-blue">Back to <?= $session_role; ?></a> 
						<?php endif; ?>
					</p>
					<p>Or, <a class="logout-link" href="<?= BASE_URL; ?>modules/logout.php">logout</a> and login using different user.</p>
				</div>
			<?php endif; ?>
			</div>
		</div>
		
		<footer class="footer">
			<p class="footer__text">Welcome to My Schneider Electric. This portal is for recording the data of products produced and tested at IDF1 factory floor at Bangalore. If you need any technical assistance, please contact the administrator.</p>
		</footer>
	</div>
	
	<script src="js/home.js"></script>
</body>
</html>