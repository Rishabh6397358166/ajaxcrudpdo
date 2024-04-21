<?php
require_once 'incudes/database.php';

class player extends database
{
    // table name
    protected $tableName = 'playersdb';

    /*
    * function is used to add records
    * @param array $data
    * @return int lastInsertedId()
    */
    public function add($data)
    {
        if (!empty($data)) {
            $fields = $placeholders = [];
            foreach ($data as $field => $value) {
                $fields[] = $field;
                $placeholders[] = ":{$field}";
            }
        }

        $sql = "INSERT INTO {$this->tableName} (" . implode(',', $fields) . ")
                VALUES(" . implode(',', $placeholders) . ")";
        
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            $lastInsertedId = $this->conn->lastInsertId(); // Corrected method name
            $this->conn->commit();
            return $lastInsertedId;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $this->conn->rollback();
        }
    }

    /*
    * function is used to get records
    * @param int $start
    * @param int $limit
    * @return array $result
    */
    public function getRows($start = 0, $limit = 4)
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY id DESC LIMIT {$start}, {$limit}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        return $result;
    }




    public function getCount()
    {
        $sql = "SELECT count(*) as pcount FROM {$this->tableName}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];

    }

    /*
    * function is used to get single records based on the column value
    * @param string $field
    * @param mixed $value
    * @return array $result
    */
    public function getRow($field, $value)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE {$field} = :value";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':value' => $value]); // Corrected placeholder key
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        return $result;
    }

    /*
    * function is used to upload file
    * @param array $file
    * @return string $newFileName
    */
    public function uploadPhoto($file)
    {
        if (!empty($file)) {
            $fileTempPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $allowed = ["jpg", "png", "gif", "jpeg","svg"];
            if (in_array($fileExtension, $allowed)) {
                $uploadFileDir = getcwd() . '/uploads/';
                $destFilePath = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTempPath, $destFilePath)) {
                    return $newFileName;
                }
            }
        }
    }
}

?>
