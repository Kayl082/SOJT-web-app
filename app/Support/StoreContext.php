<?php

namespace App\Support;

use App\Models\Store;

class StoreContext
{
    protected ?Store $store = null;

    public function set(Store $store): void
    {
        $this->store = $store;
    }

    public function get(): ?Store
    {
        return $this->store;
    }

    public function id(): ?int
    {
        return $this->store?->id;
    }

    public function hasStore(): bool
    {
        return !is_null($this->store);
    }

    public function clear(): void
    {
        $this->store = null;
    }
}