<?php declare(strict_types=1);

namespace App;

class Database
{
	/**
	 * @var \PDO to database
	 */
	private $db;

	public function __construct(string $db_server, string $db_schema, string $db_user, string $db_pass, string $db_charset = 'utf8mb4')
	{
		$dsn = 'mysql:host=' . $db_server . ';dbname=' . $db_schema . ';charset=' . $db_charset;
		$this->db = new \PDO($dsn, $db_user, $db_pass);
		$this->db->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
		$this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		// Fix if database server don't have enabled STRICT_ALL_TABLES. See https://stackoverflow.com/questions/27880035/what-causes-mysql-not-to-enforce-not-null-constraint
		$this->db->query('SET SESSION SQL_MODE=STRICT_ALL_TABLES');
	}

	public function getLink(): \PDO
	{
		return $this->db;
	}

	/**
	 * Shortcut for prepared statement
	 *
	 * @param string $query
	 * @param mixed ...$params
	 * @return bool|\PDOStatement
	 */
	public function query(string $query, ...$params)
	{
		$sql = $this->db->prepare($query);
		$sql->execute($params);
		return $sql;
	}

	/**
	 * Array shortcut for prepared statement
	 *
	 * @param mixed[] $params
	 * @return bool|\PDOStatement
	 */
	public function queryArray(string $query, array $params)
	{
		$sql = $this->db->prepare($query);
		$sql->execute($params);
		return $sql;
	}
}
