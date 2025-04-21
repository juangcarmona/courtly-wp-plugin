<?php

namespace Juangcarmona\Courtly\Domain\Repositories;

interface CourtBlockRepositoryInterface
{
    /**
     * @return object[] Array of blocked slot objects
     */
    public function getBlockedSlots(int $courtId);

    /**
     * @param array $data Associative array of block info
     */
    public function insertBlockedSlot(array $data);

    public function deleteBlockedSlot(int $id);
}
