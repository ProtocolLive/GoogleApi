<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi

namespace ProtocolLive\GoogleApi\Contacts;
use Exception;
use ProtocolLive\GoogleApi\{
  Api,
  Basics,
  Logs
};

/**
 * @version 2024.02.06.00
 */
class Contacts
extends Basics{
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
   * @throws Exception
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
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url . PHP_EOL . json_encode($Data->Get(true), JSON_PRETTY_PRINT));
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($code >= 400){
      throw new Exception($return, $code);
    }
    return $return;
  }

  /**
   * Limited by 200 persons per call
   * @param Data[] $Persons
   * @link https://developers.google.com/people/api/rest/v1/people/batchUpdateContacts
   */
  public function Edit(
    array $Persons,
    FilterMasks $FieldsUpdate,
    FilterMasks $FieldsReturn = null
  ):string|null{
    $get['updateMask'] = $FieldsUpdate->Get();
    if($FieldsReturn !== null):
      $get['readMask'] = $FieldsReturn->Get();
    endif;
    $post['contacts'] = [];
    foreach($Persons as $person):
      $post['contacts'] = array_merge($post['contacts'], $person->Get(true));
    endforeach;
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
    $this->Log(
      Api::Contacts,
      __METHOD__,
      Logs::Send,
      json_encode($post, JSON_PRETTY_PRINT)
    );
    $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
    if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 400):
      return null;
    endif;
    return $return;
  }

  /**
   * Limited by 200 persons per call
   * @param string[] $ResourceIds
   */
  public function EtagGet(
    array $ResourceIds
  ):array|null{
    $masks = new FilterMasks;
    $masks->Add(Masks::Names);
    $return = $this->Get($ResourceIds, $masks);
    foreach($return['responses'] as $contact):
      if(isset($contact['person']) === false):
        $this->Log(
          Api::Contacts,
          __METHOD__,
          Logs::Response,
          json_encode($contact, JSON_PRETTY_PRINT)
        );
        throw new Exception(json_encode($contact));
      endif;
      $return[$contact['person']['resourceName']] = $contact['person']['etag'];
    endforeach;
    unset($return['responses']);
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

  /**
   * Limited by 200 persons per call
   * @throws Exception
   * @link https://developers.google.com/people/api/rest/v1/people/getBatchGet
   */
  public function Get(
    array $Ids,
    FilterMasks $Masks
  ):array{
    if(count($Ids) > 185):
      throw new Exception('Too many IDs');
    endif;
    $get['personFields'] = $Masks->Get();
    $get = http_build_query($get);
    foreach($Ids as $id):
      $get .= '&resourceNames=people/' . $id;
    endforeach;
    $url = self::Url . '/people:batchGet?' . $get;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    $return = curl_exec($curl);
    $this->Log(Api::Contacts, __METHOD__, Logs::Send, $url);
    if($return === false):
      $this->Log(Api::Contacts, __METHOD__, Logs::Response, curl_error($curl));
      throw new Exception(curl_error($curl));
    elseif(curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200):
      $this->Log(Api::Contacts, __METHOD__, Logs::Response, curl_error($curl));
      throw new Exception($return);
    else:
      $this->Log(Api::Contacts, __METHOD__, Logs::Response, $return);
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
}