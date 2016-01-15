<?php

namespace WS\Tools\ORM\Db\Request;

use WS\Tools\ORM\Db\Pager;
use WS\Tools\ORM\Db\Request;
use WS\Tools\ORM\Entity;
use WS\Tools\ORM\EntityCollection;

/**
 * Select definititon
 * 
 * implementation of sorting and pagination
 *
 * @method Select equal(string $attr, mixed $value)
 * @method Select notEqual(string $attr, mixed $value)
 * @method Select inRange(string $attr, mixed $from, mixed $to)
 * @method Select notInRange(string $attr, mixed $from, mixed $to)
 * @method Select hasSubstr(string $attr, mixed $value)
 * @method Select less(string $attr, mixed $value)
 * @method Select more(string $attr, mixed $value)
 * @method Select lessOrEqual(string $attr, mixed $value)
 * @method Select moreOrEqual(string $attr, mixed $value)
 * @method Select in(string $attr, mixed $values)
 * @method Select notIn(string $attr, mixed $values)
 * @method Select logicOr(array $first, array $second)
 *
 * @author my.sokolovsky@gmail.com
 */
class Select extends Request {
    /**
     * @var \WS\Tools\ORM\Db\Pager
     */
    private $pager;
    
    /**
     * @var array
     */
    private $order = array();

    private $relations = array();

    /**
     * @var bool
     */
    private $excludeRepo = false;

    /**
     * Adding collation
     *
     * @param string $path Path to field.
     * @param string $direction sort direction (asc, desc)
     * @return Select
     * @throws \Exception
     */
    public function addSort($path, $direction) {
        if (!in_array(strtolower($direction), array('asc', 'desc'))) {
            throw new \Exception("Sort direction mistake `$path`: `$direction`");
        }
        $this->order[$path] = $direction;
        return $this;
    }
    
    public function setPagerParams($curPage, $countInPage, $tableId = false) {
        $this->pager = new Pager($curPage, $countInPage, $tableId);
        return $this;
    }

    /**
     * Don`t use repo, entity is getting right from database
     * @return $this
     */
    public function notUseRepo() {
        $this->excludeRepo = true;
        return $this;
    }

    /**
     * @return Pager
     */
    public function getPager() {
        return $this->pager;
    }
    
    /**
     * Getting a collection of entities.
     * @return EntityCollection
     */
    public function getCollection() {
        return $this->gateway->find(
            $this->getFilter(),
            $this->order,
            $this->relations,
            $this->pager,
            !$this->excludeRepo
        );
    }

    /**
     * Getting one entity.
     * @return Entity|null
     */
    public function getOne() {
        return $this->gateway->findOne($this->getFilter(), $this->order, $this->relations, !$this->excludeRepo);
    }

    /**
     * The request is expected the relations
     *
     * @param array $rels array like:
     * array(
     *      'rel1' => array('subRelation', 'subRelation2' => array('subSubRel')),
     *      'rel2' => 'subRelation',
     *      'rel3'
     * )
     *
     * At the moment the list of RELATIONS!!!
     * @return $this
     */
    public function withRelations(array $rels) {
        $this->relations = $rels;
        return $this;
    }

    public function count() {
        return $this->gateway->getSelectedRowsCount(
            $this->getFilter()
        );
    }

    public function execute() {
        return $this->getCollection();
    }
}
