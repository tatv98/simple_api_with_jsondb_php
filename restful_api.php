<?php
 class restful_api {
    protected $method   = '';
    protected $endpoint = '';
    protected $params   = array();


    public function __construct(){
        $this->_input();
        $this->_process_api();
    }

    private function _input(){
        // code của hàm _input
        $this->params = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        $this->endpoint = array_shift($this->params);

        // Lấy method của request
        $method         = $_SERVER['REQUEST_METHOD'];
        $allow_method   = array('GET', 'POST', 'DELETE');

        if (in_array($method, $allow_method)){
            $this->method = $method;
        }

        // Nhận thêm dữ liệu tương ứng theo từng loại method
        switch ($this->method) {
            case 'POST':
                if($_POST != null){
                    $this->params = $_POST;
                }else{
                    $this->params = json_decode(file_get_contents('php://input'), true);
                }
            break;

            case 'GET':
                $this->params = $_GET;
            break;
            case 'DELETE':
                $this->params = $_GET;
            break;

            default:
                $this->response(500, "Invalid Method");
            break;
        }
    }

    private function _process_api(){
        // code của hàm _process_api
        if (method_exists($this, $this->endpoint)){
            $this->{$this->endpoint}();
        }
        else {
            $this->response(500, "Unknown endpoint");
        }
    }

    protected function response($status_code, $data = NULL){
        header($this->_build_http_header_string($status_code));
        header("Content-Type: application/json");
        echo json_encode($data);
        die();
    }
    
    private function _build_http_header_string($status_code){
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );
        return "HTTP/1.1 " . $status_code . " " . $status[$status_code];
    }
}
 
 ?>