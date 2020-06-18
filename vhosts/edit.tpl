<html>
 <header>
	<title> Edit vhost </title>
  </header>
<body>
	<form action="/vhosts/index.php?action=update" method='POST'>
		<input type='text' name='domain' value="%domain%">
		<input type='text' name='path' value='%path%'>
		<input type='submit' value='Update domain'>
	</form>
</body>
</html>
	