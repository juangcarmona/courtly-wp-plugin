<?php
// courtly/domain/Constants.php

if (!defined('COURTLY_MAX_RESERVATION_MINUTES')) define('COURTLY_MAX_RESERVATION_MINUTES', 60);
if (!defined('COURTLY_MAX_RESERVATIONS_PER_DAY')) define('COURTLY_MAX_RESERVATIONS_PER_DAY', 1);
if (!defined('COURTLY_MIN_HOURS_TO_CANCEL')) define('COURTLY_MIN_HOURS_TO_CANCEL', 24);

if (!defined('COURTLY_DATE_FORMAT')) define('COURTLY_DATE_FORMAT', 'Y-m-d');
if (!defined('COURTLY_TIME_FORMAT')) define('COURTLY_TIME_FORMAT', 'H:i');
