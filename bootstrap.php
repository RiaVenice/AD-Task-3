<?php
define('BASE_PATH', realpath(__DIR__));
define('HANDLERS_PATH', realpath(BASE_PATH . "/handlers"));
define('UTILS_PATH', realpath(BASE_PATH . "/utils"));
define('TEMPLATES_PATH', realpath(BASE_PATH . "/components/templates"));
define('LAYOUTS_PATH', realpath(BASE_PATH . "/layouts"));
define('DUMMIES_PATH', realpath(BASE_PATH . "/staticDatas/dummies"));

chdir(BASE_PATH);