<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.01

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\{
  Api, Basics, Logs
};

class Contacts extends Basics{
  private const Url = 'https://people.googleapis.com/v1';
  private string $Token;
  public string|null $Error = null;

  public function __construct(
    string $Token,
    int $Log,
    string $DirLogs = null
  ){
    parent::__construct($Log, $DirLogs);
    $this->Token = $Token;
  }

  /**
   * @link https://developers.google.com/people/api/rest/v1/people/createContact
   */
  public function Create(
    Data $Data,
    string $Return = null
  ):string|false{
    if($Return !== null):
      $get['personFields'] = $Return;
    endif;
    $url = self::Url . '/people:createContact';
    if(isset($get)):
      $url .= '?' . http_build_query($get);
    endif;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $Data->Get());
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url . PHP_EOL . json_encode($Data, JSON_PRETTY_PRINT));
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, json_encode($return, JSON_PRETTY_PRINT));
    return $return;
  }

  /**
   * https://developers.google.com/people/api/rest/v1/people/searchContacts
   */
  public function Find(
    string $Text,
    FilterMasks $Masks,
    int $Count = 10
  ):array|null{
    $get['query'] = $Text;
    $get['pageSize'] = $Count;
    $get['readMask'] = $Masks->Get();
    $url = self::Url . '/people:searchContacts?' . http_build_query($get);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url);
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($return === false
    or $return === '{}' . PHP_EOL
    or $code === 503):
      return null;
    else:
      return json_decode($return, true);
    endif;
  }

  public function List(
    FilterMasks $Masks
  ):array|null{
    $get['personFields'] = $Masks->Get();
    $get['pageSize'] = 1000;
    $curl = curl_init(self::Url . '/people/me/connections?' . http_build_query($get));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($curl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    if($return === false):
      return null;
    else:
      return json_decode($return, true);
    endif;
  }

  /**
   * @link https://developers.google.com/people/api/rest/v1/people/batchUpdateContacts
   */
  public function Edit(
    string $ResourceId,
    Data $Data,
    FilterMasks $FieldsUpdate,
    FilterMasks $FieldsReturn = null
  ):string|null{
    $get['updateMask'] = $FieldsUpdate->Get();
    $post['contacts'][$ResourceId] = $Data->Get();
    if($FieldsReturn !== null):
      $get['readMask'] = $FieldsReturn->Get();
    endif;
    $url = self::Url . '/people:batchUpdateContacts?' . http_build_query($get);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url);
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
    if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 400):
      return null;
    endif;
    return $return;
  }

  public function Get(
    string $Id,
    FilterMasks $Masks
  ){
    $get['personFields'] = $Masks->Get();
    $url = self::Url . '/people/' . $Id . '?' . http_build_query($get);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url);
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
    if($return === false):
      return null;
    else:
      return json_decode($return, true);
    endif;
  }
}