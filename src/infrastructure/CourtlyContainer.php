<?php

require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/OpeningHoursRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/UserTypeRepository.php';
require_once plugin_dir_path(__FILE__) . '/../infrastructure/repositories/CourtReservationRepository.php';

require_once plugin_dir_path(__FILE__) . '/../domain/services/CourtBlockService.php';
require_once plugin_dir_path(__FILE__) . '/../domain/services/CourtReservationService.php';

require_once plugin_dir_path(__FILE__) . '/../application/utils/EventTransformer.php';


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

    public static function openingHoursRepository() {
        return new OpeningHoursRepository();
    }

    public static function courtReservationRepository() {
        return new CourtReservationRepository();
    }

    public static function courtRepository() {
        return new CourtRepository();
    }    
    
    public static function courtBlockRepository() {
        return new CourtBlockRepository();
    }

    public static function userTypeRepository() {
        return new UserTypeRepository();
    }

    public static function eventTransformer() {
        return new EventTransformer();
    }
}
