<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.20.01

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\Contacts\Masks;

class FilterMasks{
  private array $Masks;

  public function __construct(
    Masks $Mask = null
  ){
    if($Mask !== null):
      $this->Add($Mask);
    endif;
  }

  public function Add(
    Masks $Mask
  ){
    $this->Masks[] = $Mask->value;
  }

  public function Get(){
    return implode(',', $this->Masks);
  }
}