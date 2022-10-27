<?php

require_once 'utils/functions.php';
require_once 'classes/Users.php';
require_once 'classes/Database.php';

start_session();

try {
    $errors = array();

    if (empty($_POST['username'])) {
        $errors['username'] = "Username required";
    }
    if (empty($_POST['password'])) {
        $errors['password'] = "Password required";
    }
    if (empty($errors)) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $auser = new Users();
        $auser->checkUser($username, $password);

        $user = $auser->initWithUsername($username);
        if (empty($user)) {
            $errors['username'] = "Username is not registered";
        } else {
            if ($password === $auser->getPassword()) {
                $_SESSION['username'] = $username;
                $id = $user->getUidByUserName($_SESSION['username']);
                $userId = 0;
                for ($i = 0; $i < count($id); $i++) {
                    $userId = $id[$i]->id;
                }
                $_SESSION['userId'] = $userId;
                $_SESSION['user'] = $user;
                header('Location: index.php');
            } else {
                $_SESSION['username'] = "";
                unset($_SESSION['user']);
                $errors['password'] = "Password is incorrect";
            }
        }
    }
    if (!empty($errors)) {
        throw new Exception("");
    }
    
} catch (Exception $ex) {
    $errorMessage = $ex->getMessage();
    require 'login_form.php';
}
?>
