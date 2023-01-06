<?php
require '../bd/conexionbd.php';
class returnValues implements JsonSerializable
{
    public $returnCode= 0;
    public $contentFile = '';
    public $fileName='';
    public function __construct (int $returnCode , ?string $contentFile,$fileName)
    {
        $this->returnCode = $returnCode;
        $this->contentFile = $contentFile;
        $this->fileName = $fileName;
    }
    public function jsonSerialize() {
        return $this;
    }
}

class inputVales implements JsonSerializable
{
    public $fileName='';
    public $fileContent='';
    public $parentId = '';
    public function jsonSerialize() {
        return $this;
    }
}

class fileController
{
    private $requestMethod;
    private $conexion;
    public function __construct($requestMethod,$conexion)
    {
        $this->requestMethod=$requestMethod;
        $this->conexion = $conexion;
    }
    public function putFile( $input)
    {
        $inputObject= json_decode($input);
        $file_name=$inputObject->fileName;
        $file_content=$inputObject->fileContent;
        $parent_id = $inputObject->parentId;
        if ($parent_id == null)
            $parent_id = "-1";
        $now = new DateTime();
        $currentDate = $now->getTimestamp();
        $user = $_SESSION['user'];

        $query2 = "select * from files where ownername=? and idParent=? and name=?";
        $stmtu = $this->conexion->prepare($query2);
        $stmtu->bind_param("sss", $user, $parent_id, $file_name);
        $stmtu->execute();
        $result = $stmtu->get_result();
        
        if ( $result->num_rows>0){
            $query3 = "update files set content=?,date=FROM_UNIXTIME(?) where idParent=? and ownername=? and name=?";
            $stmtuu = $this->conexion->prepare($query3);
            $stmtuu->bind_param("sssss", $file_content, $currentDate, $parent_id, $user, $file_name);
            $stmtuu->execute();
            return (json_encode(new returnValues(0, '', $file_name))); //antes exit
            
        } else {
            $query = "INSERT INTO files (date,name,content,ownername,idParent) values (FROM_UNIXTIME(?),?,?,?,?)";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("dssss", $currentDate, $file_name, $file_content, $user, $parent_id);
            $stmt->execute();
            return (json_encode(new returnValues(0, '', $file_name))); //antes exit
        }
    }
    public function getFile ($input)
    {
        $file_name=$input['fileName'];
        $owner = $_SESSION['user'];
        $folder = $input['folder'];
        $query = "select content from files where ownername=? and name=? and idParent=?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("sss", $owner, $file_name,$folder);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $filecontent = $row["content"];
            return ( json_encode (new returnValues(0,$filecontent,$file_name)));
        }
        else
        {
            return ( json_encode (new returnValues(-1,'File not found',$file_name)));
        }
    }
    public function processRequest ($inputGet,$inputPost)
    {
        switch ($this->requestMethod)
        {
            case 'GET':
                return $this->getFile($inputGet);
            case 'POST':
                return $this->putFile($inputPost);
            default:
                return ( json_encode (new returnValues(-1,'Method not allowed','')));

        }
    }
}
