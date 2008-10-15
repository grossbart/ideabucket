<?php

class LogHelper extends AppHelper {
	
	var $helpers = array('Text');
		
	var $format = "{date} - {who} - modified {Person.name}'s record, {changes}";
	
	var $tags = array(
		'date' => ''
	);
	
	function __construct() {
		$this->Text = new TextHelper;
	}
	
	function __getModels($data) {
		$aliases = array();
		foreach ($data as $key => $model) {
			if (is_array($model)) {
				if (!is_numeric($key)) {
					$aliases[] = $key;
				}
				$aliases = array_merge($aliases, $this->__getModels($model));
			}
		}
		return $aliases;
	}
	
	function __getAliases($data) {
		$models = $this->__getModels($data);
		$aliases = array();
		foreach ($models as $model) {
			if (ClassRegistry::isKeySet(Inflector::underscore($model))) {
				$m = ClassRegistry::getObject(Inflector::underscore($model));
			} else {
				$m = ClassRegistry::init($model);
			}
			if (method_exists($model, 'aliases')) {
				$schema = $m->aliases($data);
				$aliases[$model] = $schema[$model]['name'];
				foreach ($schema[$model]['fields'] as $k => $v) {
					if (strstr($v, '{')) {
						$aliases[$model.'.'.$k] = $v;
					} else {
						if ($v) {
							$aliases[$model.'.'.$k] = $model.'.'.$v;	
						} else {
							$aliases[$model.'.'.$k] = null;
						}
					}
				}
			} else {
				$schema = $m->schema();
				foreach ($schema as $k => $v) {
					$aliases[$model.'.'.$k] = $model.'.'.$k;
				}
			}
			
		}
		return $aliases;
	}
	
	function __interpolate($str, $data, $n = null) {
		preg_match_all('/\{[A-Z0-9a-z\._\[\]]+\}/', $str, $tokens);
		$tokens = $tokens[0];
		foreach ($tokens as $t) {
			if (strstr($t, '[n]')) {
				$t = str_replace(array('{', '}'), null, $t);
				$nt = str_replace(array('{', '}'), null, $t);
				$nt = str_replace('[n]', $n, $nt);
				$str = str_replace('{'.$t.'}', Set::extract($data, $nt), $str);
			} else {
				$t = str_replace(array('{', '}'), null, $t);
				$str = str_replace('{'.$t.'}', Set::extract($data, $t), $str);
			}
		}
		return $str;
	}
			
	function toText($new, $old, $diff) {
		
		$format = $this->format;
		$aliases = $this->__getAliases(array_merge($new, $old));
	
		preg_match_all('/\{[A-Z0-9a-z\._]+\}/', $format, $tokens);
		$tokens = $tokens[0];
		
		$vars = array();
		foreach ($tokens as $t) {
			$vars[] = str_replace(array('{', '}'), '', $t);
		}

		foreach ($vars as $v) {
			if (array_key_exists($v, $aliases)) {
				$format = str_replace('{'.$v.'}', Set::extract($new, $aliases[$v]), $format);	
			} else {
				if ($value = Set::extract($new, $v)) {
					$format = str_replace('{'.$v.'}', $value, $format);
				}
			}
		}
		
		$changes = array(
			'added' => array(),
			'modified' => array(),
			'deleted' => array()
		);
		$stack = array();
		foreach ($diff as $op => $model) {
			foreach ($model as $name => $data) {
				$stack[] = $name;
				$i = 0;
				foreach ($data as $k => $d) {
					$i++;
					if (!is_array($d)) {
						if (array_key_exists($name, $aliases)) {
							$name = $this->__interpolate($aliases[$name], $new);
						}
						$stack[] = $k;
						$alias = implode('.', $stack);
						if (array_key_exists($alias, $aliases) && $aliases[$alias]) {
							$changes[$op][$i][$name][] = $this->__interpolate($aliases[$alias], $new);
						} elseif (array_key_exists($alias, $aliases) && !$aliases[$alias]) {
							//
						} else {
							$changes[$op][$i][$name][] = $k;
						}
						array_pop($stack);
					} elseif (is_numeric($k)) {
						if (array_key_exists($name, $aliases)) {
							$name = $this->__interpolate($aliases[$name], $new, $k);
						}
						foreach ($d as $nestedKey => $nestedData) {
							$i++;
							if (!is_array($nestedData)) {
								$stack[] = $nestedKey;
								$alias = implode('.', $stack);
								if (array_key_exists($alias, $aliases) && $aliases[$alias]) {
									$changes[$op][$i][$name][] = $this->__interpolate($aliases[$alias], $new, $k);
								} elseif (array_key_exists($alias, $aliases) && !$aliases[$alias]) {
									//
								} else {
									$changes[$op][$i][$name][] = $nestedKey;
								}
								array_pop($stack);
							}	
						}
					}

				}
				array_pop($stack);
			}
		}

		$str = '';
		foreach ($changes as $op => $data) {
			if ($data) {
				$str .= $op;
			}
			$i = 0;
			$icount = count($data);
			foreach ($data as $k => $v) {
				foreach ($v as $alias => $d) {
					$i++;
					$str .= " $alias ";
					$tmp = array();
					foreach ($d as $e) {
						$tmp[] = $e;
					}
					$str .= $this->Text->toList($tmp);
					if ($i != $icount) {
						$str .= ', ';
					}
				}
			}
		}
		
		$format = str_replace('{changes}', $str, $format);
		return $format;
	}
	
	function alias($diff) {
		
		$aliases = $this->__getAliases($diff);
		extract($this->__getMeta());
		
		$aliased = array(
			'added' => array(),
			'modified' => array(),
			'deleted' => array()
		);
		
		foreach ($diff as $op => $data) {
			foreach ($data as $key => $d) {
				if (is_array($d)) {
					if (array_key_exists($key, $aliases)) {
						$parent = $aliases[$key];
					} else {
						$parent = strtolower(str_replace('_', ' ', "$key's "));
					}
					foreach ($d as $k => $z) {
						if (array_key_exists($key.'.'.$k, $aliases)) {
							$aliased[$op][$parent][$aliases[$key.'.'.$k]] = $z;
						} else {
							$aliased[$op][$parent][strtolower(str_replace('_', ' ', $k))] = $z;
						}
					}
				}
			}
		}
		return $aliased;
	}
			
}

?>