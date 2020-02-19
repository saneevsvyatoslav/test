<?php
namespace my\Controllers;

use my\View\View;
use my\Models\Articles\Article;

class MainController{
    private $view;
    public function __construct(){
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    public function main(){
        $articles = Article::findAll();
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }
}