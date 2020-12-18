<?php

class loginController extends Controller
{

    public function index($params)
    {
        $this->loadTemplate('login');
    }

    public function registerPage()
    {
        $this->loadTemplate('register');
    }

    public function forgotPage()
    {
        $this->loadTemplate('forgotPassword');
    }

    public function recoverPage($params)
    {
        $data = $this->getUser($params['0']);

        $this->loadTemplate('recoverPassword', $data);
    }

    public function getUser($token)
    {
        $n = new Login();
        $data =  $n->getUserToken($token);
        return $data;
    }

    public function login()

    {
        session_start();
        //Constant with the number of access attempts allowed
        define('ATTEMPTS_ACCEPTED', 3);

        // Constant with the number of minutes for blocking
        define('BLOCKED_MINUTES', 30);

        //Checks whether the origin of the request is from the same domain as the application
        $HTTP_REFERER = rtrim($_SERVER['HTTP_REFERER'], '/');
        $HTTP_REQUEST = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . 'login';

        if (isset($_SERVER['HTTP_REFERER']) && $HTTP_REFERER !=  $HTTP_REQUEST) :
            $return = array('code' => '', 'message' => 'Unauthorized request origin! <br> ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . BASE_URL . 'login <br>');
            echo json_encode($return);
            exit();
        endif;

        //Receive form data
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $password = (isset($_POST['password'])) ? $_POST['password'] : '';

        //Validates password and email filling
        if (empty($email)) :
            $return = array('code' => '', 'message' => 'Fill in the email field!');
            echo json_encode($return);
            exit();
        endif;

        if (empty($password)) :
            $return = array('code' => '', 'message' => 'Fill in your password!');
            echo json_encode($return);
            exit();
        endif;
        if (strlen($password) < 5) :
            $return = array('code' => '', 'message' => 'Password must be at least 5 characters!');
            echo json_encode($return);
            exit();
        endif;

        //Checks whether the email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
            $return = array('code' => '', 'message' => 'Invalid email format!');
            echo json_encode($return);
            exit();
        endif;

        //Checks if the user has already exceeded the number of wrong attempts of the day
        $n = new Login();
        $data['attempts'] = $n->connectionCount();
        $attempts = $data['attempts'];
        if (!empty($attempts->attempts) && intval($attempts->minutes) <= BLOCKED_MINUTES) :
            $_SESSION['attempts'] = '';
            $return = array('code' => '', 'message' => 'You have exceeded the ' . ATTEMPTS_ACCEPTED . ' attempts, login blocked by ' . BLOCKED_MINUTES . ' minutes!');
            echo json_encode($return);
            exit();
        endif;

        //Validates the user data with the database
        $userData = $this->getUserEmail($email);

        if (empty($userData)) {
            $_SESSION['attempts'] = (isset($_SESSION['attempts'])) ? $_SESSION['attempts'] += 1 : 1;
            $blocked = ($_SESSION['attempts'] == ATTEMPTS_ACCEPTED) ? 'YES' : 'NO';
            $n->attempt($email, $password, $blocked);
            $return = array('code' => '', 'message' => 'Email not foung in database');
            echo json_encode($return);
            exit();
        } else {

            //Validates the password using the Password Hash API
            if (password_verify($password, $userData['password'])) :
                $_SESSION['id'] = $userData['id'];
                $_SESSION['name'] = $userData['name'];
                $_SESSION['email'] = $userData['email'];
                $_SESSION['attempts'] = 0;
                $_SESSION['logged'] = 'YES';
            else :
                $_SESSION['logged'] = 'NO';
                $_SESSION['attempts'] = (isset($_SESSION['attempts'])) ? $_SESSION['attempts'] += 1 : 1;
                $blocked = ($_SESSION['attempts'] == ATTEMPTS_ACCEPTED) ? 'YES' : 'NO';
                $n->attempt($email, $password, $blocked);
                $return = array('code' => '', 'message' => 'Incorrect password!');
                echo json_encode($return);
                exit();
            endif;
        }
        //If logged sends code 1, if no error message is returned to login page
        if ($_SESSION['logged'] == 'YES') :
            $fullName = $userData['name'];
            $firstName = explode(' ', trim($fullName));
            $firstName = $firstName[0];
            $return = array('code' => '1', 'message' => 'Successfully logged!', 'name' => $firstName);
            echo json_encode($return);
            exit();
        else :

            if ($_SESSION['attempts'] == ATTEMPTS_ACCEPTED) :
                $return = array('code' => '0', 'message' => 'You have exceeded the ' . ATTEMPTS_ACCEPTED . ' attempts, login blocked by ' . MINUTOS_BLOQUEIO . ' minutes!');
                echo json_encode($return);
                exit();
            else :
                $return = array('code' => '0', 'message' => 'Unauthorized user, you have more ' . $_SESSION['attempts'] . (ATTEMPTS_ACCEPTED - $_SESSION['attempts']) . ' attempts before blocking!');
                echo json_encode($return);
                exit();
            endif;
        endif;
    }

    public function logout()
    {
        session_start();
        session_destroy();

        header("Location: " . BASE_URL . "login");
        exit;
    }

    public function register()
    {
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $password = (isset($_POST['password'])) ? $_POST['password'] : '';
        $password = password_hash($password, PASSWORD_DEFAULT);

        $n = new Login();
        $register = $n->register($name, $email, $password);
        if ($register == true) {
            $return = array('status' => '1', 'name' => $name, 'email' => $email);
            echo json_encode($return);
            exit();
        } else {
            $return = array('status' => '', 'name' => $name, 'email' => $email);
            echo json_encode($return);
            exit();
        }
    }

    public function userVerify($email)
    {
        $n = new Login();
        $userVerify = $n->userVerify($email);
        if ($userVerify == 1) {
            return true;
            exit();
        } else {
            return false;
            exit();
        }
    }
    public function getUserEmail($email)
    {
        $n = new Login();
        $userData = $n->getUserEmail($email);
        return $userData;
    }
    public function forgotPassword()
    {
        $email = (isset($_POST['email'])) ? $_POST['email'] : '';
        $userData = $this->getUserEmail($email);

        if (!empty($userData)) {

            try {
                require '../Core/ConfigEmail.php';
                //Defines the recipient
                $mail->addAddress($userData['email']);
                //Defines the e-mail subject
                $mail->Subject = 'Password Renewal';
                //Defines the e-mail message
                $mail->Body    = '<p>' . $_SERVER['HTTP_HOST'] . BASE_URL . 'login/recoverPage/' . $userData['token'] . '</p>';
                //Send
                if ($mail->send()) {
                    $return = array('code' => '1', 'message' => 'An email has been sent. Use it to update your password.');
                    echo json_encode($return);
                    exit();
                } else {
                    $return = array('code' => '', 'message' => 'There was a problem sending the email.');
                    echo json_encode($return);
                    exit();
                }
            } catch (Exception $e) {
                $return = "code' => '0', 'message' => 'An error: {$mail->ErrorInfo}";
                echo json_encode($return);
            }
        } else {
            $return = array('code' => '', 'message' => 'An error was encountered. Email not found.');
            echo json_encode($return);
            exit();
        }
    }

    public function renewPassword()
    {
        $token = (isset($_POST['token'])) ? $_POST['token'] : '';
        $password = (isset($_POST['password'])) ? $_POST['password'] : '';
        $password = password_hash($password, PASSWORD_DEFAULT);
        $n = new Login();

        $renew = $n->renewPassword($password, $token);
        if ($renew == 1) {
            $return = array('status' => '1', 'message' => 'Renewed password.');
            echo json_encode($return);
            exit();
        } else {
            $return = array('status' => '0', 'message' => 'An error happened');
            echo json_encode($return);
            exit();
        }
    }
}
