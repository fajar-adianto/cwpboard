<?php
	echo('<input type="hidden" name="action" value="signup">');
	echo('<input class="form-control" type="email" name="email" placeholder="Email Address" required><br>');
	echo('<input class="form-control" type="password" name="pw" placeholder="Password" required><br>');
	echo('<input class="form-control" type="text" name="fname" placeholder="First Name" required><br>');
	echo('<input class="form-control" type="text" name="lname" placeholder="Last Name" required><br>');
	echo('<div class="form-group">');
	echo('<label class="radio-inline">');
	echo('<input type="radio" name="gender" value="Male" required>Male');
	echo('</label>');
	echo('<label class="radio-inline">');
	echo('<input type="radio" name="gender" value="Female" required>Female<br>');
	echo('</label>');
	echo('</div>');
	echo('<input class="btn btn-primary" type="submit" value="Sign Up">');
?>