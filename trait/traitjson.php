<?php

trait TraitJSON {

	public function array_to_json_string($array, $tabs = "\t") {
		$json_str   = "{\n";
		$json_chunks    = [];
		foreach($array as $k=>$el) {
			if(is_array($el)) {
				if(!ArrayHelper::is_assoc_array($el)) {
					$el = array_map(function($value) use($tabs) {
						if($value === true) {
							return '"1"';
						} elseif($value === false) {
							return '""';
						} elseif(is_array($value)) {
							return $this->array_to_json_string($value, $tabs."\t");
						} elseif(is_object($value)) {
							return $this->array_to_json_string((array)$value, $tabs."\t");
						} else {
							return "\"{$value}\"";
						}
					}, $el);
					$json_chunks[]  = "{$tabs}\"{$k}\"\t: [".implode(',', $el).']';
				} else {
					$json_chunks[]   = "{$tabs}\"{$k}\"\t: ".$this->array_to_json_string($el, $tabs."\t");
				}
			} else {
				$json_chunks[]   = "{$tabs}\"{$k}\"\t: \"{$el}\"";
			}
		}
		$json_str   .= implode(",\n", $json_chunks);
		$json_str   .= "\n".preg_replace("/\t/",'',$tabs,1).'}';
		return $json_str;
	}
}