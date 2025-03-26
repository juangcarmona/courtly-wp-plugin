<?php

require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../domain/services/AvailabilityService.php';

class CourtlyContainer
{
    public static function availabilityService()
    {
        return new AvailabilityService(
            new CourtBlockRepository()
        );
    }
}
