<?php

namespace TheBachtiarz\Base\Interfaces\Http;

interface ResponseInterface
{
    public const string CONDITION = 'condition';
    public const string STATUS = 'status';
    public const string HTTP_CODE = 'httpCode';
    public const string MESSAGE = 'message';
    public const string DATA = 'data';

    public const string ERRORS = 'errors';
}
