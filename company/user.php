<?php
require 'restful_api.php';

class api extends restful_api {

    function __construct(){
        parent::__construct();
    }

    function users(){
        if ($this->method == 'GET'){
            // Hãy viết code xử lý LẤY dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)
            
            $fread = file_get_contents("database.json");
            $fdata = json_decode($fread, true);

            $body = array();
            $body["status"] = "success";
            $body["message"] = "Lấy danh sách user thành công!";
            $body["code"] = "200";
            $body["data"] = $fdata["users"];

            $this->response(200, $body);
        }
        elseif ($this->method == 'POST'){
            // Hãy viết code xử lý THÊM dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)

            $fread = file_get_contents("database.json");
            $fdata = json_decode($fread, true);
            $fdata["users"][] = $this->params;

            $fp = fopen('database.json', 'w');
            fwrite($fp, json_encode($fdata));
            fclose($fp);

            
            $body = array();
            $body["status"] = "success";
            $body["message"] = "Thêm user thành công!";
            $body["code"] = "200";
            $body["data"] = array();
            $data[] = $this->response(200, $body);
        }
        elseif ($this->method == 'PUT'){
            // Hãy viết code xử lý CẬP NHẬT dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)
        }
        elseif ($this->method == 'DELETE'){
            // Hãy viết code xử lý XÓA dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)
        }
    }
}

$user_api = new api();
?>