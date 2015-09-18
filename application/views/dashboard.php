<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
	<title>Wishlist Dashboard</title>
	<link rel="stylesheet" href="/asset/styles.css">
</head>
<body>
	<div class="header">
		<h2 class="welcomeMsg">Welcome <?= $userdata['name'] ?>!</h2>
		<a href="logout">Logout</a>
	</div>
	<div class="userList">
		<p>Your Wish List:</p>
			<table>
				<thead>
					<th>Item</th>
					<th>Added by</th>
					<th>Date Added</th>
					<th>Action</th>
				</thead>
				<tbody>
					<?php foreach ($user_products as $product) { ?>
						<tr>
							<td><a href="wish_items/<?= $product['id']?>"><?= $product['name'] ?></a></td>
							<td><?= $product['added_by'] ?></td>
							<td><?= $product['date_added'] ?></td>
							<td><?php if($product['added_by_id'] === $userdata['id']){ ?>
								<!-- remember this REMOVES item from the database and all dependencies - that seems so cruel ;p -->
									<a href="delete/<?= $product['id'] ?>">Delete</a>
							<?php } else { ?>
									<a href="remove/<?= $product['id'] ?>">Remove from my Wishlist</a>
							<?php }?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
	</div>
	<div class="nonList">
		<p>Other user's wishlist:</p>
		<table>
			<thead>
				<th>Item</th>
				<th>Added by</th>
				<th>Date Added</th>
				<th>Action</th>
			</thead>
			<tbody>
				<?php foreach ($other_products as $product) { ?>
					<tr>
						<td><a href="wish_items/<?= $product['id'] ?>"><?= $product['name'] ?></a></td>
						<td><?= $product['added_by'] ?></td>
						<td><?= $product['date_added'] ?></td>
						<td><a href="update/<?= $product['id'] ?>">Add to my Wishlist</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<a href="wish_items/create">Add Item</a>
</body>
</html>