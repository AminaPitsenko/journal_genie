<?php
   session_start();
   if (!isset($_SESSION['user'])){
      header('Location: login.php');
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Main Page</title>
   <!-- links -->
   <link rel="stylesheet" href="home.css">
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body id='bodyID'>

   <?php
   include'php/config.php';
   $user_id = $_SESSION['user_id'];
   $username = $_SESSION['username'];
   $email = $_SESSION['email'];

   ?>

   <div class="navbar">
      <span class="logoHome">Journal Genie</span>
      <div class="addNew">
         <form method='post'>
            <button class="addBtn btn" id="addNote" name="addNote"><i class="fa-solid fa-plus"></i></button>
         </form>
         <form method='post'>
            <button class="addBtn btn" id="addList" name="addList"><i class="fa-solid fa-plus"></i></button>
         </form>
      </div>
      <a href="php/logout.php">
         <button type="submit" id="logout" class="btn">
            Log out
         </button>
      </a>
   </div>

   <div class="mainPage">
      <div class="sidebar">
         <div class="halfSidebar">
            <div class="usernameWrap">
               <span id="username"><?php echo $_SESSION['username'] ?> </span>
            </div>
            <div class="line"> </div>
            <form method="post" action="">
               <button id='startPage' class="sidebarBtn" name='startPage'>
               <div class="div"><i class="fa-solid fa-house"></i></div>Start Page
               </button>
            </form>
            <form method="post" action="">
               <button id='myNotes' class="sidebarBtn" name='myNotes'>
               <div class="div"><i class="fa-solid fa-note-sticky"></i></div>My notes
               </button>
            </form> 
            
            <form method="post" action="">
               <button id='myTasks' class="sidebarBtn" name='myTasks'>
               <div class="div"><i class="fa-solid fa-list"></i></div> My task lists
               </button>
            </form>

            
         </div>
      </div>
      
      <div class="contentBox">
         <div class='mainWrapper'>

         <?php
            // NOTES PART

            if (isset($_POST['addNote'])){
         ?>

         <form class='noteCreate' action="" method='post'>
            <div class="box">
               <input autocomplete="off" type="text" id="nameInput" name="nameInput" placeholder="Note's name" class='noteName'>
            </div>
            <div class="box">
               <textarea autocomplete="off" spellcheck="false" type="text" id='noteInput' name='noteInput' placeholder='Text...' class='noteContent'></textarea>
            </div>
            <div class="box">
               <button class='confirm' name='confirmNote'><i class="fa-solid fa-check"></i></button>
            </div>
         </form>  

         <?php
            }elseif (isset($_POST['confirmNote'])){
               
               $note_name = $_POST['nameInput'];
               $content = $_POST['noteInput'];
               $note_date = date("y-m-d") ."  ". date("H:i:sa");
               $user_id = $_SESSION['user_id'];

               mysqli_query($conn,"INSERT INTO notes(note_name, content, note_date, user_id) VALUES ('$note_name', '$content', '$note_date', '$user_id')") or die('Error occured!');

               echo"
               <div class='flex noteFlex'>
                  <span id='success'>Note added successfully!</span>
                  <form method='post' class='next'>
                     <button class='btn' name='myNotes'>continue</button>
                  </form>
               </div>";
            ?>
               
            <?php
            }elseif (isset($_POST['myNotes'])){
            $notes = mysqli_query($conn,"SELECT * FROM notes WHERE user_id='$user_id'") or die('Select Error');
            $usersNotes= mysqli_fetch_all($notes, MYSQLI_ASSOC);

            if(!$usersNotes){
               echo "
               <div class='flex noteFlex'>
                  <span class='empty'>You don't have any notes :(</span>
               </div>   
               ";
            }

            echo "<div class='notesBox'>";
            
            foreach ($usersNotes as $userNote){
               $contentPreview = substr($userNote['content'] , 0, 120);
               $dateshort =substr($userNote['note_date'] , 0, 16);
               echo "
               
               <div class='note'>
                  <div class='noteFlex'>
                     <div class='colorMark'> </div>
                     <div class='innerNoteWrap'>
                        <div class='smallWrap'>
                           <span class='noteName'>". $userNote['note_name'] . "</span>
                           <form method='post'>
                              <button id='moreIcon' name='moreNote'><i class='fa-solid fa-angle-right'></i>
                              </button>
                              <input name='noteID' value='". $userNote['note_id'] . "'>
                           <form>
                           <form action='' method='post'>
                              <button id='deleteIcon' name='delete'><i class='fa-solid fa-trash-can'></i></button>
                              <input name='noteID' value='". $userNote['note_id'] . "'>
                           </form>
                        </div>
                        <p class='noteContent'>" . $contentPreview . "...</p>
                        <span class='noteDate'>". $dateshort. "</span>
                     </div>
                  </div>
               </div>
               ";
            }

            echo "</div>";

         }elseif (isset($_POST["delete"])){
            $noteID = $_POST["noteID"];
            $note = mysqli_query($conn,"DELETE FROM notes WHERE note_id='$noteID'") or die('Error occured!');

            if($note){
               echo"
               <div class='flex noteFlex'>
                  <span id='success'>Note deleted successfully</span>
                  <form method='post' class='next'>
                     <button class='btn' name='myNotes'>continue</button>
                     <input class='hidden' name='noteID' value='".  $noteID . "'>
                  </form>
               </div>
               ";
            }

         }elseif (isset($_POST["moreNote"])){  
               $noteID = $_POST["noteID"];
               $note = mysqli_query($conn,"SELECT * FROM notes WHERE note_id='$noteID'") or die("Select Error");
               $resNote = mysqli_fetch_array($note, MYSQLI_ASSOC);

               if($resNote){
                  $dateshort = substr($resNote['note_date'] , 0, 16);

               echo "
               <div class='notesWrapper'>
                  <div class='nameWrap'>
                     <span class='noteName'>". $resNote['note_name'] ."</span>
                     <div class='formWrap'> 
                        <form action='' method='post'>
                           <button name='editNote' class='editNote'><i class='fa-solid fa-pen-to-square'></i></button>
                           <input class='hidden' name='noteID' value='". $resNote['note_id'] . "'>
                        </form>
                        <form action='' method='post'>
                           <button name='myNotes' class='closeNote'><i class='fa-solid fa-xmark'></i></button>
                        </form>
                     </div>
                  </div>
                  <p class='noteContent'>". $resNote['content'] ."</p>
                  <span class='noteDate date'>". $dateshort ."</span>
               </div></div>
               ";
            }
         }elseif (isset($_POST["editNote"])){
            $noteID = $_POST["noteID"];
            $note = mysqli_query($conn,"SELECT * FROM notes WHERE note_id='$noteID'") or die("Select Error");
            $resNote = mysqli_fetch_array($note, MYSQLI_ASSOC);
         ?>

         <form class='noteCreate' action="" method='post'>
            <div class="box">
               <input autocomplete="off" value="<?php echo $resNote['note_name'] ?>" type="text" id="nameInput" name="noteNameEdit" placeholder="Note's name" class='noteName'>
            </div>
            <div class="box">
               <textarea autocomplete="off" spellcheck="false" type="text" id='noteInput' name='noteEdit' placeholder='Text...' class='noteContent'><?php echo $resNote['content'] ?></textarea>
            </div>
            <div class="box">
               <button class='confirm' name='confirmEditNote'><i class="fa-solid fa-check"></i></button>
               <input class='hidden' name='noteID' value='<?php echo $noteID; ?>'>
            </div>
         </form>

         <?php
         }elseif (isset($_POST['confirmEditNote'])){
            $noteNameEdit = $_POST['noteNameEdit'];
            $noteEdit = $_POST['noteEdit'];
            $noteID = $_POST['noteID'];
            $noteDate = date("y-m-d") ."  ". date("H:i:sa");

            $edit = mysqli_query($conn,"UPDATE notes SET note_name='$noteNameEdit', content='$noteEdit', note_date='$noteDate' WHERE note_id='$noteID'") or die(mysqli_error($conn));

            if($edit){
               echo "
               <div class='flex noteFlex'>
                  <span id='success'>Changes saved!</span>
                  <form method='post' class='next'>
                     <button class='btn' name='moreNote'>continue</button>
                     <input class='hidden' name='noteID' value='".  $noteID . "'>
                  </form>
               </div>";
            }
         // TASK LIST PART

         }elseif (isset($_POST["addList"])){
            
         ?>

         <div class="addListWrap">
            <form class='listCreate' action="" method='post'>
               <div class="box">
                  <input autocomplete="off" type="text" id="nameInput" name="nameInput" placeholder="Task list's name" class='noteName'>
               </div>
               <div class="box">
                  <button class='confirm' name='confirmList'><i class="fa-solid fa-check"></i></button>
               </div>
            </form>
         </div>

         <?php
         }elseif (isset($_POST["confirmList"])){

               $tasklist_name = $_POST['nameInput'];
               $tasklist_date = date("y-m-d") ."  ". date("H:i:sa");
               $user_id = $_SESSION['user_id'];

               mysqli_query($conn,"INSERT INTO tasklists(tasklist_name, tasklist_date, user_id) VALUES ('$tasklist_name', '$tasklist_date', '$user_id')") or die('Error occured!');

               echo"
               <div class='flex noteFlex'>
                  <span id='success'>Task list added successfully!</span>
                  <form method='post' class='next'>
                     <button class='btn' name='myTasks'>continue</button>
                  </form>
               </div>";

         }elseif (isset($_POST["myTasks"])){
            $tasklists = mysqli_query($conn,"SELECT * FROM tasklists WHERE user_id='$user_id'") or die('Select Error');
            $userTasklists= mysqli_fetch_all($tasklists, MYSQLI_ASSOC);

            if(!$userTasklists){
               echo "
               <div class='flex noteFlex'>
                  <span class='empty'>You don't have any tasklists :(</span>
               </div>   
               ";
            }

            echo "<div class='noteBox'>";
            
            foreach ($userTasklists as $userTasklist){
               $dateshort =substr($userTasklist['tasklist_date'] , 0, 16);
               echo "
               
               <div class='note'>
                  <div class='noteFlex'>
                     <div class='colorMarkTask'> </div>
                     <div class='innerNoteWrap'>
                        <div class='smallWrap2'>
                           <form method='post'>
                              <button id='moreIconList' name='moreList'><i class='fa-solid fa-angle-right'></i>
                              </button>
                              <input name='listID' value='". $userTasklist['tasklist_id'] . "'>
                           <form>
                           <form action='' method='post'>
                              <button id='deleteIcon' name='deleteList'><i class='fa-solid fa-trash-can'></i></button>
                              <input name='listID' value='". $userTasklist['tasklist_id'] . "'>
                           </form>
                        </div>
                        <span class='listName'>". $userTasklist['tasklist_name'] . "</span>
                        <span class='noteDate'>". $dateshort. "</span>
                     </div>
                  </div>
               </div>
               ";
            }

            echo "</div>";

         }elseif (isset($_POST["deleteList"])){
            $listID = $_POST["listID"];
            $list = mysqli_query($conn,"DELETE FROM tasklists WHERE tasklist_id='$listID'") or die('Error occured!');
            $tasks = mysqli_query($conn,"DELETE FROM tasks WHERE tasklist_id='$listID'") or die('Error occured!');

            if($list){
               echo"
               <div class='flex noteFlex'>
                  <span id='success'>Note deleted successfully</span>
                  <form method='post' class='next'>
                     <button class='btn' name='myTasks'>continue</button>
                     <input class='hidden' name='listID' value='".  $listID . "'>
                  </form>
               </div>
               ";
            }
         
         }elseif (isset($_POST["moreList"])){
            $listID = $_POST["listID"];
            $list = mysqli_query($conn,"SELECT * FROM tasks WHERE tasklist_id='$listID'") or die("Select Error");
            $resTasks = mysqli_fetch_all($list, MYSQLI_ASSOC);

            $tasklistName = mysqli_query($conn,"SELECT tasklist_name FROM tasklists WHERE tasklist_id='$listID'") or die("Select Error");
            $resTaskListName = mysqli_fetch_array($tasklistName, MYSQLI_ASSOC);
            $data = mysqli_query($conn,"SELECT tasklist_date FROM tasklists WHERE tasklist_id='$listID'") or die("Select Error");
            $resDate = mysqli_fetch_array($data, MYSQLI_ASSOC);
            
            $dateshort = substr($resDate['tasklist_date'] , 0, 16);

            echo "
            <div class='notesWrapper'>
               <div class='nameWrap'>
                  <span class='noteName'>". $resTaskListName['tasklist_name'] ."</span>
                  <div class='formWrap'>
                     <form action='' method='post'>
                        <button name='addTask' class='editNote'><i class='fa-solid fa-plus'></i></button>
                        <input class='hidden' name='listID' value='". $listID. "'>
                     </form>
                     <form action='' method='post'>
                        <button name='editNote' class='editNote'><i class='fa-solid fa-pen-to-square'></i></button>
                        <input class='hidden' name='listID' value='". $listID. "'>
                     </form>
                     <form action='' method='post'>
                        <button name='myLists' class='closeNote'><i class='fa-solid fa-xmark'></i></button>
                     </form>
                  </div>
               </div>";

               echo "<form>";
               if($resTasks){
                  foreach($resTasks as $resTask){
                  echo "<div class='oneTask'>
                  <input type='checkbox' id='taskCheckbox' name='task' ><label for='task'>". $resTask['content'] ."</label>
                  </div>";
               }}
               echo "</form>";
            echo "<span class='noteDate date'>". $dateshort ."</span>
            </div></div>  
            ";

         }else{
      
         ?>
         <div class="mainWrapper startOption">
            <div class="startWrapper">
               <div class="wrapper">
                  <span>Click <button disabled class="addBtn btn startPageBtn" id="addNoteDis"><i class="fa-solid fa-plus"></i></button> to create new note</span>
               </div>
               <div class="wrapper">
                  <span>Click <button disabled class="addBtn btn startPageBtn" id="addTaskDis"><i class="fa-solid fa-plus"></i></button> to create new task list</span>
               </div>
            </div>
         </div>
         <?php }?>
         </div>     
      </div>

   </div>
   <!-- <script src="script.js"></script> -->
   <!-- <a href="edit.php" class="btn edit-btn">edit</a> -->
</body>
</html>

