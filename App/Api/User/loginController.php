<?php

namespace App\Api\User;

header("Authorization: Bearer=$_COOKIE[token]");

use App\Models\User\Login;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Token;
use Core\Validation;
use Exception;

class LoginController extends Controller
{
    public function login(Validation $validation)
    {
        try {
            $this->formValidation($validation)
                ->fetchUserFromTable($this->params['username'], $user)
                ->checkUIfUserExistsAndPasswordIsCorrect($user, $this->params['password'])
                ->storeUserIdAndUserRoleIntoJwt($user->userId, $user->roleId)
                ->loadUserDashboard();
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'loginErrorMessage');
        }
    }

    private function formValidation($validation)
    {
        $validation->fields(['username', 'password'], ['نام کاربری', 'رمز عبور'])->required();
        return $this;
    }

    private function fetchUserFromTable($username, &$user)
    {
        $user = Login::getInstance()->fetchUser($username);
        return $this;
    }

    private function checkUIfUserExistsAndPasswordIsCorrect($userObject, $userPassword)
    {
        if (!isset($userObject) || !Helper::passwordVerify($userPassword, $userObject->password))
            throw new Exception('نام کاربری یا رمز عبور اشتباه است', 302);
        return $this;
    }

    private function storeUserIdAndUserRoleIntoJwt($userId, $roleId)
    {
        Token::generate(['userId' => $userId, 'roleId' => $roleId]);
        return $this;
    }

    private function loadUserDashboard()
    {
        UserDashboardController::getInstance()->dashboard();
    }
}
