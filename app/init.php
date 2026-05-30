<?php
// require_once '../config/config.php';
// wait, from public/index.php, the working directory is public. So require '../config/config.php' is correct.
require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/Core/App.php';
require_once __DIR__ . '/Core/Controller.php';
require_once __DIR__ . '/Core/Database.php';
require_once __DIR__ . '/Core/Flasher.php';
require_once __DIR__ . '/Core/NocGuard.php';
