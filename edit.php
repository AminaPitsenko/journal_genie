<?php
   session_start();
   if (!isset($_SESSION['user'])){
      header('Location: login.php');
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
            if (!isset($_POST['submit'])){
               $email = $_POST['email'];
               $username = $_POST['username'];

               $user_id = $_SESSION['user_id'];

               $edit = mysqli_query($conn,"UPDATE accounts SET email='$email', username='$username' WHERE user_id='$user_id'") or die(mysqli_error($conn));

               if($edit){
                  echo "
                  <div class='update'>
                  <span class='form-logo'>journal genie</span>
                  <p>Profile updated!</p>
                           
                  <a href='home.php'>
                     <button class='btn'>Go home</button>
                  </a>
                  </div>";
               }
            }else{
               $user_id = $_SESSION['user_id'];
               $query = mysqli_query($conn,"SELECT * FROM accounts WHERE user_id='$user_id'");

               while($result = mysqli_fetch_assoc($query)){
                  $res_uname = $result['username'];
                  $res_email = $result['email'];
               }
         ?>

			<span class="form-logo">journal genie</span>
			<form action="" method='post' class="form-main">
				<span class="form-header">Edit profile</span>

				<div class="inputContainer email-field">
					<i class="fa-solid fa-at"></i>
					<input
                  value="<?php echo $res_email ?>"
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
                  value="<?php echo $res_uname ?>"
                  name="username"
						type="text"
						class="inputField"
						id="username"
						placeholder="Username"
                  autocomplete="off"
					/>
				</div>
            
            <div class="buttons">
               <button name="submit" type="submit" id="confirm" class="btn">
					   Confirm
               </button>
               <button name="cancel" type="submit" id="cancel" class="btn">
                  Cancel
               </button>
            </div>
				


				<!-- <a class="forgotLink" href="#">Forgot your password?</a> -->
			</form>
         <?php } ?>
		</div>

		<script src="scriptForm.js"></script>
	</body>
</html>