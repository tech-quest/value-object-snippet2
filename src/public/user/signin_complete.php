<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Infrastructure/Redirect/redirect.php';
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\InputPassword;
use App\UseCase\UseCaseInput\SignInInput;
use App\UseCase\UseCaseInteractor\SignInInteractor;
use App\Infrastructure\Dao\UserDao;


session_start();
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

try {
    if (empty($email) || empty($password)) {
        throw new Exception('パスワードとメールアドレスを入力してください');
    }

    $userEmail = new Email($email);
    $inputPassword = new InputPassword($password);
    $userDao = new UserDao(); 
    $user = $userDao->findByEmail($userEmail->value());
    

    if (is_null($user) || !password_verify($inputPassword->value(), $user['password'])) {
        $_SESSION['errors'][] = 'メールアドレスまたは<br />パスワードが違います';
        redirect('./signin.php');
    }

    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['name'] = $user['name'];

    redirect('../index.php');
} catch (Exception $e) {
    $_SESSION['errors'][] = $e->getMessage();
    redirect('../index.php');
}
