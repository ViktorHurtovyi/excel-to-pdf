<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class UserFileService
{

    public function userFileToArray($userData)
    {
        $filesData = $this->getData($userData);
        $result = $this->getResult($filesData);
        return $result;
    }

    private function getPath($userData)
    {
        return storage_path('app/'). Storage::putFile('files', $userData->file('file'));
    }

    private function getData($userData)
    {
        return (new FastExcel)->import(self::getPath($userData))->where('Filter:Anlass', '!=', '');
    }

    private function getResult($filesData){
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
