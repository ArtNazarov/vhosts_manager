<html>
	<header>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title> Add vhost </title>
	</header>
<body>
<div class="container-fluid">
<div class="row">
		<div class="col-md-6 offset-md-3">
	<h1>Input data for new domain</h1>
	<form action="/vhosts/index.php?action=add" method='POST'>
		<label for='domain'>Domain</label>
		<input type='text' name='domain'>
		<label for='path'>Path</label>
		<input type='text' name='path'>
		<input type='submit' value='add domain'>
	</form>
	</div>
	</div>
</div>
</body>
</html>
	