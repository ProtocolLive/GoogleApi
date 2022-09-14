<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.02

namespace ProtocolLive\GoogleApi;

abstract class Basics{
  public function __construct(
    protected int $Log,
    protected string|null $DirLogs = null
  ){}

  protected function Log(Api $Api, string $Method, Logs $Type, string $Msg):void{
    if(($Type->value & $this->Log) === 0):
      return;
    endif;
    $Msg = $Method . ' - ' . $Type->name . PHP_EOL . $Msg;
    if($this->DirLogs === null):
      error_log('GoogleApi - ' . $Api->name . ' - '. $Msg);
    else:
      file_put_contents(
        $this->DirLogs . '/GoogleApi' . $Api->name . '.log',
        $Msg . PHP_EOL,
        FILE_APPEND
      );
    endif;
  }
}