<?php
namespace Models;

use Silex\Application;

class BaseModel {

	private $data = array();

	protected $app;
	protected $table;

	public function __construct(Application $app, $table) {
		$this->app = $app;
		$this->table = $table;
	}	

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}

	$error = 'Undefined property via __get(): ' . $name .' in ' . $trace[0]['file'] .' on line ' . $trace[0]['line'];
		throw new Exception($error);
		exit;
	}

	public function find(array $parameters, array $fields=null) {
		$strWhere = "";
		$arrValues = [];
		$fields = (is_null($fields))?"*":implode(", ", $fields);
		$result = null;

		foreach ($parameters as $key => $value) {
			$strWhere .= (($strWhere != "") ? "AND ":" ") .$key." = ? ";
			$arrValues[] = $value;
		}

		$strSql = "SELECT ".$fields." FROM ".$this->table." WHERE".$strWhere;

		try {
			$result = $this->app['db']->fetchAll($strSql, $arrValues);
		} catch (Exception $e) {
			throw new Exception('Database or Query Error: '.$strSql);
			exit;
		}

		return $result;
	}

	public function save(array $parameters) {
		$result = null;

		try {
			$result = $this->app['db']->insert($this->table, $parameters);
		} catch (Exception $e) {
			throw new Exception('Insert '.$this->table.' Error ');
			exit;
		}

		return $result;
	}

	public function update(array $parameters, array $fields=null) {
		$result = null;

		try {
			$result = $this->app['db']->update($this->table, $parameters, $fields);
		} catch (Exception $e) {
			throw new Exception('Update '.$this->table.' Error ');
			exit;
		}

		return $result;
	}
}
