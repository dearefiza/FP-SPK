<?php
require './connect.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Dimas Aldi Pratama">
	<title>Employee Evaluation System</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="asset/plugin/font-icon/css/fontawesome-all.min.css">
	<link rel="stylesheet" type="text/css" href="asset/css/style.css">
</head>
<body class="app-body">
<div class="app-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <?php include "nav.php"; ?>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <main class="content-area" style="padding-top: 15px;">
            <?php include "page.php"; ?>
        </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="asset/js/main.js" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var btn = document.getElementById('sidebarToggle');
	var wrapper = document.querySelector('.app-wrapper');
	if (btn && wrapper) {
		btn.addEventListener('click', function() {
			wrapper.classList.toggle('sidebar-collapsed');
		});
	}
});
</script>
</body>
</html>
