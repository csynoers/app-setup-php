<?php
    /**
     * $whitelist adalah daftar ip localhost jika ip berbeda silahkan tambahkan lagi
    */
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    /**
     * FILTER :
     * jika run aplikasi di localhost $status_server = offline
     * jika run aplikasi di cpanel $status_server = online
     */
    $status_server = 'offline';
    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        $status_server = 'online';
    }

    /**
     * Membuat username database :
     * jika $
     */
    
    echo '<pre>';
    print_r($status_server);
    echo '</pre>';
    // define();
