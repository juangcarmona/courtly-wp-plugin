<?php

require_once plugin_dir_path(__FILE__) . '/../Infrastructure/Repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '/../Infrastructure/Repositories/OpeningHoursRepository.php';
require_once plugin_dir_path(__FILE__) . '/../Infrastructure/Repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '/../Infrastructure/Repositories/UserTypeRepository.php';
require_once plugin_dir_path(__FILE__) . '/../Infrastructure/Repositories/CourtReservationRepository.php';

require_once plugin_dir_path(__FILE__) . '/../Domain/Services/CourtBlockService.php';
require_once plugin_dir_path(__FILE__) . '/../Domain/Services/CourtReservationService.php';

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
        return EventTransformer::class;
    }
}
