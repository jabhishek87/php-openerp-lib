<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Console OpenERPlib</title>
		<meta name="author" content="Benito Rodriguez" />
		
		<style>
			label { 
				display: block;
				width: 85px;
				float: left;
				clear: both; 
			}
			
			input { 
				float: left;
				width: 250px;
			}
			
		</style>
	</head>
	<body>
		<div  style="margin: auto; width: 900px;">
			<header>
				<h1>Console</h1>
			</header>
			
			<form action="." method="post">
				<h2>Configuration</h2>
				<div style="height: 52px;">					
					<div style="width: 450px; float: left;">
						<label for="bd">BD: </label><input name="bd" id="bd" value="<?php if($_POST['bd']) print $_POST['bd']; else print "bd1"?>" />
						<label for="uid">UID: </label><input name="uid" id="uid" value="<?php if($_POST['uid']) print $_POST['uid']; else print "1"?>" />	
					</div>
					<div style="width: 450px; float: left;">
						<label for="passwd">Password: </label><input name="passwd" id="passwd" value="<?php if($_POST['passwd']) print $_POST['passwd']; else print "bd1"?>" />
						<label for="url">UID: </label><input name="url" id="url" value="<?php if($_POST['url']) print $_POST['url']; else print "http://localhost:8069/xmlrpc"?>"/>
					</div>
				</div>
	
				<h2>Ienter the code to run:: </h2>
				<small>the variable <code>$config</code> is replaced by the above values</small>
				<div>									
					<textarea style="width: 100%; height: 150px;" name="code">$open = new OpenERP($config);
$p = $open->res_partner->get(1);
print $p->id;</textarea>
						<button type="submit">execute</button>					
				</div>
			</form>
			
			<?php
				if (sizeof($_POST)) {
					include_once '../openerplib/openerplib.php';
					
					function my_exception_handler($e) {
    					print("<pre>$e</pre>");
					}

					function my_error_handler($no,$str,$file,$line) {
    					$e = new ErrorException($str,$no,0,$file,$line);
    					my_exception_handler($e);     /* Do not throw, simply call error handler with exception object */
					}
					
					set_error_handler('my_error_handler');
					set_exception_handler('my_exception_handler');

					$config = array(
	       				'bd'        => $_POST['bd'],
	       				'uid'       => $_POST['uid'],
	       				'passwd'    => $_POST['passwd'],
	       				'url'       => $_POST['url'],
	   				);
					$code = $_POST['code'];
										
					if (!$config['bd'] OR !$config['uid'] OR !$config['passwd'] OR !$config['url'] OR !$code) {
						echo '<p>Invalid configuration</p>';
					} else {
						/*$open = new OpenERP($config);
						$p = $open->res_partner->get(1);
						print $p->id;*/
						
						$code_replace = '$config = '.var_export($config, true).';
'.$code;
						
						print "<h2>result</h2>";
						print "<pre>$code_replace</pre>";
												
						eval($code_replace);

					}
				}
			?>

			<footer>
				<p>
					&copy; Copyright by Abhishek jaiswal
				</p>
			</footer>
		</div>
	</body>
</html>