<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $dataDirectory = null;

    public function __construct()
    {
        $this->dataDirectory = storage_path('data');
    }

    protected function get($table = '') {
      $dataFile = $this->checkFileExist($table);
      if(!$dataFile) {
        return [];
      }
      return json_decode(file_get_contents($dataFile), true);
    }

    protected function getRecordById($id, $table) {
      $dataFile = $this->checkFileExist($table);
      if(!$dataFile) {
        return [];
      }
      $data = json_decode(file_get_contents($dataFile), true);
      return !empty($data[$id]) ? $data[$id] : [];
    }

    protected function getMaxId($table) {
      $list = $this->get($table);
      if(!is_array($list) || empty($list)) {
        return 0;
      }
      return max(array_keys($list));
    }

    protected function insert($data, $table) {
      $dataFile = $this->checkFileExist($table);
      if(!$dataFile) {
        return [];
      }

      if(empty($data)) {
        return [];
      }

      $dataId = array_keys($data)[0];

      $oldData = $this->get($table);

      if(is_array($oldData)) {
        $data = $oldData + $data;
      }

      if($this->writeData($data, $dataFile)) {
        return ["id" => $dataId];
      }
      return [];
    }

    protected function update($id, $data, $table) {
      $dataFile = $this->checkFileExist($table);
      if(!$dataFile) {
        return [];
      }

      if(empty($data)) {
        return [];
      }

      $oldData = $this->get($table);

      if(empty($oldData[$id])) {
        return [];
      }

      $newData = array_replace($oldData[$id], $data);
      $oldData[$id] = $newData;

      if($this->writeData($oldData, $dataFile)) {
        return ["id" => $id];
      }
      return [];
    }

    protected function delete($id, $table) {
      $dataFile = $this->checkFileExist($table);
      if(!$dataFile) {
        return [];
      }

      $data = $this->get($table);

      if(empty($data[$id])) {
        return [];
      }
      unset($data[$id]);

      if($this->writeData($data, $dataFile)) {
        return ["id" => $id];
      }
      return [];
    }

    protected function notify($msg, $statusCode, $type = "error") {
      $notification[$type] = [
        "statusCode" => $statusCode,
        "name" => $type,
        "message" => $msg
      ];
      return json_encode($notification);
    }

    private function writeData($data, $table) {
      $data = json_encode($data,
        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);

      return file_put_contents($table, $data);
    }

    private function checkFileExist($table) {
      $dataFile = $this->dataDirectory.'/'.$table.'.json';
      return is_file($dataFile) ? $dataFile : '';
    }
}
