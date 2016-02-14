<?php
	echo('<input type="hidden" name="action" value="login">');
	echo('<input class="form-control" type="email" name="email" placeholder="Email Address" required><br>');
	echo('<input class="form-control" type="password" name="pw" placeholder="Password" required><br>');
	echo('<input class="btn btn-primary" type="submit" value="Log In">');
?>