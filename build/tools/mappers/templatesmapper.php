<?php

namespace Tools\mappers;

use adapters\FileAdapter;
use classes\Application;
use collections\BaseCollection;
use mappers\BaseMapper;
use Tools\models\TemplateModel;

/**
 * Class TemplatesMapper
 * @package Tools\mapper
 *
 * @property FileAdapter $adapter
 */
class TemplatesMapper extends BaseMapper {

    /**
     * @return FileAdapter
     * @throws \InvalidArgumentException
     */
    public function get_adapter() {
        /**
         * @var $ext \Tools
         */
        $ext = Application::get_class(\Tools::class);
        return new FileAdapter($ext->get_path().DS.'resource'.DS.'templates.json');
    }

    /**
     * @param int $number
     * @param int $per_page
     * @return BaseCollection
     */
    public function get_page($number = 1, $per_page = 30) {
        $number < 0 && $number = 0;
        $collection = new BaseCollection(TemplateModel::class);
        $collection->load($this->adapter->select()->skip(($number-1)*$per_page)->take($per_page)->toArray());
        return $collection;
    }
}
