<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'admin') {
		header("Location:".BASE_URL);
	}

	$page = "admin_users";
?>

<body class="admin admin__sidebar-collapse">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<?php include_once 'includes/admin_sidebar.php'; ?>

	<div class="admin__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>Users Management</h1>
			</div>

			<div class="page-header__right">
				<button id="editUserBtn" class="btn btn-blue btn-icon btn-sm mr-1 hide"><i class="fa fa-pencil-square-o"></i> Edit Selected User</button>
				<button id="deleteUserBtn" class="btn btn-red btn-icon btn-sm mr-4 hide"><i class="fa fa-trash-o"></i> Delete Selected User</button>
				<button id="addUserBtn" class="btn btn-green btn-icon btn-sm"><i class="fa fa-user-plus"></i> Add New User</button>
			</div>
		</div>
	
		<div class="container">
			<div class="card admin__users">
				<div class="card__header">
					<h3>All Users</h3>
				</div>
				<div class="card__body table-responsive">
					<table id="usersTable" class="mb-3" width="100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Username</th>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Fixture Id</th>
								<th>System</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- create -->
	<div class="modal" id="userCreate">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#userCreate">&times;</button>
			<div class="modal-header">
				<h3>New User</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="userCreateForm" class="form">
					<div class="form__group required">
						<label for="username">Username</label>
						<input type="text" id="username" name="username" placeholder="Username" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="pass_confirmation">Password</label>
						<input type="password" name="pass_confirmation" id="pass_confirmation" placeholder="Password" data-validation="required" data-validation-error-msg="Required *" autocomplete="off">
					</div>
					<div class="form__group required">
						<label for="pass">Confirm Password</label>
						<input type="password" name="pass" id="pass" placeholder="Password" data-validation="confirmation" data-validation-error-msg="Password do not match" autocomplete="off">
					</div>
					<hr>
					<div class="form__group required">
						<label for="fullname">Fullname</label>
						<input type="text" id="fullname" name="fullname" placeholder="Firstname Lastname" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="email">Email</label>
						<input type="text" id="email" name="email" placeholder="Email" data-validation="email" data-validation-error-msg="Enter correct email address">
					</div>
					<div class="form__group required">
						<label for="role">Role</label>
						<select name="role" id="role" data-validation="required" data-validation-error-msg="Required *">
							<option selected disabled>Select a role</option>
							<option value="production">Production</option>
							<option value="finishing">Finishing</option>
							<option value="packaging">Packaging</option>
						</select>
					</div>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Create User</button>
						<button type="reset" class="btn btn-blue btn-darker sm-1-4"><i class="fa fa-repeat"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- edit -->
	<div class="modal" id="userEdit">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#userEdit">&times;</button>
			<div class="modal-header">
				<h3>Edit User</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="userEditForm" class="form">
					<div class="form__group required">
						<label for="username">Username</label>
						<input type="text" id="username" name="username" readonly>
					</div>
					<div class="form__group required">
						<label for="pass_confirmation">Password</label>
						<input type="password" name="pass_confirmation" id="pass_confirmation" placeholder="Password" autocomplete="off">
					</div>
					<div class="form__group required">
						<label for="pass">Confirm Password</label>
						<input type="password" name="pass" id="pass" placeholder="Password" data-validation="confirmation" data-validation-error-msg="Password do not match" autocomplete="off">
					</div>
					<hr>
					<div class="form__group required">
						<label for="fullname">Fullname</label>
						<input type="text" id="fullname" name="fullname" placeholder="Firstname Lastname" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="email">Email</label>
						<input type="text" id="email" name="email" placeholder="Email" data-validation="email" data-validation-error-msg="Enter correct email address">
					</div>
					<div class="form__group required">
						<label for="role">Role</label>
						<select name="role" id="role" data-validation="required" data-validation-error-msg="Required *">
							<option selected disabled>Select a role</option>
							<option value="production">Production</option>
							<option value="finishing">Finishing</option>
							<option value="packaging">Packaging</option>
						</select>
					</div>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Update User</button>
						<button type="reset" class="btn btn-blue btn-darker sm-1-4"><i class="fa fa-repeat"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- edit -->
	<div class="modal" id="userDelete">
		<div class="modal-contents modal-sm">
			<button class="modal-close" data-modal-close="#userDelete">&times;</button>
			<div class="modal-header">
				<h3>Confirm to delete user</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="userDeleteForm" class="form">
					<input type="hidden" name="username" value="">
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-red btn-icon"><i class="fa fa-check"></i> Confirm</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="js/vendor/datatables.min.js"></script>
	<script>
		
		$(function () {
			components.sidebar.init();
		});

		/* users */
		$(function () {

			/* selectors */
			var table = $('#usersTable tbody');
			var addBtn = $('#addUserBtn');
			var editBtn = $('#editUserBtn');
			var deleteBtn = $('#deleteUserBtn');
			var createModal = $('#userCreate');
			var editModal = $('#userEdit');
			var deleteModal = $('#userDelete');

			/* admin Users */
			var dataTable = $('#usersTable').DataTable({
				"ajax": base_url+'modules/get_users.php',
				"columns": [
		            { "data": "UserID" },
		            { "data": "Username" },
		            { "data": "Fullname" },
		            { "data": "Email" },
		            { "data": "Role" },
		            { "data": "Bay" },
		            { "data": "System" }
		        ]
			});

			/* modal */
			addBtn.on('click', function () {
				createModal.find('button[type="submit"]').text('Create User');
				components.form.reset('#userCreateForm');
				components.modal.openModal(createModal);
			});
		 
			/* create user */
			$('#userCreateForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/create_user.php',
					data: formData,
					method: 'post',
					dataType: 'json'
				}).done(function(d) {
					if (d.status=='ok') {
						components.nag.show(d.message, 'success');
						components.form.reset($this);
						dataTable.ajax.reload();
					} else if (d.status=='error') {
						components.nag.show(d.message, 'warn');
					}
				}).always(function () {
					components.loader.off($this);
				}).fail(function ( error ) {
					components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
					console.log(error);
				});
			});

			/* users row select */
			table.on( 'click', 'tr', function () {
		        if ( $(this).hasClass('selected') ) {
		            $(this).removeClass('selected');
		            editBtn.addClass('hide');
		            deleteBtn.addClass('hide');
		        }
		        else {
		            table.find('tr.selected').removeClass('selected');
		            $(this).addClass('selected');
		            editBtn.removeClass('hide');
		            deleteBtn.removeClass('hide');
		        }
		    } );

			/* edit */
			editBtn.on('click', function (e) {
				e.preventDefault();
				
				var tds = table.find('tr.selected td');
				
				editModal.find('input[name="username"]').val(tds.eq(1).text());
				editModal.find('input[name="fullname"]').val(tds.eq(2).text());
				editModal.find('input[name="email"]').val(tds.eq(3).text());
				editModal.find('[name="role"] option').each(function() {
					if($(this).val() == tds.eq(4).text()) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});

				components.modal.openModal(editModal);
			});

			/* edit user */
			$('#userEditForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/edit_user.php',
					data: formData,
					method: 'post',
					dataType: 'json'
				}).done(function(d) {
					if (d.status=='ok') {
						components.nag.show(d.message, 'success');
						components.form.reset($this);
						components.modal.closeModal(editModal);
						dataTable.ajax.reload();
						editBtn.addClass('hide');
		            	deleteBtn.addClass('hide');
					} else {
						components.nag.show('Failed to update cycle! Try again.', 'warn');
					}
				}).always(function () {
					components.loader.off($this);
				}).fail(function ( error ) {
					components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
					console.log(error);
				});
			});

			/* delete */
			deleteBtn.on('click', function (e) {
				e.preventDefault();
				
				var tds = table.find('tr.selected td');
				
				deleteModal.find('input[name="username"]').val(tds.eq(1).text());

				components.modal.openModal(deleteModal);
			});

			/* delete user */
			$('#userDeleteForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/delete_user.php',
					data: formData,
					method: 'post',
					dataType: 'json'
				}).done(function(d) {
					console.log(d);
					if (d.status=='ok') {
						components.nag.show(d.message, 'success');
						dataTable.ajax.reload();
					} else {
						components.nag.show('Failed to delete user! Try again.', 'warn');
					}
				}).always(function () {
					editBtn.addClass('hide');
	            	deleteBtn.addClass('hide');
					components.modal.closeModal(deleteModal);
					components.loader.off($this);
				}).fail(function ( error ) {
					components.nag.show('Problem connecting to server! Refresh, try again or contact administrator.', 'error');
					console.log(error);
				});
			});

		});

	</script>

	
</body>
</html>