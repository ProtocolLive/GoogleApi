<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts\Masks;

enum Email:string{
  case Meta = 'metadata';
  case Name = 'displayName';
  case Type = 'type';
  case Value = 'value';
}