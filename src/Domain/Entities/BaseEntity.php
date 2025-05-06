<?php
namespace Juangcarmona\Courtly\Domain\Entities;
interface BaseEntity
{
    public static function schema(string $tableName): string;
}
