<?php 
// 1. หลังจากที่ยูสเซอร์ป้อนข้อมูลหน้าฟอร์มและ submit มา สร้างตัวเลขสุ่มขึ้นมาชุดหนึ่งเพื่อเป็น verify code 

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) { 

    // เชื่อมต่อฐานข้อมูล 
    $hostname_connection = "localhost"; 
    $database_connection = "example"; 
    $username_connection = "root"; 
    $password_connection = ""; 
    
    $connection = mysql_pconnect($hostname_connection, $username_connection, $password_connection)
    or trigger_error(mysql_error(),E_USER_ERROR); 
    
    mysql_query( "SET NAMES UTF8" ) ; 
    
    // สร้างรหัสสุ่ม 
    
    //1.สร้างชุดตัวอักษรตั้งแต่ a-z 
    // $arr_a_z = range( "a" , "z" ) ;
    
    //2.สร้างชุดตัวอักษรตั้งแต่ A-Z 
    // $arr_A_Z = range( "A" , "Z" ) ;
    
    //3.สร้างชุดตัวอักษรตั้งแต่ 0-9 
    $arr_0_9 = range( 0 , 9 ) ; 
    
    //4.เอาชุดตัวอักษรทั้ง 3 มารวมกัน 
    $arr_a_9 = array_merge( $arr_a_z , $arr_A_Z , $arr_0_9 ) ; 
    $str_a_9 = implode( $arr_a_9 ) ; 
    
    //5.ทำการสับเปลี่ยนตำแหน่งตัวอักษร 
    $str_a_9 = str_shuffle( $str_a_9 ) ; 
    
    //6.ตัดเอามาแค่ 10 ตัวอักษร 
    $member_verify_code = substr( $str_a_9 , 0 , 10 ) ; 
    
    // 2. เอา code ที่ได้ เก็บลงไปในฐานข้อมูลพร้อมข้อมูลที่สมัครเป็นสมาชิก 
    $insertSQL = sprintf("INSERT INTO tbl_member (member_name, member_verify_code) 
                                            VALUES (’%s’, ’%s’, ’%s’)", $_POST['member_name'] , 
                                            $_POST['member_email'] , $member_verify_code ); 
    
    mysql_select_db($database_connection, $connection); 
    $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error()); 
    
    // เอา member_id มา 
    $member_id = mysql_insert_id() ; 
    
    // 3. ส่ง verify code ไปทางอีเมล์ 
    $to = "User <{$_POST['member_email']}>" ; 
    $subject = "Verify By Email" ; 
    $headers = "MIME-Version: 1.0\r\n" ; 
    $headers .= "Content-Type: text/html; charset=UTF-8 \r\n" ; 
    $headers .= "From: Admin <admin@select2web.com>\r\n" ; 
    $body = "<a href=http://www.select2web.com/verify_by_email.php?
                     member_id={$member_id}&member_verify_code={$member_verify_code}>
                     คลิกเพื่อยันยัน</a>" ; 
    
    @mail($to, $subject, $body,$headers) ; 

} 
?>