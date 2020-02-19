<?php
namespace my\Controllers;

use my\Exceptions\UserActivationException;
use my\View\View;
use my\Models\Articles\Article;
use my\Models\Users\User;
class ArticlesController{
    /** @var View   */
    private $view;

    /** @var Db */
    private $db;

    public function __construct(){
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    public function view(int $articleId): void{
        $article = Article::getById($articleId);
        $this->view->renderHtml('articles/view.php',
            ['article' => $article]
        );
    }
    public function edit(int $articleId):void {
        $article = Article::getById($articleId);

        if (!$article){
            throw new \NotFoundException();
        }
        $article -> setName('New name');
        $article -> setText('New text');
    }
    public function add():void{
        $article = new Article;
        $user = User::getById(1);
        $article -> setName('New name5');
        $article -> setText('New text5');
        $article -> setAuthor($user);
        $article -> save();
        var_dump($article);
    }
    public function delete(int $articleId):void{
        $article = Article::getById($articleId);
        if (!$article){
            throw new \NotFoundException();
        }
        $article->delete();
        var_dump($article);
    }
}