<?php
/*
converts sPartner=... links  to nfcPal=... 
dumps a backup to csv file
100 per run to reduce db load
WARNING: even though the script 
checks for that
ONLY run from console via 
	#php -f convert.php
2022 by Yves Jeanrenaud
https://github.com/yjeanrenaud/yj_nfc-tag-shop-partnerlink
*/

if(defined('STDIN') ) {
	echo("Running from CLI, all fine");
}
else {
	echo("Not Running from CLI\This script is solely for you to use via command line, not via browser!");
	die;
}
$sql_username ="";
$sql_password ="";
$sql_hostname ="";
$sql_db_name ="";

$doChange=false; //set to true in order to commit changes to db

error_reporting(E_ALL);

$mysqli = mysqli_connect($sql_hostname, $sql_username, $sql_password); 
if (!$mysqli) {
    echo "Error: connection failed to MySQL server." . PHP_EOL;
    echo "Debug error no: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debug error msg: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: connection to MySQL server established!" . PHP_EOL;
echo "Host infos: " . mysqli_get_host_info($mysqli) . "<br>".PHP_EOL;

$mysqli->query("USE `".$sql_db_name."`;");
print_r($mysqli->error_list); //debug
$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE '%sPartner=pocketPC%'  ORDER BY `meta_id` DESC LIMIT 0,100";  //0-99, next is 100,100 for 100-199

if($doChange == false){
	$csvFilename="yjsqlSIMULATE.csv";
}
else {
	$csvFilename="yjsqlbackup.csv";
	}

$fp = fopen($csvFilename, 'a+') or die($this->error_list);
$fp = fopen($csvFilename, 'a+') or die($this->error_list);

$mysqli->query($sql);

print_r($mysqli->error_list);
echo "<hr>";

if ($result = $mysqli->query($sql) or die ($mysqli->error_list)){
print_r($result);
fwrite($fp,"meta_id;meta_value;meta_value_new\r\n") or die ("error writting!");
	//echo "<pre>";
    while ($row = $result->fetch_row()) {
		print_r($row[0].PHP_EOL);
		
		//print_r($row[3].PHP_EOL);
		$subject=$row[3];

		$regresult = preg_replace('/(sPartner=)/', 'nfcPal=', $subject);
		//echo "#############<br/>".PHP_EOL;
		//print_r("neu: ".$regresult);
		
		$statement = $mysqli->prepare("UPDATE `wp_postmeta` SET `meta_value` = ? WHERE `meta_id` = ?") or die ($statement->error_list);
		if($doChange==true){
			//*
			$statement->bind_param("ss",$regresult, $row[0]) or die ($statement->error_list);
			$statement->execute() or die ($statement->error_list); //*/
		}
	fwrite($fp,$row[0].";".str_replace("\r\n",'\\r\\n',$row[3])."\';".str_replace("\r\n",'\\r\\n',$regresult)."\'\r\n") or die ("error writting!");
    }
	/* free result set */
	$statement->close();
    $result->close();
	fclose($fp);
}

mysqli_close($mysqli);
?>
