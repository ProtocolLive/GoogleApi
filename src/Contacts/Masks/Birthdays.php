<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts\Masks;

enum Birthdays:string{
  case Date = 'date';
  case Meta = 'metadata';
  case Text = 'text';
}