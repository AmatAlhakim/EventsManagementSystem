<?php
//include './debugging.php';
require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Database.php';
require_once 'classes/LoginUnit.php';
start_session();

if (isset($_POST['submitted'])) {
    try {
        $errors = array();
        if (empty($_POST['username'])) {
            $errors['username'] = "Username required";
        }
        if (empty($_POST['password'])) {
            $errors['password'] = "Password required";
        }
        if (empty($errors)) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $user = new Users();

            if ($user->login($username, $password)) {
                $_SESSION['username'] = $username;
                $id = $user->getUidByUserName($_SESSION['username']);
                $userId = 0;
                for ($i = 0; $i < count($id); $i++) {
                    $userId = $id[$i]->id;
                }
                $_SESSION['userId'] = $userId;
                $_SESSION['user'] = $user;
                echo 'You Logged in successfully';
                header('Location: index.php');
            } else {
                $_SESSION['username'] = "";
                unset($_SESSION['user']);
//               / echo json_encode($user->login($username, $password));
                echo 'Wrong Login Values';
            }
        }
        if (!empty($errors)) {
            throw new Exception("");
        }
        
    } catch (Exception $ex) {
        $errorMessage = $ex->getMessage();
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style>
            span.error{
                color: red;
            }
        </style>  
        <?php require 'utils/styles.php'; ?><!--css links. file found in utils folder-->
        <?php require 'utils/scripts.php'; ?><!--js links. file found in utils folder-->
        <?php require './debugging.php'; ?>
    </head>
    <body>
        <?php require 'utils/header.php'; ?><!--header content. file found in utils folder-->
        <div class = "content"><!--body content holder-->
            <div class = "container">
                <div class ="col-md-6 col-md-offset-3">
                    <?php
                    if (isset($errorMessage))
                        echo "<p>$errorMessage</p>";
                    ?>
                    <form action="login_form.php" method="post"><!--form-->
                        <div class = "form-group">
                            <!--username field-->
                            <label for="username">Username: </label>
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"
                                   />
                            <span class = "error"><!--error message for invalid input-->
                                <?php if (isset($errors['username'])) echo $errors['username']; ?>
                            </span>
                        </div>
                        <div class = "form-group">
                            <!--password field-->
                            <label for="password">Password: </label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   value=""
                                   />
                            <span class = "error"><!--error message for invalid input-->
                                <?php if (isset($errors['password'])) echo $errors['password']; ?>
                            </span>
                        </div>
                        <button type = "submit" class = "btn btn-default">Login</button>
                        <input type="hidden" name="submitted" value="1" />
                    </form>
                </div><!--col md 6 div-->
            </div><!--container div-->
        </div><!--content div-->
        <?php require 'utils/footer.php'; ?><!--footer content. file found in utils folder-->
    </body>
</html>
