<?php
class SystemModel extends SuperModel {

    public function dump_db($return_dump = false) {
        $tables = $this->execute('SHOW TABLES');
        $dump   = '';
        $quote_str  = function($str) {
            return "'{$str}'";
        };
        foreach($tables as $el) {
            $key    = array_keys($el)[0];
            $table_name = $el[$key];
            $rows   = $this->select($table_name);
            $rows_str   = '';
            if(!empty($rows[0]) && is_array($rows[0])) {
                foreach($rows as $el2) {
                    $el2    = array_map($quote_str, $el2);
                    $rows_str   .= '('.implode(',', $el2).')';
                }
            } else {
                $rows   = array_map($quote_str, $rows);
                $rows_str   = '('.implode(',', $rows).')';
            }
            $dump   .= "TRUNCATE `{$table_name}`\n\nINSET INTO `{$table_name}` VALUES {$rows_str}\n\n";
        }
        if($return_dump) {
            return $dump;
        } else {
            file_put_contents(ROOT_PATH.DS.'resource'.DS.'dump_db.sql', $dump);
            return md5($dump);
        }
    }

    public function update_db_from_dump() {
        $backup_file    = ROOT_PATH.DS.'resource'.DS.'database_backup.sql';
        $backup_data    = $this->dump_db($return_dump   = true);
        file_put_contents($backup_file, $backup_data);

        $sql    = file_get_contents(ROOT_PATH.DS.'resource'.DS.'dump_db.sql');
        $sql_chunks    = explode("\n\n", $sql);
        foreach($sql_chunks as $el) {
            $this->execute($el);
        }
        return md5($sql);
    }
}