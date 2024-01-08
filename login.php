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
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

				if(empty($email)) {
					echo "<div class='validation-result'>
					<span class='form-logo'>journal genie</span>
					<span class='empty-login'>Please insert the values!</span><a href='javascript:self.history.back()'>
					<button class='btn'>Try again</button>
					</a></div>";
				}else{
					$result = mysqli_query($conn,"SELECT * FROM accounts WHERE email='$email'") or die('Select Error');
					$user= mysqli_fetch_array($result, MYSQLI_ASSOC);
					
					// if(is_array($userInfo) && !empty($userInfo)){
					//    $_SESSION['valid'] = $userInfo['email'];
					//    $_SESSION['username'] = $userInfo['username'];
					//    $_SESSION['user_id'] = $userInfo['user_id'];

					// 	header("Location: home.php");
					if ($user){
						if ($password == $user['password']){
							$_SESSION['user'] = 'yes';
							$_SESSION['email'] = $user['email'];
							$_SESSION['username'] = $user['username'];
							$_SESSION['user_id'] = $user['user_id'];
							header('Location: home.php');
						}
						else{
							echo "
							<div class='wrongEmail'>
							<span class='form-logo'>journal genie</span>
							<p> Wrong password!</p>
										
							<a href='javascript:self.history.back()'>
								<button class='btn'>Try again</button>
							</a>
							</div>";
						}
					}
					else{
						echo "
						<div class='wrongEmail'>
						<span class='form-logo'>journal genie</span>
						<p> Wrong email! Account does not exist!</p>
									
						<a href='javascript:self.history.back()'>
							<button class='btn'>Try again</button>
						</a>
						</div>";   
					}
				}
         }else{
		?>

			<span class="form-logo">journal genie</span>
			<form action="" method='post' class="form-main">
				<span class="form-header">Sign in</span>

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

				<button name="submit" type="submit" id="signIn" class="btn">
					Sign in
				</button>

				<div class="if forRegister">
					<p class="ifNotHave">I don't have an account:</p>
					<a href="register.php" id="toSignUp">Sign up</a>
				</div>

				<!-- <a class="forgotLink" href="#">Forgot your password?</a> -->
			</form>
			<?php } ?>
		</div>

		<script src="scriptForm.js"></script>
	</body>
</html>