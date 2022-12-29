<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.12.29.01

namespace ProtocolLive\GoogleApi;
use Exception;

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

  /**
   * @return array Array order: Token, expires, refresh token (if first auth)
   * @throws Exception Throws HTTP code error
   */
  public function CredentialsGet(
    string $ApiId,
    string $ApiSecret,
    string $Token,
    string $Redirect = null,
    bool $Refresh = false
  ):array{
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
    $this->Log(
      Api::Oauth,
      __METHOD__,
      Logs::Send,
      $url . PHP_EOL . json_encode($post, JSON_PRETTY_PRINT)
    );
    $return = json_decode($return, true);
    $this->Log(
      Api::Oauth,
      __METHOD__,
      Logs::Response,
      json_encode($return, JSON_PRETTY_PRINT)
    );
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($code !== 200):
      throw new Exception(
        $return['error'] . ' ' . $return['error_description'],
        $code
      );
    endif;
    return [
      $return['access_token'],
      $return['expires_in'],
      $return['refresh_token'] ?? null,
    ];
  }
}