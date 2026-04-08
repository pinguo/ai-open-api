<?php
class SignatureBuilder
{
    protected $ak = "";
    protected $sk = "";
    protected $expiredIn = 0;

    public function __construct($ak, $sk, $expiredIn)
    {
        $this->ak = $ak;
        $this->sk = $sk;
        $this->expiredIn = intval($expiredIn);
    }

    public function sign($path, $getParams = [], $postParams = [], $body = "",$ts = "")
    {
        $params = empty($getParams) ? [] : $getParams;
        if (!empty($postParams)) {
            foreach ($postParams as $key => $value) {
                $params[$key] = $value;
            }
        }
        $finalText = $path . $this->buildFinalText($params, $body);
        if(empty($ts)){
            $ts = time();
        }
        $finalText .= $ts . $this->sk;
        return [
            "finalText" => $finalText,
            "timestamp" => $ts,
            "sign" => hash('sha256', $finalText),
        ];
    }

    protected function buildFinalText($params, $body)
    {
        ksort($params);
        $text = "";
        foreach ($params as $key => $value) {
            $text .= "$key=$value";
        }
        $text .= $body;
        return $text;
    }
}