<?php
class UploadFile{
  
  protected $_uploaded = array();
  protected $_destination;  
  protected $_max = 1024000;
  protected $_messages = array();
  protected $type = array(
                'image/gif',
                'image/jpeg',
                'image/pjpeg',
                'image/png' 
  );
  protected $_renamed = false;
  private  $path = "";
  public static $con = false; 
  /**
   * 
   * @param mix $path
   * 
   */
  private function __construct(){
    $config = $GLOBALS["config"]["upload"];
    $this->path = isset($config["uplaodPath"])?$config["uplaodPath"]:'upload';
    $this->type = isset($config["type"])?$config["type"]: array('image/gif','image/jpeg','image/pjpeg','image/png' );
    $this->_max = isset($config["max_length"])?$config["max_length"]:1024000;
    $date = @date('Ymd');
    $this->path = $this->path."/".$date."/";
    // if (!is_dir($path)||!is_writable($path)){
    //   throw new Exception("文件名不可写，或者不是目录！");
    // }

    if (!file_exists ($this->path)){
        mkdir ( $this->path, 0777, true);
    }
    $this->_destination = $this->path;
    $this->_uploaded = $_FILES;
     //d($this->_destination);
  }

  private function __clone(){

  }

  /**
   *  向外提供接口
   */
  public static function getUploadFile(){
    if(self::$con==false){
      self::$con = new UploadFile();
    }
    return self::$con;
  }

  /**
   * 移动文件
   *  @param retrun 返回一个消息数组
   */
  public function upload(){
      
    $filed = current($this->_uploaded); 
    $isOk = $this->checkError($filed['name'], $filed['error']);
    //debug ok
    if ($isOk){
      $sizeOk = $this->checkSize($filed['name'], $filed['size']);
      $typeOk = $this->checkType($filed['name'], $filed['type']);
      if ($sizeOk && $typeOk){
          
        $success = move_uploaded_file($filed['tmp_name'], $this->_destination.$filed['name']);
        d($this->_destination);
          
        if ($success){
          $this->_messages["staut"] ="ok";
          $this->_messages["msg"] = $filed['name']."文件上传成功";
          $this->_messages["path"] = $this->_destination.$filed['name'];
        }else {
          $this->_messages["staut"] ="error";
          $this->_messages["msg"] = $filed['name']."文件上传失败";
        }
        return $this->_messages;
      }
        
    }
    $this->_messages["staut"] = "error";
    return $this->_messages;
  }
  /**
   * 查询messages数组内容 
   *
   */
  public function getMessages(){
    return $this->_messages;
  }
    
  /**
   * 检测上传的文件大小
   * @param mix $string
   * @param int $size
   */
  public function checkSize($filename, $size){
      
    if ($size == 0){
      return false;
    }else if ($size > $this->_max){
      $this->_messages["msg"] = "文件超出上传限制大小".$this->getMaxsize();
      return false;
    }else { 
      return true;
    }
  }
    
  /**
   * 检测上传文件的类型
   * @param mix $filename
   * @param mix $type
   */
  protected function checkType($filename, $type){
    if (!in_array($type,$this->type)){
      $this->_messages["msg"] = "该文件类型是不被允许的上传类型";
      return false;
    }else {
      return true;
    }
  }
    
  /**
   * 获取文件大小
   * 
   */
  public function getMaxsize(){
    return number_format($this->_max / 1024, 1).'KB';
  }
    
  /**
   * 检测上传错误
   * @param mix $filename
   * @param int $error
   * 
   */
  public function checkError($filename, $error){
    switch ($error){
      case 0 : return true;
      case 1 :
      case 2 : $this->_messages["msg"] = "文件过大！"; return true;
      case 3 : $this->_messages["msg"] = "错误上传文件！";return false;
      case 4 : $this->_messages["msg"] = "没有选择文件!"; return false;
      default : $this->_messages["msg"] = "系统错误!"; return false;
    }
  }
}