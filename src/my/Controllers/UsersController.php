<?php
namespace my\Controllers;
use my\Exceptions\ActivateException;
use my\Exceptions\InvalidArgumentException;
use my\View\View;
use my\Models\Users\User;
use my\Models\Users\UserActivationService;
use my\Services\EmailSender;
class UsersController{
    private $view;
    public function __construct(){
        $this->view = new View(__DIR__ . '/../../../templates');
    }
    public function login(){
        $this->view->renderHtml('users/login.php',[]);
    }
    public function signUp(){
            if(!empty($_POST)){
                try {
                    $user = User::signUp($_POST);
                }
                catch (InvalidArgumentException $e){
                    $this->view->renderHtml('users/signUp.php',['error' => $e->getMessage()]);
                    return;
                }
                if ($user instanceof User){

                    $code = UserActivationService::createActivationCode($user);

                    EmailSender::send($user, 'Активация', 'userActivation.php', [
                        'userId' => $user->getId(),
                        'code' => $code
                    ]);
                    $this->view->renderHtml('users/signUpSuccessful.php');
                    return;
                }
            }
            $this->view->renderHtml('users/signUp.php');
    }
    public function activate(int $userId, string $activationCode){
        try {
            $user = User::getById($userId);
            if (!$user){
                throw new ActivateException('User is no found');
            }
            $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
            if (!$isCodeValid) {
                throw new ActivateException('Code is invalid');
            }
            $user->activate();
            $this->view->renderHtml('mail/userActivationOk.php', ['userId' => $user->getId()]);
            return;
        } catch (ActivateException $e){
            $this->view->renderHtml('mail/UserActivationError.php', ['error' => $e -> getMessage()], 422);
        }
    }
}