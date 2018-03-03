<?php 
	require_once 'modules/session.php';
	include_once 'includes/head.php';

	if ($session_role !== 'admin') {
		header("Location:".BASE_URL);
	}

	$page = "admin_cycles";
?>

<body class="admin admin__sidebar-collapse">
	
	<?php include_once 'includes/nav.php'; ?>
	
	<?php include_once 'includes/admin_sidebar.php'; ?>

	<div class="admin__content">
		
		<div class="page-header">
			<div class="page-header__left">
				<h1>Cycles Management</h1>
			</div>

			<div class="page-header__right">
				<button id="editCycleBtn" class="btn btn-blue btn-icon btn-sm mr-1 hide"><i class="fa fa-pencil-square-o"></i> Edit Selected Cycle</button>
				<button id="deleteCycleBtn" class="btn btn-red btn-icon btn-sm mr-4 hide"><i class="fa fa-trash-o"></i> Delete Selected Cycle</button>
				<button id="addCycleBtn" class="btn btn-green btn-icon btn-sm" data-modal-open="#cycleCreate"><i class="fa fa-clock-o"></i> Add New Cycle</button>
			</div>
		</div>
	
		<div class="container">
			<div class="card admin__cycles">
				<div class="card__header">
					<h3>All Cycles</h3>
				</div>
				<div class="card__body table-responsive">
					<table id="cyclesTable" class="mb-3" width="100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Model Id</th>
								<th>Family</th>
								<th>Rating</th>
								<th>Hipot CT</th>
								<th>Hipot Tol</th>
								<th>LP CT</th>
								<th>LP Tol</th>
								<th>BI CT</th>
								<th>BI Tol</th>
								<th>FCT CT</th>
								<th>BI Tol</th>
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
	<div class="modal" id="cycleCreate">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#cycleCreate">&times;</button>
			<div class="modal-header">
				<h3>New Test Cycle</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="cycleCreateForm" class="form">
					<div class="form__group required">
						<label for="model">SKU ID</label>
						<input type="text" id="model" name="model" placeholder="SKU ID" data-validation="required" data-validation-error-msg="Required *">
					</div>
					<div class="form__group required">
						<label for="family">Product Family</label>
						<input type="text" name="family" id="family" value="Solar" data-validation="required" data-validation-error-msg="Required *" autocomplete="off">
					</div>
					<div class="form__group required">
						<label for="rating">Rating (kVA)</label>
						<input type="text" name="rating" id="rating" value="1200" data-validation="required" data-validation-error-msg="Required *" autocomplete="off">
					</div>
					<table>
						<tr>
							<td>
								<div class="form__group required">
									<label for="hipot_ct">Hipot Cycle Time</label>
									<input type="text" name="hipot_ct" id="hipot_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="hipot_tol">Hipot Tolerance</label>
									<input type="text" name="hipot_tol" id="hipot_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="lp_ct">Low Power Cycle Time</label>
									<input type="text" name="lp_ct" id="lp_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="lp_tol">Low Power Tolerance</label>
									<input type="text" name="lp_tol" id="lp_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="bi_ct">Burn In Cycle Time</label>
									<input type="text" name="bi_ct" id="bi_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="bi_tol">Burn In Tolerance</label>
									<input type="text" name="bi_tol" id="bi_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="fct_ct">FCT Cycle Time</label>
									<input type="text" name="fct_ct" id="fct_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="fct_tol">FCT Tolerance</label>
									<input type="text" name="fct_tol" id="fct_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
						</tr>
					</table>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Create Cycle</button>
						<button type="reset" class="btn btn-blue btn-darker sm-1-4"><i class="fa fa-repeat"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- edit -->
	<div class="modal" id="cycleEdit">
		<div class="modal-contents">
			<button class="modal-close" data-modal-close="#cycleEdit">&times;</button>
			<div class="modal-header">
				<h3>Edit Test Cycle</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="cycleEditForm" class="form">
					<div class="form__group required">
						<label for="model">SKU ID</label>
						<input type="text" id="model" name="model" readonly>
					</div>
					<div class="form__group required">
						<label for="family">Product Family</label>
						<input type="text" name="family" id="family" readonly>
					</div>
					<div class="form__group required">
						<label for="rating">Rating (kVA)</label>
						<input type="text" name="rating" id="rating" readonly>
					</div>
					<table>
						<tr>
							<td>
								<div class="form__group required">
									<label for="hipot_ct">Hipot Cycle Time</label>
									<input type="text" name="hipot_ct" id="hipot_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="hipot_tol">Hipot Tolerance</label>
									<input type="text" name="hipot_tol" id="hipot_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="lp_ct">Low Power Cycle Time</label>
									<input type="text" name="lp_ct" id="lp_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="lp_tol">Low Power Tolerance</label>
									<input type="text" name="lp_tol" id="lp_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="bi_ct">Burn In Cycle Time</label>
									<input type="text" name="bi_ct" id="bi_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="bi_tol">Burn In Tolerance</label>
									<input type="text" name="bi_tol" id="bi_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
							<td>
								<div class="form__group required">
									<label for="fct_ct">FCT Cycle Time</label>
									<input type="text" name="fct_ct" id="fct_ct" data-cycle-time="cycle_time" placeholder="hh:mm" data-validation="required time" data-validation-error-msg="Select time *" autocomplete="off">
								</div>
								<div class="form__group required">
									<label for="fct_tol">FCT Tolerance</label>
									<input type="text" name="fct_tol" id="fct_tol" placeholder="mins" data-validation="required number" data-validation-error-msg="Enter minutes *">
								</div>
							</td>
						</tr>
					</table>
					<div class="btn-group maxw-50 center-block">
						<button type="submit" class="btn btn-blue sm-3-4">Update Cycle</button>
						<button type="reset" class="btn btn-blue btn-darker sm-1-4"><i class="fa fa-repeat"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- edit -->
	<div class="modal" id="cycleDelete">
		<div class="modal-contents modal-sm">
			<button class="modal-close" data-modal-close="#cycleDelete">&times;</button>
			<div class="modal-header">
				<h3>Confirm to delete cycle</h3>
			</div>
			<div class="modal-body">
				<form action="#" method="POST" id="cycleDeleteForm" class="form">
					<input type="hidden" name="model" value="">
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

		$(function () {

			// datetimepicker
			$('[data-cycle-time="cycle_time"]').datetimepicker({
			  datepicker:false,
			  format:'H:i'
			});

			/* selectors */
			var table = $('#cyclesTable tbody');
			var addBtn = $('#addCycleBtn');
			var editBtn = $('#editCycleBtn');
			var deleteBtn = $('#deleteCycleBtn');
			var createModal = $('#cycleCreate');
			var editModal = $('#cycleEdit');
			var deleteModal = $('#cycleDelete');

			/* admin Cycles */
			var dataTable = $('#cyclesTable').DataTable({
				"ajax": base_url+'modules/get_cycles.php',
				"columns": [
		            { "data": "CycleId" },
		            { "data": "ModelId" },
		            { "data": "Family" },
		            { "data": "Rating" },
		            { "data": "HipotCT" },
		            { "data": "HipotTol" },
		            { "data": "LowPowerCT" },
		            { "data": "LowPowerTol" },
		            { "data": "BurnInCT" },
		            { "data": "BurnInTol" },
		            { "data": "FctCT" },
		            { "data": "FctTol" }
		        ]
			});

			/* modal */
			addBtn.on('click', function () {
				createModal.find('button[type="submit"]').text('Create Cycle');
				components.form.reset('#cycleCreateForm');
				components.modal.openModal(createModal);
			});
		 
			/* create cycle */
			$('#cycleCreateForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/create_cycle.php',
					data: formData,
					method: 'post',
					dataType: 'json'
				}).done(function(d) {
					if (d.status=='ok') {
						components.nag.show(d.message, 'success');
						components.form.reset($this);
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

			/* cycles row select */
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
				
				editModal.find('input[name="model"]').val(tds.eq(1).text());
				editModal.find('input[name="family"]').val(tds.eq(2).text());
				editModal.find('input[name="rating"]').val(tds.eq(3).text());
				editModal.find('input[name="hipot_ct"]').val(components.time.toHHmm(tds.eq(4).text()));
				editModal.find('input[name="hipot_tol"]').val(tds.eq(5).text());
				editModal.find('input[name="lp_ct"]').val(components.time.toHHmm(tds.eq(6).text()));
				editModal.find('input[name="lp_tol"]').val(tds.eq(7).text());
				editModal.find('input[name="bi_ct"]').val(components.time.toHHmm(tds.eq(8).text()));
				editModal.find('input[name="bi_tol"]').val(tds.eq(9).text());
				editModal.find('input[name="fct_ct"]').val(components.time.toHHmm(tds.eq(10).text()));
				editModal.find('input[name="fct_tol"]').val(tds.eq(11).text());

				components.modal.openModal(editModal);
			});

			/* edit cycle */
			$('#cycleEditForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/edit_cycle.php',
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
				
				deleteModal.find('input[name="model"]').val(tds.eq(1).text());

				components.modal.openModal(deleteModal);
			});

			/* delete cycle */
			$('#cycleDeleteForm').on('submit', function(e) {
				e.preventDefault();

				var $this = $(this);
				var formData = $(this).serialize();

				components.loader.on($this);
				
				$.ajax({
					url: base_url + 'modules/delete_cycle.php',
					data: formData,
					method: 'post',
					dataType: 'json'
				}).done(function(d) {
					console.log(d);
					if (d.status=='ok') {
						components.nag.show(d.message, 'success');
						dataTable.ajax.reload();
					} else {
						components.nag.show('Failed to delete cycle! Try again.', 'warn');
					}
				}).always(function () {
					components.modal.closeModal(deleteModal);
					editBtn.addClass('hide');
		            deleteBtn.addClass('hide');
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