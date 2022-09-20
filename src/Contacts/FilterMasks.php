<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.20.00

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

  public function Get(
    bool $Array = false
  ){
    if($Array):
      return $this->Masks;
    else:
      return implode(',', $this->Masks);
    endif;
  }
}