<?php

namespace Tools\mappers;

use common\adapters\FileAdapter;
use common\classes\Application;
use common\collections\BaseCollection;
use common\mappers\BaseMapper;
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
        return new FileAdapter($this->get_file_path());
    }

    private function get_file_path() {
        $file = 'resource'.DS.'templates.json';
        $build_dir = ROOT_PATH.DS.'build'.DS.'tools';
        if(is_dir($build_dir)) {
            return $build_dir.DS.$file;
        } else {
            $tools = Application::get_class(\Tools::class);
            return $tools->get_path().DS.$file;
        }
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
            $this->adapter->select()
                ->skip(($number-1)*$per_page)
                ->take($per_page)
                ->toArray()
        );
        return $collection;
    }
}
