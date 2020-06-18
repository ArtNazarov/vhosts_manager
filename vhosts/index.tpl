<html>
<header>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title> Vhosts gen </title>
</header>
<body>
<div class="container-fluid">
 <h3>List of domains</h3>
 <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Domain</th>
      <th scope="col">Path</th>
      <th scope="col">Action</th>
	  <th scope="col">Action</th>
    </tr>
  </thead>
 %contents%
 </table>
 <hr/>
 <button id='actNew'>Add new domain</button>
 <h3>vhosts result:</h3>
 <textarea cols='85' rows='24'>
 %vhosts%
 </textarea>
 <hr/>
  <button id='actWriteConf'>Write conf</button>
 </div>
 <script>
 document.getElementById('actNew').onclick = function(){
		document.location.href='/vhosts/index.php?action=new';
 }
 document.getElementById('actWriteConf').onclick = function(){
 document.location.href='/vhosts/index.php?action=write_vhosts';
 }
 </script>
</body>
</html>