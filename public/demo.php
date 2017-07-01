<?php 
header('content-type:text/html;charset=utf8');
//$conn=mysqli_connect('localhost','root','123456','szwl') or die("error connecting") ; //连接数据库

$dir=dirname(__FILE__);//找到当前脚本的路径
require $dir.'./PHPExcel/PHPExcel/IOFactory.php';
$filename='./ssss.xlsx';
$obj=PHPExcel_IOFactory::load($filename);
$data=$obj->getSheet(0)->toArray();
$arr=array();
foreach($data as $v){
	$id=trim($v[3]);
	$province=trim($v[0]);
	$city=trim($v[1]);
	$area=trim($v[2]);
	$sql ="INSERT INTO szwl_communityID(`community_id`,`province`,`city`,`area`) values('$id','$province','$city','$area');"; //SQL语句 //SQL语句
 	echo $sql;echo '<br>';
	//$result = mysqli_query($conn,$sql); //查询
}
//echo json_encode($arr,JSON_UNESCAPED_UNICODE);
//$data=json_decode($str,true);

//print_r(json_decode($str,true));
