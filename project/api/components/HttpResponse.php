<?php

class HttpResponse {
    public $code;
    public $msg;
    public $data;

    /**
     * @param int $code response status code
     * @param string $msg response message describing the code
     */
    public function __construct($code, $msg) {
        $this->code = $code;
        $this->msg = $msg;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void {
        $this->code = $code;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void {
        $this->msg = $msg;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): void {
        $this->data = $data;
    }

    public function getArr(): array {
        $arr =  [
            "code"=>$this->code,
            "msg"=>$this->msg,
        ];

        if (isset($this->data)) {
            $arr["data"] = $this->data;
        };

        return $arr;
    }

    public function sendResponse(): void {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($this->code);
        echo json_encode($this->getArr());
        die();
    }


}
