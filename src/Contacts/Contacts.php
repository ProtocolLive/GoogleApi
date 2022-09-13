<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts;
use ProtocolLive\GoogleApi\Oauth2;

class Contacts extends Oauth2{
  private const Url = 'https://people.googleapis.com/v1';
  private string $Token;
  public string|null $Error = null;

  public function __construct(
    string $Token
  ){
    $this->Token = $Token;
  }

  /**
   * @param array $Return Can be addresses, biographies, birthdays, braggingRights, calendarUrls, clientData, emailAddresses, etag, events, externalIds, fileAses, genders, imClients, interests, locales, locations, memberships, metadata, miscKeywords, names, nicknames, occupations, organizations, phoneNumbers, relations, residences, resourceName, sipAddresses, skills, urls, userDefined
   */
  public function Create(
    array $Data,
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
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($Data));
    $this->ErrorLog('Contact - Create - Send: ' . PHP_EOL . json_encode($Data, JSON_PRETTY_PRINT));
    $return = curl_exec($curl);
    $this->ErrorLog('Contact - Create - Return: ' . PHP_EOL . $return);
    return $return;
  }

  public function Find(
    string $Text,
    FilterMasks $Masks,
    int $Count = 10
  ):array|null{
    $get['query'] = $Text;
    $get['pageSize'] = $Count;
    $get['readMask'] = $Masks->Get();
    $get['access_token'] = $this->Token;
    $curl = curl_init(self::Url . '/people:searchContacts?' . http_build_query($get));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($return === false
    or $return === '{}' . PHP_EOL):
      $this->ErrorLog('Contact - Find - Return: null');
      return null;
    else:
      $this->ErrorLog('Contact - Find - Return:' . PHP_EOL . $return);
      if($code === 503):
        return null;
      else:
        return json_decode($return, true);
      endif;
    endif;
  }

  public function List(
    FilterMasks $Masks
  ):array|null{
    $get['access_token'] = $this->Token;
    $get['personFields'] = $Masks->Get();
    $get['pageSize'] = 1000;
    $curl = curl_init(self::Url . '/people/me/connections?' . http_build_query($get));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($curl);
    if($return === false):
      return null;
    else:
      return json_decode($return, true);
    endif;
  }

  public function Edit(
    array $Values,
    string $FieldsUpdate,
    string $FieldsReturn = null
  ):string|null{
    $get['updateMask'] = $FieldsUpdate;
    $get['readMask'] = $FieldsReturn;
    $post['contacts'] = $Values;
    $curl = curl_init(self::Url . '/people:batchUpdateContacts?' . http_build_query($get));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json',
      'Authorization: Bearer ' . $this->Token
    ]);
    $return = curl_exec($curl);
    $this->ErrorLog('Contact - Edit - Return:' . PHP_EOL . $return);
    if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 400):
      return null;
    endif;
    return $return;
  }

  public function Get(
    string $Id,
    FilterMasks $Masks
  ){
    $get['access_token'] = $this->Token;
    $get['personFields'] = $Masks->Get();
    $curl = curl_init(self::Url . '/people/' . $Id . '?' . http_build_query($get));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($curl);
    if($return === false):
      return null;
    else:
      return json_decode($return, true);
    endif;
  }
}