<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
 		$this->display();
    }
    public function save()
    {
    	$data=$_POST['data'];
    	$path='savedData'.time().'.txt';
    	$file=fopen($path, 'w');
    	fputs($file,$data);
    	fclose($file);
        echo $path;
    }

    public function readPath()
    {
    	$readPath=$_POST['data'];
    	// echo $readPath;
    	$file=fopen($readPath, 'r');
    	$result=fgets($file,filesize($readPath)+1);
    	fclose($file);
    	echo json_encode($result);
    }

    public function help()
    {		
    	$this->display();
    }
}