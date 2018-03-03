<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'admin') {
		header("Location:".BASE_URL);
	}

	$page = "admin_users_msgs";
?>

<body class="admin admin__sidebar-collapse">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<?php include_once 'includes/admin_sidebar.php'; ?>

	<div class="admin__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>Message Receivers Configuration</h1>
			</div>

			<div class="page-header__right">
				<button id="editUserBtn" class="btn btn-blue btn-icon btn-sm mr-1 hide"><i class="fa fa-pencil-square-o"></i> Edit Selected Receiver</button>
				<button id="deleteUserBtn" class="btn btn-red btn-icon btn-sm mr-4 hide"><i class="fa fa-trash-o"></i> Delete Selected Receiver</button>
				<button id="addUserBtn" class="btn btn-green btn-icon btn-sm"><i class="fa fa-user-plus"></i> Add New Receiver</button>
			</div>
		</div>
	
		<div class="container">
			<div class="card admin__users">
				<div class="card__header">
					<h3>All Receivers</h3>
				</div>
				<div class="card__body table-responsive">
					<table id="usersTable" class="mb-3" width="100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Department</th>
								<th>Phone</th>
								<th>Email</th>
								<th>BayDown</th>
								<th>PowerFailure</th>
								<th>UnitFailure</th>
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
				<h3>New Message Receiver</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="userCreateForm" class="form">

					<div class="form__group required">
						<label for="user_name">Name</label>
						<input type="text" id="user_name" name="user_name" placeholder="Name" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="user_dept">Department</label>
						<input type="text" id="user_dept" name="user_dept" placeholder="Department" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="user_phone">Phone</label>
						<input type="text" id="user_phone" name="user_phone" placeholder="Phone" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="user_email">Email</label>
						<input type="text" id="user_email" name="user_email" placeholder="Email" data-validation="email" data-validation-error-msg="Enter correct email address">
					</div>
					<table>
						<tr>
							<td>
							<div class="form__group required">
								<label for="user_role_baydown">BayDown</label>
								<select name="user_role_baydown" id="user_role_baydown" data-validation="required">
										<option disabled selected value="">Select Option</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
								</select>
							</div>
							</td>
							<td>
							<div class="form__group required">
								<label for="user_role_powerfailure">PowerFailure</label>
								<select name="user_role_powerfailure" id="user_role_powerfailure" data-validation="required">
										<option disabled selected value="">Select Option</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
								</select>
							</div>
							</td>
							<td>
							<div class="form__group required">
								<label for="user_role_unitfailure">UnitFailure</label>
								<select name="user_role_unitfailure" id="user_role_unitfailure" data-validation="required">
										<option disabled selected value="">Select Option</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
								</select>
							</div>
							</td>
						</tr>										
					</table>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Create Receiver</button>
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
				<h3>Edit Revei</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="userEditForm" class="form">
					<div class="form__group required">
						<label for="fullname">Name</label>
						<input type="text" id="user_name" name="user_name" readonly>
					</div>
					<div class="form__group required">
						<label for="fullname">Department</label>
						<input type="text" id="user_dept" name="user_dept" placeholder="Department" data-validation="required" >
					</div>
					<div class="form__group required">
						<label for="fullname">Phone</label>
						<input type="text" id="user_phone" name="user_phone" placeholder="Phone" data-validation="required" >
					</div>					

					<div class="form__group required">
						<label for="email">Email</label>
						<input type="text" id="user_email" name="user_email" placeholder="Email" data-validation="email" data-validation-error-msg="Enter correct email address">
					</div>
					<table>
						<tr>
							<td>
								<div class="form__group required">
									<label for="user_role_baydown">BayDown</label>						
									<select name="user_role_baydown" id="user_role_baydown">
										<option disabled selected value="">Select </option>
										<option value="yes">Yes</option>
										<option value="no">No</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="user_role_powerfailure">PowerFailure</label>						
									<select name="user_role_powerfailure" id="user_role_powerfailure" data-validation="required">
										<option disabled selected value="">Select </option>
										<option value="yes">Yes</option>
										<option value="no">No</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="user_role_unitfailure">UnitFailure</label>						
									<select name="user_role_unitfailure" id="user_role_unitfailure" data-validation="required">
										<option disabled selected value="">Select </option>
										<option value="yes">Yes</option>
										<option value="no">No</option>
									</select>
								</div>					
							</td>
						</tr>
					</table>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Update Receiver</button>
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
					<input type="hidden" name="user_name" value="">
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
				"ajax": base_url+'modules/get_users_msg.php',
				"columns": [
					{ "data": "UserID" },
		            { "data": "Name" },
		            { "data": "Department" },
		            { "data": "Phone" },
		            { "data": "Email" },
		            { "data": "BayDown" },
		            { "data": "PowerFailure" },
		            { "data": "UnitFailure" }
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
					url: base_url + 'modules/create_user_msg.php',
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

				editModal.find('input[name="user_name"]').val(tds.eq(1).text());
				editModal.find('input[name="user_dept"]').val(tds.eq(2).text());
				editModal.find('input[name="user_phone"]').val(tds.eq(3).text());
				editModal.find('input[name="user_email"]').val(tds.eq(4).text());
				editModal.find('[name="user_role_baydown"] option').each(function() {
					if($(this).val() == tds.eq(5).text()) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});
				editModal.find('[name="user_role_powerfailure"] option').each(function() {
					if($(this).val() == tds.eq(6).text()) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});
				editModal.find('[name="user_role_unitfailure"] option').each(function() {
					if($(this).val() == tds.eq(7).text()) {
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
					url: base_url + 'modules/edit_user_msg.php',
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
				
				deleteModal.find('input[name="user_name"]').val(tds.eq(1).text());

				components.modal.openModal(deleteModal);
			});

			/* delete user */
			$('#userDeleteForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
								
				$.ajax({
					url: base_url + 'modules/delete_user_msg.php',
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