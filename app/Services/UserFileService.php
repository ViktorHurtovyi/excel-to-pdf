<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class UserFileService
{

    public static function userFileToArray($userData)
    {
        $filesData = self::getData($userData);
        $result = self::getResult($filesData);
        return $result;
    }

    private static function getPath($userData)
    {
        return storage_path('app/'). Storage::putFile('files', $userData->file('file'));
    }

    private static function getData($userData)
    {
        return (new FastExcel)->import(self::getPath($userData))->where('Filter:Anlass', '!=', '');
    }

    private static function getResult($filesData){
        $result = [];
        foreach ($filesData as $data)
            $result = array_merge_recursive ($result, $data);

        foreach ($result as $k=>$v){
            if(strpos($k, 'Filter:') === false || $v[0]===''){
                unset($result[$k]);
            }
        }
        return $result;
    }

}
