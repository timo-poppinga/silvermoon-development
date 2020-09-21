<?php

declare(strict_types=1);

namespace Silvermoon\Toolbox\Utility;

class ApcuUtility
{
    /**
     * @return bool
     */
    public static function isApcuEnabled(): bool
    {
        $apcuEnabled = (ini_get('apc.enabled') === true || ini_get('apc.enabled') === '1') && extension_loaded('apcu') === true;
        return $apcuEnabled && PHP_SAPI !== 'cli';
    }

    /**
     * @param string $identifier
     * @return string|null
     */
    public static function read(string $identifier): ?string
    {
        if(self::isApcuEnabled() === false) {
            return null;
        }
        $cacheIdentifier = self::buildCacheIdentifier($identifier);
        $success = false;
        $data = \apcu_fetch($cacheIdentifier, $success);
        if($success === true) {
            return $data;
        }
        return null;
    }

    /**
     * @param string $identifier
     * @param string $data
     * @param int|null $timeToLiveInSeconds
     */
    public static function write(string $identifier, string $data, ?int $timeToLiveInSeconds = null): void
    {
        if(self::isApcuEnabled() === false) {
            return;
        }
        $cacheIdentifier = self::buildCacheIdentifier($identifier);
        if($timeToLiveInSeconds === null) {
            $timeToLiveInSeconds = 0;
        }
        \apcu_add($cacheIdentifier, $data, $timeToLiveInSeconds);
    }

    protected static function buildCacheIdentifier(string $identifier): string
    {
        return \hash_hmac('md5', $identifier, 'ce741c3b2f5d4037b1ef3a344ca4eaf4');
    }
}
