<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\Contacts\Masks;

class FilterMasks{
  private array $Masks;

  public function Add(
    Masks $Mask
  ){
    $this->Masks[] = $Mask->value;
  }

  public function Get(){
    return implode(',', $this->Masks);
  }
}