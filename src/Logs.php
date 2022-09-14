<?php
//Protocol Corporation Ltda.
//https://github.com/ProtocolLive/GoogleApi
//2022.09.14.00

namespace ProtocolLive\GoogleApi;

enum Logs:int{
  case None = 0;
  case Send = 1;
  case Response = 2;
  case All = 3;
}