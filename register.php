<?php
session_start();
if (isset($_SESSION['user'])){
   header('Location: home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Main Page</title>
		<!-- links -->
		<link rel="stylesheet" href="style.css" />
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
			integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		/>
	</head>
	<body>
		<nav>
			<span class="logo">Journal Genie</span>
		</nav>
		<div class="card">

		<?php
			include('php/config.php');
			if(isset($_POST['submit'])){
				$email = $_POST['email'];
				$username = $_POST['username'];
				$password = $_POST['password'];
				$repeatPassword = $_POST['repeatPassword'];
				$errors = array();

				if(empty($email) || empty($username) || empty($password) || empty($repeatPassword)) {
					array_push($errors,'All fields are required!');
				}
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					array_push($errors,'Email is not valid!');
				}
				if(strlen($password) < 8) {
					array_push($errors,'Password must be at least 8 characters long!');
				}
				else if($password != $repeatPassword) {
					array_push($errors,'Passwords are not the same!');
				}

				if(count($errors) > 0) {
					echo "
					<div class='validation-result'>
					";
					echo "<span class='form-logo'>journal genie</span>
					<ul class='error-list'>";
					foreach($errors as $error) {
					echo '<li class="validation-error">'. $error .'</li>';
					}
					echo "</ul><a href='javascript:self.history.back()'>
					<button class='btn validation-error-btn'>Go back</button>
					</a> </div>";
				}else{
					
					//verify the email

					$verify_query = mysqli_query($conn,"SELECT email FROM accounts WHERE email='$email'");

					if(mysqli_num_rows($verify_query) != 0){
						echo "
						<div class='wrongEmail'>
							<span class='form-logo'>journal genie</span>
							<p> This email is used!</p>
										
							<a href='javascript:self.history.back()'>
								<button class='btn'>Go back</button>
							</a>
						</div>";
					}else{
						mysqli_query($conn,"INSERT INTO accounts(email, username, password) VALUES ('$email', '$username', '$password')") or die('Error occured!');
						echo "
						<div class='successfulRegistration'>
							<span class='form-logo'>journal genie</span>
							<p> Registered successfuly! Sign in now!</p>
							<a href='login.php' class='log-in'>
								<button class='btn'>Sign in</button>
							</a>
						</div>";
					}
				}

			
			}else{
			?>

			<span class="form-logo">journal genie</span>
			<form action="" method='post' class="form-main">
				<span class="form-header">Sign up</span>

				<div class="inputContainer email-field">
					<i class="fa-solid fa-at"></i>
					<input
                  name="email"
						type="email"
						class="inputField"
						id="email"
						placeholder="Email"
                  autocomplete="off"
					/>
				</div>

				<div class="inputContainer username-field">
					<i class="fa-solid fa-circle-user"></i>
					<input
                  name="username"
						type="text"
						class="inputField"
						id="username"
						placeholder="Username"
                  autocomplete="off"
					/>
				</div>

				<div class="inputContainer password-field">
					<i class="fa-solid fa-lock"></i>
					<input
                  name="password"
						type="password"
						class="inputField"
						id="password"
						placeholder="Password"
                  autocomplete="off"
					/>
				</div>

				<div class="inputContainer repeatPassword-field">
					<i class="fa-solid fa-lock"></i>
					<input
                  name="repeatPassword"
						type="password"
						class="inputField"
						id="repeatPassword"
						placeholder="Repeat password"
                  autocomplete="off"
					/>
				</div>

				<button
					name="submit"
					type="submit"
					id="signUp"
					class="enabledBtn btn"
				>
					Sign up
				</button>

				<div class="if forLogin">
					<p class="ifHave">I already have an account:</p>
					<a href="login.php" id="toSignIn">Sign in</a>
				</div>

				<!-- <a class="forgotLink" href="#">Forgot your password?</a> -->
			</form>
			<?php } ?>
		</div>

		<script src="scriptForm.js"></script>
	</body>
</html>

