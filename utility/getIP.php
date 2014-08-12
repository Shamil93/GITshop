<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 12/08/14
 * Time: 21:54
 */
/**
 * Получаем IP адрес
 * @return string
 */
function getIP() {
    if( $ip = getenv('http_client_ip') ) return $ip;
    if( $ip = getenv('http_x_forwarded_for') ) {
        if( $ip == "" || $ip == 'unknown' ) {
            $ip = getenv('remote_addr');
        }
        if( count( $ip ) < 4 ) {
            $ip = "127.0.0.1";
        }
        return $ip;
    }
    if( $ip = getenv('remote_addr') ) {
        if( count( $ip ) < 4 ) {
            $ip = "127.0.0.1";
        }
        return $ip;
    }
}
?>