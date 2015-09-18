<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Product Details</title>
	<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
	<div class="header">
		<a href="/dashboard">Home</a>
		<a href="/logout">Logout</a>
	</div>
	<h2><?= $product['name'] ?></h2>
	<p>Users who added this product/item to their wishlist:</p>
	<?php foreach ($product_users as $user) { ?>
		<p><?= $user['name'] ?></p>
	<?php } ?>
</body>
</html>