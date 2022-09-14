<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.00

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\Contacts\Masks\{
  Address, Birthdays, Date, Email, Names, Phone
};

class Data{
  private array $Fields = [];

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
    
    $this->Fields[$Mask->value] = [[
      $Field->value => $Value
    ]];
    return true;
  }

  public function AddBirthday(
    int $Year,
    int $Month,
    int $Day
  ):void{
    $this->Fields[Masks::Birthdays->value] = [[
      Birthdays::Date->value => [
        Date::Year->value => $Year,
        Date::Month->value => $Month,
        Date::Day->value => $Day
      ]
    ]];
  }

  public function Get():string{
    return json_encode($this->Fields);
  }
}