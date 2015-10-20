<?php

namespace Tools\mappers;

use common\adapters\MysqlAdapter;
use common\collections\BaseCollection;
use common\mappers\BaseMapper;
use Tools\models\TemplateModel;

/**
 * Class TemplatesMapper
 * @package Tools\mapper
 *
 * @property MysqlAdapter $adapter
 */
class TemplatesMapper extends BaseMapper {

    /**
     * @return MysqlAdapter
     * @throws \InvalidArgumentException
     */
    public function get_adapter() {
        return new MysqlAdapter('templates');
    }

    /**
     * @param int $number
     * @param int $per_page
     * @return BaseCollection
     */
    public function get_page($number = 1, $per_page = 30) {
        $number < 0 && $number = 0;
        $collection = new BaseCollection(TemplateModel::class);
        $collection->load(
            $this->adapter
                ->select()
                ->limit($per_page)
                ->offset(($number-1)*$per_page)
                ->execute()
                ->get_arrays()
        );
        return $collection;
    }
}
