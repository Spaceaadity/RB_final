<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add an Item</title>
	<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
	<div class="header">
		<a href="/dashboard">Home</a>
		<a href="/logout">Logout</a>
	</div>
	<h1>Create a New Wish List Item</h1>
	<div class="flashdata">
		<p class="errors"><?= $this->session->flashdata('errors') ?></p>
	</div>
	<form action="create/item" method="post">
		<p><label>Item/Product:</label>	<input type="text" name="product"></p>
		<input type="submit" value="Add">
	</form>
</body>
</html>