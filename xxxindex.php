<?php
    function collect_file($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return($result);
    }

    function multiple_download(array $urls, $save_path = '/tmp')
    {
        $multi_handle = curl_multi_init();
        $file_pointers = [];
        $curl_handles = [];

        // Add curl multi handles, one per file we don't already have
        foreach ($urls as $key => $url) {
            $file = $save_path . '/' . basename($url);
            if(!is_file($file)) {
                $curl_handles[$key] = curl_init("http://anakhebatindonesia.com/get.php?a=download&file=".$url);
                $file_pointers[$key] = fopen($file, "w+");
                curl_setopt($curl_handles[$key], CURLOPT_FILE, $file_pointers[$key]);
                curl_setopt($curl_handles[$key], CURLOPT_HEADER, 0);
                curl_setopt($curl_handles[$key], CURLOPT_CONNECTTIMEOUT, 60);
                curl_multi_add_handle($multi_handle,$curl_handles[$key]);
            }
        }

        // Download the files
        do {
            curl_multi_exec($multi_handle,$running);
        } while ($running > 0);

        // Free up objects
        foreach ($urls as $key => $url) {
            curl_multi_remove_handle($multi_handle, $curl_handles[$key]);
            curl_close($curl_handles[$key]);
            fclose ($file_pointers[$key]);
        }
        curl_multi_close($multi_handle);
    }


    
    // start loop here
    $url = "http://anakhebatindonesia.com/get.php?a=temp&dir=josys";
    
    $temp_file_contents     = json_decode(collect_file($url));
    multiple_download($temp_file_contents);

    /* foreach ($temp_file_contents as $key => $value) {
        // create directory
        $dirname = dirname($value);
        if (!is_dir($dirname))
            mkdir($dirname, 0755, true);

        $ch = curl_init("http://anakhebatindonesia.com/get.php?a=download&file={$value}");
        if($ch === false)
            echo ($value.' => Failed to create curl handle');

        $fp = fopen($value, 'w+');
        // curl_setopt($ch, CURLOPT_URL, "http://anakhebatindonesia.com/get.php?a=download&file={$value}");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    } */

    // end loop here
?>