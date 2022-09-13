<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts\Masks;

enum Address:string{
  case AddressExtended = 'extendedAddress';
  case City = 'city';
  case Country = 'country';
  case CountryCode = 'countryCode';
  case FormattedValue = 'formattedValue';
  case Meta = 'metadata';
  case Pobox = 'poBox';
  case PostalCode = 'postalCode';
  case Region = 'region';
  case Street = 'streetAddress';
  case Type = 'type';
}