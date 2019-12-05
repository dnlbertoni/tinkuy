<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'app/config/bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);