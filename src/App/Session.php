<?php

namespace Jaxon\Laravel\App;

use Jaxon\App\Session\SessionInterface;

use function session;

class Session implements SessionInterface
{
    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return session()->getId();
    }

    /**
     * @inheritDoc
     */
    public function newId(bool $bDeleteData = false): void
    {
        if($bDeleteData)
        {
            session()->flush();
        }
        session()->regenerate();
    }

    /**
     * @inheritDoc
     */
    public function set(string $sKey, mixed $xValue): void
    {
        session([$sKey => $xValue]);
    }

    /**
     * @inheritDoc
     */
    public function has(string $sKey): bool
    {
        return session()->exists($sKey);
    }

    /**
     * @inheritDoc
     */
    public function get(string $sKey, mixed $xDefault = null): mixed
    {
        return session($sKey, $xDefault);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return session()->all();
    }

    /**
     * @inheritDoc
     */
    public function delete(string $sKey): void
    {
        session()->forget($sKey);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        session()->flush();
    }
}
