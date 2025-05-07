namespace Juangcarmona\Courtly\Domain\Services;

use Juangcarmona\Courtly\Domain\Repositories\OpeningHoursRepositoryInterface;

class OpeningHoursService
{
    private OpeningHoursRepositoryInterface $repository;

    public function __construct(OpeningHoursRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOpeningHours(): array
    {
        return $this->repository->getAll();
    }

    public function saveOpeningHours(int $dayOfWeek, array $timeRanges, bool $closed): bool
    {
        return $this->repository->upsert($dayOfWeek, $timeRanges, $closed);
    }
}