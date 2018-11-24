<?php
/**
 *================================================================ 
 * Mysql.class.php 数据库操作类，负责数据库的底层操作
 * @author realYangex
 * @version 2018-08-18
 *================================================================
 */

class Mysql{
	protected static $con = false;  //数据库连接资源
	protected $sql;           //sql语句
	/**
	 * 构造函数，负责连接服务器、选择数据库、设置字符集等
	 * @param $config string 配置数组
	 */
	private function __construct($config = array())
	{	
		$config = $GLOBALS["config"]["database"];
		$dbtype = 'mysql';
		$host = isset($config['host'])? $config['host'] : 'localhost';
		$user = isset($config['user'])? $config['user'] : 'root';
		$password = isset($config['password'])? $config['password'] : '';
		$dbname = isset($config['dbname'])? $config['dbname'] : '';
		$port = isset($config['port'])? $config['port'] : '3306';
		$charset = isset($config['charset'])? $config['charset'] : 'utf8';
		$dsn = "$dbtype:host=$host;dbname=$dbname";
		try
		{
			$this->con = new PDO($dsn,$user,$password);
			$this->con->query("set  names ".$charset);
		}
		catch (PDOException $e)
		{
		 die ("Error!: " . $e->getMessage() . "<br/>");
		}

	}
	/** 
	 * 防止克隆
	 *
	 */
	private function __clone()
	{

	}
	/**
	 *	获取数据库示例
	 *
	 */
	public static function con()
	{
		if(self::$con==false)
		{	
			$config = $GLOBALS["config"];
			self::$con = new Mysql($config);
		}
		return self::$con;

	}

	/**
	 *	支持默认的查询方法 
	 * @param String $sql sql 语句
	 * @return 资源对象
	 */
	public function query($sql)
	{
		$PDOStatement = $this->con->prepare($sql);
		if(substr($sql, 0,6)=="select")
		{
			$PDOStatement->execute();
			$list = array();
			while ($row = $PDOStatement->fetch(PDO::FETCH_ASSOC)){
				$list[] = $row;
			}
			return $list;
		}else
		{
			return $PDOStatement->execute();
		}
		
	}


	/**
	 *	插入方法 
	 * @param String $table 数据表 
	 * @param Array $data 插入的数组
	 * @return 资源对象
	 */
	public function insert($table,$data)
	{
		if(empty($table)) return 0;
		if(count($data)==0) return 0;
		$fields = "(";
		$values = "values(";
		foreach ($data as $key => $value) 
		{
			$fields.=$key.",";
			$values.="?,";
		}
		$fields = substr($fields, 0,strlen($fields)-1).")";
		$values = substr($values, 0,strlen($values)-1).")";
		$sql = "insert into `$table`".$fields.$values;
		//echo $sql;
		$PDOStatement = $this->con->prepare($sql);
		$i = 1;
		foreach ($data as $key => $value) 
		{
			$PDOStatement->bindValue($i,$value);
			$i++;
		}
		$result = $PDOStatement->execute();
		if($result)
		{
			return $this->con->lastInsertId();
		}
		return 0;


	}

	/**
	 *	查询所有的方法 
	 * @param String $table 数据表
	 * @param String $orderBy 排序 默认为空
	 * @return Array 二维数组
	 */
	public function all($table,$orderBy="")
	{
		if(empty($table)) return 0;
		$sql = "select * from `$table` $orderBy";
		$PDOStatement = $this->con->prepare($sql);
		
		$PDOStatement->execute();
		$list = array();
		while ($row = $PDOStatement->fetch(PDO::FETCH_ASSOC)){
			$list[] = $row;
		}
		return $list;
	}

	/**
	 *	条件查询方法 
	 * @param String $table 数据表
	 * @param String $where 条件
	 * @param String $orderBy  排序默认为空
	 * @return Array 二维数组
	 */
	public function find($table,$where="",$orderBy="")
	{
		if(empty($table)) return 0;
		$sql = "select * from `$table`".$where." ".$orderBy;
		$PDOStatement = $this->con->prepare($sql);
		$PDOStatement->execute();
		$list = array();
		while ($row = $PDOStatement->fetch(PDO::FETCH_ASSOC)){
			$list[] = $row;
		}
		return $list;
	}

	/**
	 *	查询一条记录的方法 
	 * @param String $table 数据表
	 * @param String $where 条件
	 * @return Array 二维数组
	 */
	public function one($table,$where="")
	{
		if(empty($table)) return 0;
		$sql = "select * from `$table`".$where." limit 0,1";
		$PDOStatement = $this->con->prepare($sql);
		$PDOStatement->execute();
		$list = array();
		while ($row = $PDOStatement->fetch(PDO::FETCH_ASSOC)){
			$list[] = $row;
		}
		return $list;
	}
	
	/**
	 *	分页查询方法 
	 * @param String $table 数据表
	 * @param String $orderBy 排序 默认为空
	 * @return Array 二维数组
	 */
	public function page($table,$where="",$orderBy="",$pagesize=10,$page=1)
	{
		if(empty($table)) return 0;
		$start = ($page-1)*10;
		$sql = "select * from `$table`".$where." ".$orderBy." limit ".$start.",".$pagesize;
		$PDOStatement = $this->con->prepare($sql);
		$PDOStatement->execute();
		$list = array();
		while ($row = $PDOStatement->fetch(PDO::FETCH_ASSOC)){
			$list[] = $row;
		}
		return $list;
	}

	/**
	 *	修改方法 
	 * @param String $table 数据表 
	 * @param String $where 条件
	 * @param Array $data 插入的数组
	 * @return 资源对象
	 */
	public function update($table,$where,$data)
	{
		if(empty($table)) return 0;
		if(empty($where)) return 0;
		if(count($data)==0) return 0;
		$fields = "set ";
		foreach ($data as $key => $value) 
		{
			$fields.=$key."=?,";
		}
		$fields = substr($fields, 0,strlen($fields)-1);
		$sql = "update `$table`".$fields." ".$where;
		$PDOStatement = $this->con->prepare($sql);
		$i = 1;
		foreach ($data as $key => $value) 
		{	
			$PDOStatement->bindValue($i,$value);
			$i++;
		}

		return $PDOStatement->execute();
	}

	/**
	 *	删除方法 
	 * @param String $table 数据表 
	 * @param String $where 条件
	 * @return 资源对象
	 */
	public function delete($table,$where)
	{
		if(empty($table)) return 0;
		if(empty($where)) return 0;
		$sql = "delete from `$table` ".$where;
		$PDOStatement = $this->con->prepare($sql);
		return $PDOStatement->execute();
	}

}