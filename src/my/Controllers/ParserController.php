<?php

namespace my\Controllers;
use my\View\View;
use my\Models\Parser\Parser;
use phpQuery;


class  ParserController{

    /** @var View   */
    private $view;

    /** @var Db */
    private $db_psql;

    public function __construct(){
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    public function view(){

        $html = Parser::getPage([
            "url" => "https://zakupki.gov.ru/epz/eruz/card/general-information.html?revisionId=175882"
        ]);

//start
        if(!empty($html["data"])){

            $content = $html["data"]["content"];

            phpQuery::newDocument($content);
            $values = [];
            foreach ( pq('.blockInfo')->find('.blockInfo__section') as $key => $value){
                $pq = pq($value);
                $values[$key]['title'] = trim( $pq -> find('.section__title') -> text());
                $values[$key]['value'] = trim( $pq -> find('.section__info') -> text());
            }

            phpQuery::unloadDocuments();
            $eruz =[];
            foreach ($values as $key => $item) {
                $toColumn = [
                    'idEruz' => 'Номер реестровой записи в ЕРУЗ',
                    'status' => 'Статус регистрации',
                    'eruzType' => 'Тип участника закупки',
                    'dateStart' => 'Дата регистрации в ЕИС',
                    'dateEnd' => 'Дата окончания срока регистрации в ЕИС',
                    'fio' => 'ФИО',
                    'inn' => 'ИНН',
                    'ogrnip' => 'ОГРНИП',
                    'dateRegistration' => 'Дата регистрации индивидуального предпринимателя',
                    'dateStartDuty' => 'Дата постановки на учет в налоговом органе',
                    'msp' => 'Участник закупки является субъектом малого предпринимательства',
                    'email' => 'Адрес электронной почты',
                    'em' => 'Электронная площадка'];
                $tmp[]=$item;
                foreach ($toColumn as $toCamel => $value) {
                    if($item['title'] == $value) {
                       $eruz[$toCamel] = $item['value'] ;
                    }
                }
            }
            if(!$eruz === false) {
                $parser =  Parser::toInsertE($eruz);
            }
        }
        $this->view->renderHtml('parser/parser_view.php',
            ['tmp' => $parser]
        );
    }

}

