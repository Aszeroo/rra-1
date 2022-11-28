<?php 
// 1. เชื่อมต่อฐานข้อมูล 
$hostname_connection = "localhost"; 
$database_connection = "example"; 
$username_connection = "root"; 
$password_connection = ""; 

$connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection) 
or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query( "SET NAMES UTF8" ) ; 

// 2. ตรวจสอบก่อนว่า verify code ที่ส่งมา ถูกต้อง 
mysql_select_db($database_connection, $connection); 
$query_rs_member = sprintf("SELECT * FROM tbl_member WHERE member_id = '%s'  
                           AND member_verify_code = '%s' ", $_GET['member_id'] , $_GET['member_verify_code'] ); 

$rs_member = mysql_query($query_rs_member, $connection) or die(mysql_error()); 
$row_rs_member = mysql_fetch_assoc($rs_member); 
$totalRows_rs_member = mysql_num_rows($rs_member); 

// กรณีที่ถูกต้อง ก็อัปเดต สถานะในฐานข้อมูล 
if ( $totalRows_rs_member > 0 ) { 

    $updateSQL = sprintf("UPDATE tbl_member SET member_verify_status= 1 WHERE member_id=%s", $_GET['member_id'] ); 
    mysql_select_db($database_connection, $connection); 
    $Result1 = mysql_query($updateSQL, $connection) or die(mysql_error()); 
    
    echo "Verify Complete" ; 

} else { 

    // ในกรณีที่มั่วข้อมูลอะไรก็ไม่รู้มา 
    echo "Verify Uncomplete " ; 

} 
?>