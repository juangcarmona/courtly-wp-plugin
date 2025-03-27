<?php

require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/OpeningHoursRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/UserTypeRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtReservationRepository.php';

require_once plugin_dir_path(__FILE__) . '/../domain/services/CourtBlockService.php';

class CourtlyContainer {
    public static function courtBlockService() {
        return new CourtBlockService(new CourtBlockRepository());
    }
}
