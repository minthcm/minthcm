<?php

namespace MintHCM\Lib\MintLogic;

enum Hook: string
{
    case ALL = 'all';
    case INIT = 'init';
    case CHANGE = 'change';
}
