<?php
class database
{
    private $db_handle;
    private $user_name;
    private $password;
    private $data_base;
    private $host_name;
    private $sql;
    private $results;

    function __construct($host="localhost",$user,$passwd)
    {
        $this->db_handle = mysql_connect($host,$user,$passwd);
    }

    function dbSelect($db)
    {
        $this->data_base = $db;
        if(!mysql_select_db($this->data_base, $this->db_handle))
        {
            error_log(mysql_error(), 3, "/phplog.err");
            die("Error connecting to Database");
        }
    }
    
    function executeSql($sql_stmt)
    {
        $this->sql = $sql_stmt;
        $this->result = mysql_query($this->sql);
    }
    function returnResults()
    {
        return $this->result;
    }
}
$host = "localhost";
$user = "webserver";
$passwd = "345456";
$db = "service_activations"; 
$sql = "SELECT * FROM current_orders";

$dbObject = new database($host,$user,$passwd);
$dbObject->dbSelect($db);
$dbObject->executeSql($sql);


$res = $dbObject->returnResults();

$newFileName = "emp_names.csv";

$fpWrite = fopen("C:\\$newFileName", "w");

$nameStr = "";

while($record = mysql_fetch_object($res))
{
    $name = $record->empname;
    
    $nameArray = explode(",",$name);
    
    if(count($nameArray) > 1)
    {
            $nameTemp = "";
            for($i=0;$i < count($nameArray); $i++)
            {
                $nameTemp = $nameTemp . $nameArray[$i];
                
                if($i != (count($nameArray) - 1))
                    $nameTemp = $nameTemp . "&sbquo;";
            }
            $name = $nameTemp;
    }
    
    $nameStr = $nameStr.$name.",";
}


fwrite($fpWrite,$nameStr);

echo "File operation has been completed successfully!<br><br>";
?> 