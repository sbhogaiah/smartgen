<?php 
	// for sidebar active toggle
	$page = "account";
	
	// session
	require_once 'modules/session.php';
	
	// head 
	require_once 'includes/head.php'; 
	require_once 'includes/dbconnect.php'; 

	if(isset($_SESSION['id'])) {

		$user_id = $_SESSION['id'];

		$user_q = "SELECT * FROM users WHERE UserID='$user_id'";
		$user_data = mysqli_query($db, $user_q) or die('Cannot make connection to database!');
		$user_data = mysqli_fetch_assoc($user_data);

	}

?>

<body class="account">
	
	<div id="page"><!-- #page -->

		<?php // navigation
			require_once 'includes/nav.php'; 
		?>
		<!-- page-header -->
		<div class="page-header">
			<div class="page-header__left">
				<h1>Update Account Details
					<span class="pill pill-blue pill-outline ml-4"><?= $session_username; ?></span>
				</h1>
			</div>
		</div>
		<!-- contents -->
		<div class="container maxw-60">
			<!-- login-box -->
			<div class="card account-box cf">
				<div class="card__header">
					<h4>Account Information</h4>
				</div>
				<div class="card__body">
					<div class="account-update-box">
						<div id="updateUserInfo">
							<form action="<?= BASE_URL; ?>modules/account_update.php" method="POST" class="form">
								<div class="form__group">
									<label for="fullname">Update Full Name</label>
									<input type="text" id="fullname" name="fullname" value="<?php echo $user_data['Fullname']; ?>">
								</div>
								<div class="form__group required">
									<label for="user_pass_confirmation">Password</label>
									<input type="password" id="user_pass_confirmation" name="user_pass_confirmation" data-validation="required length" data-validation-length="min8" placeholder="new password">
								</div>
								<div class="form__group required">
									<label for="user_pass">Re-type Password</label>
									<input type="password" id="user_pass" name="user_pass" data-validation="confirmation" data-validation-error-msg="Password does not match" placeholder="repeat password">
								</div>
								<div class="form__group">
									<button class="btn btn-blue" type="submit">Update User Info</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div><!-- #page -->
	
	<script>
		
		$(function() {

			// submit
			$('form').on('submit', function (e) {
				e.preventDefault();

				components.card.loading( '.account-box' );

				var link = $(this).attr('action');
				var d = $(this).serialize();
				d = d + '&user_id='+<?php echo $user_id; ?>;

				$.ajax({
					url: link,
					data: d,
					method: 'POST',
					dataType: 'json',
					success: function ( d ) {
						if(d.status == "ok") {
							components.nag.show(d.message, 'success');
						}
					}
				}).always(function () {
					components.card.loaded( '.account-box' );
				}).fail(function ( error ) {
					components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
					console.log(error);
				});
			});

		});	

	</script>

</body>
</html>