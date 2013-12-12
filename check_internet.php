<?php
ini_set('max_execution_time', 0); // seconds
ini_set('output_buffering', 'on');
ini_set('zlib.output_compression', 0);

error_reporting(E_ALL);
ini_set('display_errors', true);

header( 'Content-type: text/html; charset=utf-8' );
header("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header("Expires: Mon, 24 Sep 2012 04:00:00 GMT");

function is_connected() {
    $connected = @fsockopen("www.google.com", 80, $errno, $errstr, 30);
    // $connected = @fsockopen("www.google.com", [80|443]);
    if ($connected){
        // $out = "GET / HTTP/1.1\r\n";
        // $out .= "Host: www.google.com\r\n";
        // $out .= "Connection: Close\r\n\r\n";
        // fwrite($fp, $out);
        // while (!feof($fp)) {
        //     echo fgets($fp, 128);
        // }
        $is_conn = true;
        fclose($connected);
    }else{
        // echo "$errstr ($errno)<br />\n";
        $is_conn = false;
    }
    return $is_conn;
}

$boolarray = array(false => 'false', true => 'true');
// echo '<table border="1">'; // use when browser mode

// ob_implicit_flush();
// ob_start();
// if (ob_get_level() == 0) ob_start();
// if (ob_get_level() > 0) ob_end_clean();
$date = date("Y-m-d");
$i = 1;
while (true) {
    $is_connected = is_connected();
    $micro_date = microtime();
    $date_array = explode(" ",$micro_date);
    // $date = date("Y-m-d H:i:s",$date_array[1]);
    if ($date != date("Y-m-d")) {
        $date = date("Y-m-d");
    }
    $logs_file = $date . '.txt';
    // Open the file to get existing content
    $current = @file_get_contents($logs_file);
    if (!file_exists($logs_file)) {
        $current .= "###############################################################\r\n";
        $current .= "Logs date:".date("Y-m-d")."\r\n";
        // $current .= "date time|processid|appid|app_trans_id|action|level|message\r\n";
        $current .= "ID | DateTime | Status(boolean)\r\n";
        $current .= "###############################################################\r\n";
    }
    // Append a new person to the file
    $current .= $i . ' | ' . date("Y-m-d H:i:s") . ' ' . $date_array[0] . ' | ' . $boolarray[$is_connected];
    $current .= "\r\n";
    // Write the contents back to the file
    file_put_contents($logs_file, $current);

    // use when browser mode
    /*echo '<tr>';
    echo '<td>' . $i . '</td>';
    echo '<td>' . date("Y-m-d H:i:s") . ' ' . $date_array[0] . '</td>';
    echo '<td>' . $boolarray[$is_connected] . '</td>';
    echo '</tr>';
    ob_flush();
    flush();*/
    // ob_end_flush();
    // ob_flush();

    // Command line
    echo $i . ' | ' . date("Y-m-d H:i:s") . ' ' . $date_array[0] . ' | ' . $boolarray[$is_connected] . "\r\n";

    sleep(1);
    $i++;
}

// echo '</table>'; // use when browser mode
// ob_end_flush(); // use when browser mode

?>