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

function is_connected($domain = array(), $timeout = 30) {
    $random_keys_domain = array_rand($domain, 1);
    $domain_result = $domain[$random_keys_domain]['domain'];
    $port = $domain[$random_keys_domain]['port'];

    $random_keys_port = array_rand($port, 1);
    $port_result = $domain[$random_keys_domain]['port'][$random_keys_port];

    $connected = @fsockopen($domain_result, $port_result, $errno, $errstr, $timeout);
    // $connected = @fsockopen("www.google.com", [80|443]);

    $response = array();
    if ($connected){
        // $out = "GET / HTTP/1.1\r\n";
        // $out .= "Host: www.google.com\r\n";
        // $out .= "Connection: Close\r\n\r\n";
        // fwrite($fp, $out);
        // while (!feof($fp)) {
        //     echo fgets($fp, 128);
        // }
        $response['code'] = array(
            'status' => 200,
            'message' => 'OK'
            );
        $response['data'] = array(
            'status' => true,
            'domain' => $domain_result,
            'port' => $port_result
            );
        fclose($connected);
    }else{
        // echo "$errstr ($errno)<br />\n";
        $response['code'] = array(
            'status' => 404,
            'message' => 'Lose'
            );
        $response['data'] = array(
            'status' => false,
            'domain' => $domain_result,
            'port' => $port_result
            );
    }

    return $response;
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
    $is_connected = is_connected(array(
        array(
            'domain' => 'www.google.com',
            'port' => array(80, 443)
            ),
        array(
            'domain' => 'www.facebook.com',
            'port' => array(80, 443)
            ),
        array(
            'domain' => 'www.twitter.com',
            'port' => array(80, 443)
            ),
        array(
            'domain' => 'www.sanook.com',
            'port' => array(80)
            ),
        array(
            'domain' => 'www.kapook.com',
            'port' => array(80)
            )
        ), 10);
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
        $current .= "Logs date: ".date("c")."\r\n";
        // $current .= "date time|processid|appid|app_trans_id|action|level|message\r\n";
        $current .= "ID - - [DateTime] | Status(boolean)\r\n";
        $current .= "###############################################################\r\n";
    }
    // Append a new person to the file
    $current .= '::' . $i . ' - - [' . date("Y-m-d H:i:s P e") . ' ' . $date_array[0] . '] ' . $boolarray[$is_connected['data']['status']] . ' ' . $is_connected['data']['domain'] . ':' . $is_connected['data']['port'];
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
    echo '::' . $i . ' - - [' . date("Y-m-d H:i:s P e") . ' ' . $date_array[0] . '] ' . $boolarray[$is_connected['data']['status']] . ' ' . $is_connected['data']['domain'] . ':' . $is_connected['data']['port'] . "\r\n";

    sleep(1);
    $i++;
}

// echo '</table>'; // use when browser mode
// ob_end_flush(); // use when browser mode

?>