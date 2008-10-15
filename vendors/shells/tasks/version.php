<?php

// add automatic dropping/truncating of tables
// add automatic creation of shadow tables for all

uses('file', 'model' . DS . 'schema');

class VersionShell extends Shell {
	
	var $tasks = array('Model');
	
	function startup() {
		$this->out('');
		$this->out('VersionBehavior Shadow Table Manager');
		$this->out('');
		$this->out('Commands:');
		$this->out(' shadow - create a table to hold version info for a model');
		$this->out('');
	}
		
	function main() {
		
	}
	
	/**
	 * Method for me to upgrade my old VersionBehavior tables
	 *
	 */
	function convert() {
		$db    = ConnectionManager::getDataSource('default');
		$tables = $db->listSources();
		
		$count = count($tables);
		for ($i=0; $i<$count; $i++) {
			$this->out("$i: {$tables[$i]}");
		}
		$id = $this->in("Pick One: ");
		
		$pre   = Inflector::singularize($table);
		$table = $pre . '_versions';
		
		$db->begin();
			
		$sql[] = "alter table $table add version_id number";
		$sql[] = "update $table set version_id = id";
		$sql[] = "alter table $table drop constraint pk_{$pre}_versions";
		$sql[] = "update $table set id = {$pre}_id";
		$sql[] = "alter table $table drop column {$pre}_id";
		debug($sql);
		
		$sql = "select id, created from $table";
		$results = $db->query($sql);
		
		debug($results);exit;
		
		
		exit;
	}
	
	function kill_oracle() {
		$db    = ConnectionManager::getDataSource('default');
		$model = $this->Model->getName('default');
		$model = ClassRegistry::init($model);
		
		$table_old = $model->table;
		$table_new = Inflector::underscore($model->name) . '_versions';
		
		$sql = "drop table $table_new";
		$db->execute($sql);
		
		$sql = "drop sequence {$table_new}_seq";
		$db->execute($sql);
		
		$sql = "drop trigger pk_{$table_new}_trg";
		$db->execute($sql);
	}
	
	function shadow_oracle() {
		
		$db    = ConnectionManager::getDataSource('default');
		$model = $this->Model->getName('default');
		$model = ClassRegistry::init($model);
		
		$table_old = $model->table;
		$table_new = Inflector::underscore($model->name) . '_versions';
		
		$sql = "create table $table_new as select * from $table_old";
		print $sql . "\n";
		$db->execute($sql);

		$sql = "alter table $table_new add version_id number";	
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "alter table $table_new add vb_date_start date";
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "alter table $table_new add vb_date_end date";
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "update $table_new set vb_date_start = SYSDATE";
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "create sequence {$table_new}_seq";
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "CREATE OR REPLACE TRIGGER pk_$table_new" . "_trg BEFORE INSERT ON $table_new FOR EACH ROW BEGIN SELECT {$table_new}_seq.NEXTVAL INTO :NEW.VERSION_ID FROM DUAL; END;";
		print $sql . "\n";
		$db->execute($sql);
		
		$sql = "update $table_new set version_id = {$table_new}_seq.nextval";
		print $sql . "\n";
		$db->execute($sql);
	}

	/**
	 * Shows which tables have had more than one revision thus indicating versioning
	 * is active for that table, and vice versa
	 *
	 */
	function check() {
		$db    = ConnectionManager::getDataSource('default');
		$tables = $db->listSources();
		
		$active = array();
		$inactive = array();
		$shadow = array();
		foreach ($tables as $t) {
			if (strstr($t, "_versions")) {
				$shadow[] = $t;
				$sql = "SELECT COUNT(*) AS cnt FROM $t WHERE vb_date_end IS NOT NULL";
				$res = $db->query($sql);
				if ($res[0][0]['cnt']) {
					$active[] = $t;
				} else {
					$inactive[] = $t;
				}
			}
		}
		$this->out('Active: ' . implode(', ', $active));
		$this->out('');
		$this->out('Inactive: ' . implode(', ', $inactive));	
	}
		
	/**
	 * Create a shadow table for a given model
	 *
	 * @return unknown
	 */
	function shadow() {
		
		$db    = ConnectionManager::getDataSource('default');
		$this->out('Please select a model for which to create a shadow.');
		$this->out('');
		$model = $this->Model->getName('default');
		$model = ClassRegistry::init($model);
		
		$table_old = $model->table;
		$table_new = Inflector::singularize($model->table) . '_versions';
		
		$sql = "CREATE TABLE $table_new AS SELECT * FROM $table_old";
		$db->execute($sql);
		
		$sql = "ALTER TABLE $table_new ADD COLUMN version_id INT(6) AUTO_INCREMENT PRIMARY KEY";
		$db->execute($sql);
		
		$sql = "ALTER TABLE $table_new ADD COLUMN vb_date_start DATETIME";
		$db->execute($sql);
		
		$sql = "ALTER TABLE $table_new ADD COLUMN vb_date_end DATETIME";
		$db->execute($sql);
		
		$sql = "UPDATE $table_new SET vb_date_start = NOW()";
		$db->execute($sql);
		
		$this->out('Successfully created shadow table.');

		/*
		$this->Schema = new CakeSchema();
		$schema = $this->Schema->read();
		
		$table_schema = $schema['tables']['missing'][$table_new];
		#unset($table_schema['id']['key']);
		
		$table_schema['indexes'] = array('PRIMARY' => array('column' => 'version_id', 'unique' => 1));
		$table_schema['version_id'] = array('type' => 'integer', 'length' => 6, 'key' => 'primary');
		$table_schema['vb_date_start'] = array('type' => 'float', 'length' => '12,2');
		$table_schema['vb_date_end'] = array('type' => 'float', 'length' => '12,2');
		

		$old['tables'][$table_new] = $schema['tables']['missing'][$table_new];
		$new['tables'][$table_new] = $table_schema;
		
		$result = $this->Schema->compare($old, $new);
		
		if ($alter = $db->alterSchema($result)) {
			debug($alter);exit;
			if ($db->execute($alter)) {
				$this->out("Successfully created $table_new.");	
				exit;
			}
		} 
		$this->out('Failed to create shadow table.');
		*/		
		
	}
	
	
	
}

?>