<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.11.30.00

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\Contacts\Masks\{
  Address,
  Birthdays,
  Date,
  Email,
  Names,
  Phone
};

class Data{
  private array $Fields = [];
  private array $Pointer;

  public function __construct(
    private string|null $ResourceId = null,
    private string|null $Etag = null
  ){
    if($ResourceId === null):
      $this->Pointer = &$this->Fields;
      return;
    endif;
    $this->Fields = [$ResourceId => []];
    $this->Pointer = &$this->Fields[$ResourceId];
    $this->Pointer['etag'] = $Etag;
  }

  public function Add(
    Masks $Mask,
    Names|Email|Address|Birthdays|Phone $Field,
    string $Value
  ):bool{
    if(($Mask === Masks::Names and $Field instanceof Names === false)
    or ($Mask === Masks::Email and $Field instanceof Email === false)
    or ($Mask === Masks::Addresses and $Field instanceof Address === false)
    or ($Mask === Masks::Birthdays and $Field instanceof Birthdays === false)
    or ($Mask === Masks::Phones and $Field instanceof Phone === false)
    ):
      return false;
    endif;
    
    $this->Pointer[$Mask->value][] = [
      $Field->value => $Value
    ];
    return true;
  }

  public function AddBirthday(
    int $Year,
    int $Month,
    int $Day
  ):void{
    $this->Pointer[Masks::Birthdays->value][] = [
      Birthdays::Date->value => [
        Date::Year->value => $Year,
        Date::Month->value => $Month,
        Date::Day->value => $Day
      ]
    ];
  }

  public function Get(
    bool $Array = false
  ):string|array{
    if($Array):
      return $this->Fields;
    else:
      return json_encode($this->Fields);
    endif;
  }
}