<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 23.03.19
 * Time: 21:36
 */

namespace services;

use Sonata\Common\Db\DbServiceAbstract;

class DbService extends DbServiceAbstract
{
    public function getComplexes()
    {
        $rs = $this->select('complex', ['city_id' => 1, 'mass_market' => true], ['name' => SORT_ASC]);

        return array_combine(array_map(function ($v) {return $v['id'];}, $rs), $rs);
    }
}