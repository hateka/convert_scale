<?php

class Imageresize
{

protected $file_list = array();
protected $reg = null;
protected $old_name = array();
public $resized = array();

function __construct($diname,$ratio)
{
  if(!function_exists('imagecreatetruecolor')){
    exit('You need GD library');
  }

  $this->dirname = $diname;
  $this->reduction_ratio = $ratio;
}

function get_file($dir)
{
  if(@opendir($dir)){
    $this->file_list = scandir($dir);
    return $this->file_list;
  }else{
    exit('No! such dir');
  }
}

function filetype($typearray)
{
  $image = array();
  $this->reg = '/(jpg|gif|png)/';
  for($i=0;$i<count($typearray);$i++){
     if(preg_match($this->reg,$typearray[$i])){
        $image[] = $typearray[$i];
     }
  }
  return $image;
}

function image_info($image)
{
  $file_obj = array();
  for($i = 0;$i<count($image);$i++){
     list($width,$height) = getimagesize($this->dirname.$image[$i]);
     $file_obj[$image[$i]] = array($width,$height);
  }
  return $file_obj;
}

function resize($file_obj)
{
  foreach($file_obj as $key=>$val){
    if($file_obj[$key][0] > $file_obj[$key][1]){
      $long = intval($file_obj[$key][1] * 0.9);
    }else{
      $long = intval($file_obj[$key][0] * 0.9);
    }

    $image_p = imagecreatetruecolor($long,$long);
    $imagin = imagecreatefromjpeg($this->dirname.$key);
    $ratio = $long / 2;
    $divwid = ($file_obj[$key][0] / 2) - $ratio;
    $divhei = ($file_obj[$key][1] / 2) - $ratio;
    $newname = $this->dirname.'sample'.$key;
    $this->resized[] = $newname;
    $this->old_name[] = $key;
    imagecopy($image_p,$imagin,0,0,$divwid,$divhei,$long,$long);
    imagejpeg($image_p,$newname);
    imagedestroy($imagin);
    imagedestroy($image_p);
  }
}

function reduction($resized)
{
  for($i=0;$i<count($resized);$i++){
     list($wd,$he) = getimagesize($resized[$i]);
     $snall_p = imagecreatetruecolor($this->reduction_ratio,$this->reduction_ratio);
     $image = imagecreatefromjpeg($resized[$i]);
     imagecopyresampled($snall_p,$image,0,0,0,0,$this->reduction_ratio,$this->reduction_ratio,$wd,$he);
     imagejpeg($snall_p,$this->dirname.'new_'.$this->old_name[$i]);
     unlink($resized[$i]);
  }
}


}