<?php

namespace my\Models\Users;
use my\Models\ActiveRecordEntity;
use my\Exceptions\InvalidArgumentException;
class User extends ActiveRecordEntity {
    protected $nickname;
    protected $passwordHash;
    protected $isConfirmed;
    protected $email;
    protected $createdAt;
    protected $authToken;
    protected $role;

    public function getEmail():string {
        return $this->email;
    }
    public function getNickname():string {
        return $this->nickname;
    }

    protected static function getTableName():string{
        // TODO: Implement getTableName() method.
        return 'users';
    }
    public static function signUp(array $userData){

        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }

        if (!preg_match('/[a-zA-Z0-9]+/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');
        }

        if(empty($userData['nickname'])){
            throw new InvalidArgumentException('nickname is empty');
        }
        if(empty($userData['email'])){
            throw new InvalidArgumentException('email is empty');
        }
        if(empty($userData['password'])){
            throw new InvalidArgumentException('password is empty');
        }
        if(static::findByOneColumn('nickname', $userData['nickname'])!== null){
            throw new InvalidArgumentException('nickname is not uniq');
        }
        if(static::findByOneColumn('email', $userData['email']) !== null){
            throw new InvalidArgumentException('nickname is not uniq');
        }
        $user = new User();
        $user -> nickname = $userData['nickname'];
        $user -> email = $userData['email'];
        $user -> passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user -> role = 'user';
        $user -> authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user -> isConfirmed = false;
        $user -> save();
        return $user;
    }
    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
        UserActivationService::deleteActivationCode($this->getId());

    }
}