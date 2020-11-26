<?php

namespace App\Service;

use function PHPSTORM_META\type;

class converter
{
    public function base64ToImage($base64_string, $output_file,$dist) {
        print($base64_string);
        list($type, $data) = explode(';',$base64_string);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        var_dump($dist.'/' .$output_file);
        file_put_contents($dist.'/'.$output_file, $data);
        return file_put_contents($dist.'/'.$output_file, $data);
    }
}
