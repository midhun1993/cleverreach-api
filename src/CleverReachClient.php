<?php
namespace Midhun\CleverReach;

class CleverReachClient
{
    const VERSION_TWO_URL = "https://rest.cleverreach.com/v2";
    const VERSION_THREE_URL = "https://rest.cleverreach.com/v3";

    protected $token;
    protected $client_id;
    protected $login;
    protected $password;

    protected $base_url;

    public static function create($credentials = [])
    {
        $client = new self();
        $client->client_id = $credentials['client_id'];
        $client->login = $credentials['login'];
        $client->password = $credentials['password'];
        return $client;
    }

    public function __construct()
    {
        $this->v2Auth();
    }

    public function v2Auth()
    {
        $this->base_url = self::VERSION_TWO_URL;
    }

    public function v3Auth()
    {
        $this->base_url = self::VERSION_THREE_URL;
    }

    protected function request($path, $parameters = [], $type = 'GET')
    {
        $curl_url = $this->base_url.$path;

        if ($this->token != '') {
            $curl_url =$curl_url."?token=".$this->token;
        }
        $have_parameters = sizeof($parameters) > 0;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $header = ["Content-Type: application/json; charset=utf-8"];


        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        if ($have_parameters) {
            $curl_data = json_encode($parameters);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
        }

        $info = curl_getinfo($ch);
        $response =  curl_exec($ch);
        if ($response === false) {
            die("Error :".curl_error($ch).", Error Code : ".curl_error($ch));
        }
        curl_close($ch);
        return json_decode($response);
    }

    public function get()
    {
        return $this->request($path, $parameters);
    }

    public function post($path, $parameters)
    {
        return $this->request($path, $parameters, 'POST');
    }

    public function put()
    {
        /*
        TODO : need to check the documentation;
        */
    }

    public function authenticate()
    {
        $token = $this->getToken();
        $this->setToken($token);
    }

    public function getToken()
    {
        $parameters = ["client_id" => $this->client_id, "login" => $this->login, "password" => $this->password];
        $token_response = $this->post("/login", $parameters);
        if ($token_response instanceof \stdClass) {
            $response = json_decode($token_response);
            if ($response->error) {
                die("Error : " .$response->error->message." Error Code : ".$response->error->code);
            }
        }
        return $token_response;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function formatRequestData($parameters = [])
    {
        $string_data = [];
        foreach ($parameters as $key => $value) {
            $string_data[] = $key.'='.$value;
        }
        return implode('&', $string_data);
    }

    public function addReceiver($list_id, $data)
    {
        $path = '/groups.json/'.$list_id.'/receivers';
        $response = $this->post($path, ['postdata'=>$data]);
        return $response;
    }
}
