<?php

namespace my\Models;
use my\Exceptions\InvalidArgumentException;
use my\Services\Db_psql;

abstract class ActiveRecordEntity{
    protected $id;

    abstract protected static function getTableName(): string;

    private function underscoreToCamelCase(string $source): string {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }
    private function camelCaseToUnderscore(string $source): string{
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }
    private function mapPropertiesToDbFormat():array {
        $reflection = new \ReflectionObject($this);
        $properties = $reflection->getProperties();
        foreach($properties as $property){
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
    }
    private function update($mappedProperties):void{
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index; // :param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[':param' . $index] = $value; // [:param1 => value1]
            $index++;
        }
        $sql= 'update '. static::getTableName().' set '.implode($columns2params, ', ').' where id='.$this->getId();
        $db = Db_psql::getInstance();
        $db->query($sql, $params2values, static::class);

    }
    private  function insert($mappedProperties):void {
        $columns = [];
        $params = [];
        $params2values = [];
        $mappedFilteredProperties = array_filter($mappedProperties);
        foreach ($mappedFilteredProperties as $column => $value) {
            $columns[] = '`'.$column.'`';
            $paramsName = ':'.$column;
            $params[] = $paramsName;
            $params2values[$paramsName] = $value;
        }
        $columnsViaSemicolon = implode($columns, ', ');
        $paramsViaSemicolon = implode($params, ', ');
        $sql = 'insert into '. static::getTableName().'('.$columnsViaSemicolon.') values ('.$paramsViaSemicolon.');';
        //return print_r($params2values);

        $db = Db_psql::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    public static function findByOneColumn($columnName, $value): ?self{
        $db= Db_psql::getInstance();
        $result = $db->query('select * from '.static::getTableName().' where '.$columnName.' = :value limit 1', [':value'=>$value], static::class);
        if (!$result) return null;
        return $result[0];
    }

    public function refresh(){
        $objectDB = static::getById($this->id);
        $properties = get_object_vars($objectDB);
        foreach ($properties as $key => $value){
            $this->$key = $value;
        }
    }
    public function delete():void{
        $sql= 'delete from '. static::getTableName().' where id=:id;';
        $db = Db_psql::getInstance();
        $db->query($sql, [':id' => $this->id]);
    }
    public function save():void{
        $mappedProperties = $this->mapPropertiesToDbFormat($this);
        ($this->id !== null)? $this->update($mappedProperties) : $this->insert($mappedProperties);
    }



    public function getId():int{
        return $this->id;
    }
    public function getAuthorId():int {
        return (int) $this->authorId;
    }
    public function findAll(){
        $db = Db_psql::getInstance();
        return $db->query('SELECT * FROM `'.static::getTableName().'`;', [], static::class);
    }
    public function getById(int $id): ?self{
        $db = Db_psql::getInstance();
        $entities = $db->query('SELECT * FROM `'.static::getTableName().'` where id=:id;', [':id' => $id],static::class);
        return $entities ? $entities[0] : null;
    }
    public function __SET($name, $value){
        $underscoreToCamelCase = $this->underscoreToCamelCase($name);
        $this->$underscoreToCamelCase = $value;
    }
}