<?php
  require_once('../private/initialize.php');

  // Set default values for all variables the page needs.
  $firstname = 0;
  $lastname = 0;
  $email = 0;
  $username = 0;
  $errorflag = 0;

  // if this is a POST request, process the form
  // Hint: private/functions.php can help

    // Confirm that POST values are present before accessing them.

    // Perform Validations
    // Hint: Write these in private/validation_functions.php

    // if there were no errors, submit data to database

      // Write SQL INSERT statement
      // $sql = "";

      // For INSERT statments, $result is just true/false
      // $result = db_query($db, $sql);
      // if($result) {
      //   db_close($db);

      //   TODO redirect user to success page

      // } else {
      //   // The SQL INSERT statement failed.
      //   // Just show the error, not the form
      //   echo db_error($db);
      //   db_close($db);
      //   exit;
      // }
	  
	if(is_post_request()) {
		
		$errors = [];
		if(is_blank($_POST['firstname'])) {
			$errors[] = "First name cannot be blank.";
			$errorflag = 1;
		}
		elseif(!has_length($_POST['firstname'], ['min' => 2, 'max' => 255])) {
			$errors[] = "First name must be between 2 and 255 characters.";
			$errorflag = 1;
		}
		elseif(!preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $_POST['firstname'])) {
			$errors[] = "First name can only contain letters, spaces, and the symbols: -,.'";
			$errorflag = 1;
		}	
		if(is_blank($_POST['lastname'])) {
			$errors[] = "Last name cannot be blank.";
			$errorflag = 1;
		}
		elseif(!has_length($_POST['lastname'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Last name must be between 2 and 255 characters.";
			$errorflag = 1;
		}
		elseif(!preg_match('/\A[A-Za-z\s\-,\.\']+\Z/', $_POST['lastname'])) {
			$errors[] = "Last name can only contain letters, spaces, and the symbols: -,.'";
			$errorflag = 1;
		}	
		if(is_blank($_POST['username'])) {
			$errors[] = "Username cannot be blank.";
			$errorflag = 1;
		}
		elseif(!has_length($_POST['username'], ['min' => 8, 'max' => 255])) {
			$errors[] = "Username must be between 8 and 255 characters.";
			$errorflag = 1;
		}
		elseif(!preg_match('/\A[A-Za-z0-9\_]+\Z/', $_POST['username'])) {
			$errors[] = "Username can only contain letters, numbers, and the symbols: _";
			$errorflag = 1;
		}	
		if(is_blank($_POST['email'])) {
			$errors[] = "Email cannot be blank.";
			$errorflag = 1;
		}
		elseif(!has_length($_POST['email'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Email must be between 2 and 255 characters.";
			$errorflag = 1;
		}
		elseif(!has_valid_email_format($_POST['email'])) {
			$errors[] = "Email must be a valid email address.";
			$errorflag = 1;
		}
		elseif(!preg_match('/\A[A-Za-z0-9\_\@\.]+\Z/', $_POST['email'])) {
			$errors[] = "Email can only contain letters, numbers, and the symbols: _@.";
			$errorflag = 1;
		}
		
		$errormessage = display_errors($errors);
		
		if(!$errorflag) {
			
			$firstname = h($_POST['firstname']);
			$lastname = h($_POST['lastname']);
			$email = h($_POST['email']);
			$username = h($_POST['username']);
			
			$created_at = date("Y-m-d H:i:s");
			
			$sql = "INSERT INTO users (first_name, last_name, email, username, created_at)
			        VALUES ('$firstname', '$lastname', '$email', '$username', '$created_at');";
					
			$result = db_query($db, $sql);
			if($result) {
				db_close($db);
				
				redirect_to("registration_success.php");
				exit;
			}
			else {
				echo db_error($db);
				db_close($db);
				exit;
			}
		}
	}

?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>

  <?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
	
	if(is_post_request()) {
		if($errorflag) {
			echo $errormessage;
		}
	}
	
  ?>

  <!-- TODO: HTML form goes here -->
	<form action="register.php" method="post">
		First Name: <br>
		<input type="text" id="firstname" name="firstname" value="<?php 
		    if(isset($_POST['firstname'])) {
		        echo $_POST['firstname'];
		        $firstname = $_POST['firstname'];
            }
			else {
		        echo "";
				$firstname = '';
			}
	    ?>">
		<br> 
		Last Name: <br>
		<input type="text" id="lastname" name="lastname" value="<?php 
		    if(isset($_POST['lastname'])) {
		        echo $_POST['lastname'];
		        $lastname = $_POST['lastname'];
            }
			else {
		        echo "";
				$lastname = '';
			}
	    ?>">
		<br>
		Email: <br>
		<input type="text" id="email" name="email" value="<?php 
		    if(isset($_POST['email'])) {
		        echo $_POST['email'];
		        $email = $_POST['email'];
            }
			else {
		        echo "";
				$email = '';
			}
	    ?>">
		<br>
		Username: <br>
		<input type="text" id="username" name="username" value="<?php 
		    if(isset($_POST['username'])) {
		        echo $_POST['username'];
		        $username = $_POST['username'];
            }
			else {
		        echo "";
				$username = '';
			}
	    ?>">
		<br> <br>
		<input type="submit" name="submit" value="Submit">
	</form>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
