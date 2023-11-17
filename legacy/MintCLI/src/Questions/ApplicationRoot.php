<?php

namespace MintHCM\MintCLI\Questions;

class ApplicationRoot extends Question
{
    protected $question = "Application Root Directory (ie. /var/www, /usr/local/htdocs, etc.)";
    protected $defaultValue = "/var/www";
}
