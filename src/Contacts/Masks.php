<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.13.00

namespace ProtocolLive\GoogleApi\Contacts;

enum Masks:string{
  case Addresses = 'addresses';
  case Bio = 'biographies';
  case Birthdays = 'birthdays';
  case CalendarUrls = 'calendarUrls';
  case ClientData = 'clientData';
  case Email = 'emailAddresses';
  case Events = 'events';
  case ExternalIds = 'externalIds';
  case Genders = 'genders';
  case ImClients = 'imClients';
  case Interests = 'interests';
  case Locales = 'locales';
  case Locations = 'locations';
  case Memberships = 'memberships';
  case MiscKeywords = 'miscKeywords';
  case Names = 'names';
  case Nicknames = 'nicknames';
  case Occupations = 'occupations';
  case Organizations = 'organizations';
  case Phones = 'phoneNumbers';
  case Relations = 'relations';
  case SipAddresses = 'sipAddresses';
  case Urls = 'urls';
  case UserDefined = 'userDefined';
}