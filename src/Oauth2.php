<?php
//2022.09.13.00

namespace ProtocolLive\GoogleApi;

/**
 * @return string The refresh token
 */
class Oauth2{
  private string $DirLog;

  public function __construct(
    string $DirLog = null
  ){
    if($DirLog === null):
      $this->DirLog = dirname(ini_get('error_log'));
    else:
      $this->DirLog = $DirLog;
    endif;
  }

  protected function ErrorLog(string $Msg){
    file_put_contents(
      $this->DirLog . '/GoogleContacts.log',
      $Msg . PHP_EOL,
      FILE_APPEND
    );
  }

  public static function CredentialsGet(
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
    $curl = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $return = curl_exec($curl);
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