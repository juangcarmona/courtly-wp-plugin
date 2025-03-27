<?php

interface BaseEntity
{
    public static function schema(string $tableName): string;
}
