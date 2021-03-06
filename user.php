<?php
require 'restful_api.php';

class api extends restful_api
{

    function __construct()
    {
        parent::__construct();
    }

    function users()
    {
        if ($this->method == 'GET') {
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
        } elseif ($this->method == 'POST') {
            // Hãy viết code xử lý THÊM dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)
            $body = array();
            if ($this->params["id"] == null || $this->params["id"] == "") {
                $body["status"] = "failure";
                $body["message"] = "Vui lòng nhập id!";
                $body["code"] = "402";
            } else if ($this->params["name"] == null || $this->params["name"] == "") {
                $body["status"] = "failure";
                $body["message"] = "Vui lòng nhập tên!";
                $body["code"] = "403";
            } else {
                $is_match = false;
                $fread = file_get_contents("database.json");
                $fdata = json_decode($fread, true);

                for ($i = 0; $i < sizeof($fdata["users"]); $i++) {
                    if ((int)$this->params["id"] == $fdata["users"][$i]["id"]) {
                        $is_match = true;
                        break;
                    }
                }
                if ($is_match) {
                    $body["status"] = "failure";
                    $body["message"] = "Id đã tồn tại!";
                    $body["code"] = "401";
                } else {
                    $user = array();
                    $user["id"] = (int)$this->params["id"];
                    $user["name"] = $this->params["name"];

                    $fdata["users"][] = $user;
                    $fp = fopen('database.json', 'w');
                    fwrite($fp, json_encode($fdata));
                    fclose($fp);


                    $body["status"] = "success";
                    $body["message"] = "Thêm user thành công!";
                    $body["code"] = "200";
                }
            }

            $body["data"] = array();
            $this->response(200, $body);
        } elseif ($this->method == 'DELETE') {
            // Hãy viết code xử lý XÓA dữ liệu ở đây
            // trả về dữ liệu bằng cách gọi: $this->response(200, $data)
            if ($this->params["id"] == null || $this->params["id"] == "") {
                $body["status"] = "failure";
                $body["message"] = "Vui lòng nhập id!";
                $body["code"] = "402";
            } else {
                $is_match = false;
                $positon = -1;

                $fread = file_get_contents("database.json");
                $fdata = json_decode($fread, true);

                for ($i = 0; $i < sizeof($fdata["users"]); $i++) {
                    if ($this->params["id"] == $fdata["users"][$i]["id"]) {
                        $is_match = true;
                        $positon = $i;
                        break;
                    }
                }
                if ($is_match) {
                    unset($fdata["users"][$positon]);
                    $fp = fopen('database.json', 'w');
                    fwrite($fp, json_encode($fdata));
                    fclose($fp);

                    $body["status"] = "success";
                    $body["message"] = "Xóa user thành công!";
                    $body["code"] = "200";
                } else {
                    $body["status"] = "failure";
                    $body["message"] = "User không tồn tại!";
                    $body["code"] = "390";
                }
            }
            $body["data"] = array();
            $this->response(200, $body);
        }
    }
}

$user_api = new api();
