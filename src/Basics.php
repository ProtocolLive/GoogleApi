<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.01

namespace ProtocolLive\GoogleApi;

abstract class Basics{
  public function __construct(
    protected string|null $DirLogs = null
  ){}

  protected function Log(string $Msg){
    if($this->DirLogs === null):
      error_log($Msg);
    else:
      file_put_contents(
        $this->DirLogs . '/GoogleApi.log',
        $Msg . PHP_EOL,
        FILE_APPEND
      );
    endif;
  }
}