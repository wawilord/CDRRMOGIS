<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Login Form
 * DESCRIPTION: 	Code for the login of accounts.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * admintype 	-> 	If an admin account was logged in.
 * cdrrmotype 	-> 	If a cdrrmo account was logged in.
 * cswdtype 	-> 	If a cswd account was logged in.
 * brgytype 	-> 	If a barangay account was logged in.
 * error		-> 	if the username or password is incorrect.
 * ______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['USERNAME']	->	text
 * $_POST['PASSWORD']	->	text
 *______________________________________________________________________________________
 *
 * Note: you can send a custom error message by typing 'msg:' followed by your message.
 * 		 Just make sure that there are no printed or echoed before the 'msg:' keyword.
 *______________________________________________________________________________________
 *
 */
	session_start();

	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	//form variables
	$form_USERNAME = $_POST['USERNAME'];
	$form_PASSWORD = $_POST['PASSWORD'];
	

	//sql
	$sql = "SELECT USERNAME, PASSWORD, TYPE, FIRSTNAME, MIDDLENAME, LASTNAME, ENABLED, BRGY FROM user_accounts WHERE USERNAME='" . $db->connection->real_escape_string($form_USERNAME) . "'";
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	$row = $result->fetch_assoc();

	//db variables
	$result_USERNAME = $row['USERNAME'];
	$result_PASSWORD = $row['PASSWORD'];
	$result_TYPE = $row['TYPE'];
	$result_FIRSTNAME = $row['FIRSTNAME'];
	$result_MIDDLENAME = $row['MIDDLENAME'];
	$result_LASTNAME = $row['LASTNAME'];
	$result_ENABLED = $row['ENABLED'];
	$result_BRGY = $row['BRGY'];

	//verification
	if($count > 0)
	{
		if($result_ENABLED == '1')
		{
			if($result_PASSWORD == $form_PASSWORD)
			{
				//determine account type
				switch ($result_TYPE)
				{
					case 'A':
						echo "admintype";
						break;
					case 'B':
						echo "cdrrmotype";
						break;
					case 'C':
						echo "cswdtype";
						break;
					case 'D':
						echo "brgytype";
						break;
					Default:
						echo 'error';
						exit;
						break;
				}

				//create session variables
				$_SESSION['USER_USERNAME'] = $result_USERNAME;
				$_SESSION['USER_TYPE'] = $result_TYPE;
				$_SESSION['USER_FIRSTNAME'] = $result_FIRSTNAME;
				$_SESSION['USER_MIDDLENAME'] = $result_MIDDLENAME;
				$_SESSION['USER_LASTNAME'] = $result_LASTNAME;
				$_SESSION['USER_BRGY'] = $result_BRGY;

				//create log
				console_log('USER(' . $result_USERNAME . ') logged in to the system.', '../../system/log.txt');
			}
			else
			{
				echo 'error';
				//create log
				console_log('USER(' . $result_USERNAME . ') logged in with a wrong password.', '../../system/log.txt');
			}
		}
		else
		{
			echo "deactivated";
		}
	}
	else
	{
		echo 'error';
	}
?>