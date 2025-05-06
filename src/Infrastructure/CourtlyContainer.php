<?php

namespace Juangcarmona\Courtly\Infrastructure;

use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtBlockRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\UserTypeRepositoryInterface;
use Juangcarmona\Courtly\Domain\Repositories\CourtReservationRepositoryInterface;

use Juangcarmona\Courtly\Domain\Services\CourtBlockService;
use Juangcarmona\Courtly\Domain\Services\CourtReservationService;

use Juangcarmona\Courtly\Infrastructure\Repositories\CourtRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\OpeningHoursRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\CourtBlockRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\UserTypeRepository;
use Juangcarmona\Courtly\Infrastructure\Repositories\CourtReservationRepository;

use Juangcarmona\Courtly\Application\Utils\EventTransformer;

class CourtlyContainer
{
    public static function courtBlockService(): CourtBlockService
    {
        return new CourtBlockService(self::courtBlockRepository());
    }

    public static function courtReservationService(): CourtReservationService
    {
        return new CourtReservationService(
            self::courtReservationRepository(),
            self::courtBlockRepository(),
            self::openingHoursRepository()
        );
    }

    public static function openingHoursRepository(): OpeningHoursRepositoryInterface
    {
        return new OpeningHoursRepository();
    }

    public static function courtReservationRepository(): CourtReservationRepositoryInterface
    {
        return new CourtReservationRepository();
    }

    public static function courtRepository(): CourtRepositoryInterface
    {
        return new CourtRepository();
    }

    public static function courtBlockRepository(): CourtBlockRepositoryInterface
    {
        return new CourtBlockRepository();
    }

    public static function userTypeRepository(): UserTypeRepositoryInterface
    {
        return new UserTypeRepository();
    }

    public static function eventTransformer(): EventTransformer
    {
        return new EventTransformer();
    }
}
