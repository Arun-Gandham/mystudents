<?php
namespace App\Models\Traits;

trait HasTimestampsImmutable
{
    protected function initializeHasTimestampsImmutable(): void
    {
        $this->casts['created_at'] = 'immutable_datetime';
        $this->casts['updated_at'] = 'immutable_datetime';
    }
}
