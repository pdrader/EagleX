<html>
<head>
	<title>EagleX.LLC</title>
</head>
<body>

	<div style="width: 90%; height:90%; text-align:center;">
		<?php
		/**
		* Script Name: PHP Form Login Remember Functionality with Cookies
		* Source: www.TutorialsClass.com
		**/
		?>
		<form action="page2.php" method="post" style="display: inline-block;border: 2px dotted blue; text-align:center; width: 400px;margin-top:50px;">
			<p>
				Username: <input name="username" type="text" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>" class="input-field">
			</p>
				 <p>Password: <input name="password" type="password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" class="input-field">
			</p>
				<p><input type="checkbox" name="remember" /> Remember me
			</p>
				<p><input type="submit" value="Login"></span></p>
		</form>

	<div>
</body>