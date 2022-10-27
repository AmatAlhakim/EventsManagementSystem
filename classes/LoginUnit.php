<?php

Class LoginUnit extends Users {

    public $ok;
    public $salt;
    public $domain;

    function __construct() {
        parent::__construct();
        $this->ok = false;
        $this->salt = 'ENCRYPT';
        $this->domain = '';
        if (!$this->check_session())
            $this->check_cookie();
        return $this->ok;
    }

    function check_session() {
        if (!empty($_SESSION['uid'])) {
            $this->ok = true;
            return true;
        }
        else
            return false;
    }

    function check_cookie() {
        if (!empty($_COOKIE['uid'])) {
            $this->ok = true;
            return $this->check($_COOKIE['uid']);
        }
        else
            return false;
    }

    function check($uid) {
        $this->initWithUid($uid);
        if ($this->getUid() != null && $this->getUid() == $uid) {
            $this->ok = true;
            $_SESSION['uid'] = $this->getUid();
            $_SESSION['username'] = $this->getUsername();
            setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            return true;
        }
        else
            $error[] = 'Wrong Username';
        return false;
    }

    function login($username, $password) {
        try {
            //$found = false;
            $this->checkUser($username, $password);
            if ($this->getUid() != null) {
                $this->ok = true;
                $_SESSION['uid'] = $this->getUid();
                $_SESSION['username'] = $this->getUsername();
                $_SESSION['currentUserName'] = $username;
                setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                return true;
            } else {
                $error[] = 'Wrong Username OR password';
                return false;
            }
           // return false;
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return;;;;;;;;; false;
        }
        //return $found;
    }

    function logout() {
        $this->ok = false;
        $_SESSION['uid'] = '';
        $_SESSION['username'] = '';
        setcookie('uid', '', time() - 3600, '/', $this->domain);
        setcookie('username', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }
}
