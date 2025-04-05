<?php

require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/OpeningHoursRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/UserTypeRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtReservationRepository.php';

require_once plugin_dir_path(__FILE__) . '/../domain/services/CourtBlockService.php';
require_once plugin_dir_path(__FILE__) . '/../domain/services/CourtReservationService.php';


class CourtlyContainer {
    public static function courtBlockService() {
        return new CourtBlockService(new CourtBlockRepository());
    }
  
    public static function courtReservationService() {
        return new CourtReservationService(
            new CourtReservationRepository(),
            new CourtBlockRepository(),
            new OpeningHoursRepository()
        );
    }

    public static function userTypeRepository() {
        return new UserTypeRepository();
    }
}
