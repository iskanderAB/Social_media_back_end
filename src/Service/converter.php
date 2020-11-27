<?php

namespace App\Service;

use function PHPSTORM_META\type;

class converter
{
    public function base64ToImage($base64_string, $output_file,$dist) {
        $ext='.jpg';
        $fileName='file'.uniqid().$ext;
        $base64=str_replace('data:image/png;base64,', '', $base64_string);
        $file=fopen($dist.'/'.$fileName, 'wb');
        fwrite($file, base64_decode($base64));
        fclose($file);
        //var_dump( 'iskander file =>  ' . $fileName);
        return $fileName ; 
    }


    public function base64ToPDF($base64_string, $output_file,$dist){
        $ext='.pdf';
        $fileName='PDF'.uniqid().$ext;
        $base64=str_replace('data:application/pdf;base64,', '', $base64_string);
        $file=fopen($dist.'/'.$fileName, 'wb');
        fwrite($file, base64_decode($base64));
        fclose($file);
        //var_dump( 'iskander file =>  ' . $fileName);
        return $fileName ; 
    }
}
