<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.01

namespace ProtocolLive\GoogleApi;

/**
 * @return string The refresh token
 */
class Oauth2 extends Basics{
  public function __construct(
    int $Log,
    string $DirLogs = null
  ){
    parent::__construct($Log, $DirLogs);
  }

  public function CredentialsGet(
    string $ApiId,
    string $ApiSecret,
    string $Token,
    string $Redirect = null,
    bool $Refresh = false
  ):string|bool{
    $post = [
      'client_id' => $ApiId,
      'client_secret' => $ApiSecret,
    ];
    if($Refresh):
      $post['grant_type'] = 'refresh_token';
      $post['refresh_token'] = $Token;
    else:
      $post['redirect_uri'] = $Redirect;
      $post['grant_type'] = 'authorization_code';
      $post['code'] = $Token;
    endif;
    $url = 'https://oauth2.googleapis.com/token';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $return = curl_exec($curl);
    $this->Log(Api::Oauth, __METHOD__, Logs::Send, $url . PHP_EOL . json_encode($post, JSON_PRETTY_PRINT));
    $this->Log(Api::Oauth, __METHOD__, Logs::Response, json_encode($return, JSON_PRETTY_PRINT));
    if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200):
      $return = json_decode($return, true);
      $_SESSION['Oauth2']['Token'] = $return['access_token'];
      $_SESSION['Oauth2']['Expires'] = strtotime('+' . $return['expires_in'] . ' seconds');
      return $return['refresh_token'] ?? true;
    else:
      echo $return;
      return false;
    endif;
  }
}