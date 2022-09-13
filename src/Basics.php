<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.01

namespace ProtocolLive\GoogleApi;

abstract class Basics{
  protected string $DirLogs;

  public function __construct(
    string $DirLogs = null
  ){
    if($DirLogs === null):
      $this->DirLogs = dirname(ini_get('error_log'));
    else:
      $this->DirLogs = $DirLogs;
    endif;
  }

  protected function ErrorLog(string $Msg){
    file_put_contents(
      $this->DirLogs . '/GoogleContacts.log',
      $Msg . PHP_EOL,
      FILE_APPEND
    );
  }
}