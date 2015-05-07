<?php

@require_once dirname(__FILE__).'/../config.local.php';
require_once dirname(__FILE__).'/../config.php';

// Include app stuff
require_once dirname(__FILE__).'/utils.php';
require_once dirname(__FILE__).'/auth.php';
require_once dirname(__FILE__).'/db.php';
require_once dirname(__FILE__).'/users.php';
require_once dirname(__FILE__).'/layout.php';

// Include Aura entities
require_once dirname(__FILE__).'/commands.php';
require_once dirname(__FILE__).'/tasks.php';
require_once dirname(__FILE__).'/pings.php';
require_once dirname(__FILE__).'/devices.php';
require_once dirname(__FILE__).'/groups.php';
require_once dirname(__FILE__).'/interpreter.php';
require_once dirname(__FILE__).'/spyglass.php';

authInit();

?>
