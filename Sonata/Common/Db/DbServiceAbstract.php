<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 23.03.19
 * Time: 20:46
 */

namespace Sonata\Common\Db;


use Sonata\App;
use Sonata\Common\ServiceAbstract;
use Sonata\Mvc\ModelAbstract;

abstract class DbServiceAbstract extends ServiceAbstract
{

    protected $quoteTempl = null;

    public function getApp()
    {
        return App::getInstance();
    }

    public function getPdo()
    {
        return $this->getApp()->getPdo();
    }


    public function quotes($name)
    {
        if ($this->quoteTempl === null) {
            switch ($this->getApp()->getConfig()->get('db')['pdo_driver']) {
                case 'pgsql':
                    $this->quoteTempl = '"%s"';
                    break;
                case 'mysql':
                    $this->quoteTempl = '`%s`';
                    break;
                default:
                    return '%s';
            }
        }

        return sprintf($this->quoteTempl, $name);
    }

    public function insertTbl ($tableName, array $values)
    {
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->quotes($tableName),
            implode(', ', array_map(function ($v) {return $this->quotes($v);}, array_keys($values))),
            implode(', ', array_map(function($v) { return '?';}, $values))
        );
        $stmt = $this->getPdo()->prepare($sql);
        $i = 1;
        foreach ($values as &$param) {$stmt->bindParam($i++, $param);}
        $r = $stmt->execute();
        return $r;
    }

    public function updateTbl ($tableName, array $values, array $conditions)
    {
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $this->quotes($tableName),
            implode (', ', array_map(function ($v) { return $this->quotes($v) . ' = ?'; }, array_keys($values))),
            $this->getWhereString($conditions)
        );

        $stmt = $this->getPdo()->prepare($sql);
        $i = 1;
        foreach ($values as &$param) {$stmt->bindParam($i++, $param);}
        foreach ($conditions as &$param) {$stmt->bindParam($i++, $param);}
        $r = $stmt->execute();
        return $r;
    }

    public function deleteTbl ($tableName, array $conditions)
    {
        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $this->quotes($tableName),
            $this->getWhereString($conditions)
        );

        $stmt = $this->getPdo()->prepare($sql);
        $i = 1;
        foreach ($conditions as &$param) {$stmt->bindParam($i++, $param);}
        $r = $stmt->execute();
        return $r;
    }

    public function selectTbl ($tableName, array $conditions, array $orderBy = [])
    {
        $sql = sprintf(
            "SELECT * FROM %s WHERE %s",
            $this->quotes($tableName),
            $this->getWhereString($conditions)
        );

        if (count($orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(function ($v1, $v2) {return $this->quotes($v1) . ($v2 == SORT_DESC ? ' DESC' : ' ASC');}, array_keys($orderBy), $orderBy));
        }

        $stmt = $this->getPdo()->prepare($sql);
        $i = 1;
        foreach ($conditions as &$param) {$stmt->bindParam($i++, $param);}
        $r = $stmt->execute();
        $rs = $stmt->fetchAll();
        return $rs;
    }

    public function save(ModelAbstract $model)
    {

    }


    protected function getWhereString(array $conditions)
    {
        return implode (' AND ', array_map(
                function ($v) {return (strpos($v, '?') === false ? '(' . $this->quotes($v) . ' = ?)' : '(' . $v . ')'); },
                array_keys($conditions)
            )
        );
    }

}