<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts\Masks;

enum Names:string{
  case NameDisplay = 'displayName';
  case NameDisplayLastFirst = 'displayNameLastFirst';
  case NameFamily = 'familyName';
  case NameFamilyPhonetic = 'phoneticFamilyName';
  case NameFullPhonetic = 'phoneticFullName';
  case NameGiven = 'givenName';
  case NameGivenPhonetic = 'phoneticGivenName';
  case NameHonorificPrefix = 'honorificPrefix';
  case NameHonorificPrefixPhonetic = 'phoneticHonorificPrefix';
  case NameHonorificSuffix = 'honorificSuffix';
  case NameHonorificSuffixPhonetic = 'phoneticHonorificSuffix';
  case NameMeta = 'metadata';
  case NameMiddle = 'middleName';
  case NameMiddlePhonetic = 'phoneticMiddleName';
  case NameUnstructured = 'unstructuredName';
}