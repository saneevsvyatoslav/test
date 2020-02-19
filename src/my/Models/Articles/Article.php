<?php
namespace my\Models\Articles;

use my\Models\ActiveRecordEntity;
use my\Models\Users\User;

class Article extends ActiveRecordEntity  {
    /** @var string */
    protected $name;
    /** @var string */
    protected $text;
    /** @var string */
    protected $authorId;
    /** @var string */
    protected $createdAt;

    protected static function getTableName(): string{
        return 'articles';
    }

    public function getName():string{
        return $this->name;
    }
    public function getText():string{
        return $this->text;
    }
    public function getAuthor():User{
        return User::getById($this->authorId);
    }
    public function setName(string $name):void {
        $this->name = $name;
    }
    public function setText(string $text):void {
        $this->text = $text;
    }
    public function setAuthor(User $author):void {
        $this->authorId = $author->getId();
    }
    public function setId(int $id):void {
            $this->id = $id;
    }

}