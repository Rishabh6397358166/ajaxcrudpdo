<?php
class database{
    private $dbserver ='localhost';
    private $dbuser='root';
    private $dbpassword='';
    private $dbname='crudajax';
    protected $conn;



    public function __construct()
    {
        //construct ka use hum isiliye krte hai jab bhi hum kisi 
        //class ka object banatsae hai to constructor function automatically call ho jata hai
        try{
            $dsn="mysql:host={$this->dbserver};dbname={$this->dbname}; charset=utf8";
            $options=[PDO::ATTR_PERSISTENT];
            $this->conn=new PDO($dsn,$this->dbuser,$this->dbpassword,$options);
        }
        catch(PDOException $e){
echo "Connection error: " . $e->getMessage();
        }
       
    }
}




?>