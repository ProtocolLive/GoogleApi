<?php
//2022.09.13.00
//https://developers.google.com/identity/protocols/oauth2/native-app#obtainingaccesstokens

namespace ProtocolLive\GoogleApi;

enum Scopes:string{
  case Contacts = 'https://www.googleapis.com/auth/contacts';
}