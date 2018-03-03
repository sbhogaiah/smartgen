<div class="admin__sidebar">
	<button class="admin__sidebar-btn"><i class="fa fa-angle-double-left"></i></button>
	<h2 class="admin__sidebar-header"></h2>
	<ul class="admin__sidelinks">
		<li><a href="<?= BASE_URL; ?>admin_charts.php" class=<?php echo ($page == "admin_charts" ? "active" : "")?>><i class="fa fa-bar-chart-o"></i> <span>Charts</span></a></li>
		<li><a href="<?= BASE_URL; ?>admin_reports.php" class=<?php echo ($page == "admin_reports" ? "active" : "")?>><i class="fa fa-table"></i> <span>Reports</span></a></li>
		<?php if($session_role == "admin"): ?>
		<li><a href="<?= BASE_URL; ?>admin_cycles.php" class=<?php echo ($page == "admin_cycles" ? "active" : "")?>><i class="fa fa-clock-o"></i> <span>Cycles</span></a></li>
		<li><a href="<?= BASE_URL; ?>admin_users.php" class=<?php echo ($page == "admin_users" ? "active" : "")?>><i class="fa fa-users"></i> <span>Users</span></a></li>
		<li><a href="<?= BASE_URL; ?>admin_users_msgs.php" class=<?php echo ($page == "admin_users_msgs" ? "active" : "")?>><i class="fa fa-commenting"></i> <span>Message Receivers</span></a></li>	

		<?php endif; ?>
	</ul>
</div>