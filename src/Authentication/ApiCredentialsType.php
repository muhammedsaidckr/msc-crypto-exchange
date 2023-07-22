<?php

namespace Msc\MscCryptoExchange\Authentication;

enum ApiCredentialsType
{
    case HMAC;
    case RSA_XML;
    case RSA_PEM;
}
