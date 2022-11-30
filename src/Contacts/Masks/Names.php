<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.11.30.00

namespace ProtocolLive\GoogleApi\Contacts\Masks;

enum Names:string{
  case Display = 'displayName';
  case DisplayLastFirst = 'displayNameLastFirst';
  case Family = 'familyName';
  case FamilyPhonetic = 'phoneticFamilyName';
  case FullPhonetic = 'phoneticFullName';
  case Given = 'givenName';
  case GivenPhonetic = 'phoneticGivenName';
  case HonorificPrefix = 'honorificPrefix';
  case HonorificPrefixPhonetic = 'phoneticHonorificPrefix';
  case HonorificSuffix = 'honorificSuffix';
  case HonorificSuffixPhonetic = 'phoneticHonorificSuffix';
  case Meta = 'metadata';
  case Middle = 'middleName';
  case MiddlePhonetic = 'phoneticMiddleName';
  case Unstructured = 'unstructuredName';
}