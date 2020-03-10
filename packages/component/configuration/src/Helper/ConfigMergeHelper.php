<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

use Silvermoon\Configuration\Exception\InvalidNameException;

/**
 * Class ConfigMergeHelper
 */
class ConfigMergeHelper
{
    public static function mergeConfigRecursive(array $configuration, array $configurationToAdd): array
    {
        foreach ($configurationToAdd as $key => $item) {
            if (\is_array($item)) {
            }
        }
    }

    /**
     * @param string $key
     * @return array
     * @throws InvalidNameException
     */
    protected static function parseKey(string $key): array
    {
        $result = [];
        $result['type'] = 'standard';
        $result['name'] = $key;

        if (substr($key, -1) === ']') {
            $result['type'] = 'array';
            $openPosition = \strpos($key, '[');
            $result['name'] = \substr($key, 0, $openPosition);
            $result['dataType'] = \substr($key, $openPosition + 1, -1);
            if ($result['dataType'] === '+') {
                $result['type'] = 'arrayAdd';
            }
        }
        if (substr($key, -1) === '>') {
            $result['type'] = 'object';
            $openPosition = \strpos($key, '<');
            $result['name'] = \substr($key, 0, $openPosition);
            $result['dataType'] = \substr($key, $openPosition + 1, -1);
        }

        if (self::checkForValidCharacters($result['name']) === false) {
            throw new InvalidNameException('Only the characters a-z, A-Z, 0-9 and _ or allowed the name must not start with an number 0-9');
        }

        if ($result['type'] === 'array' || $result['type'] === 'object') {
            if (self::checkForValidDataType($result['dataType']) === false) {
                throw new InvalidNameException('Only the characters a-z, A-Z, 0-9 and _ or allowed the name must not start with an number 0-9');
            }
        }
        return $result;
    }

    /**
     * @param string $dataString
     * @return bool
     */
    protected static function checkForValidCharacters(string $dataString): bool
    {
        if (\preg_match('#^[a-zA-Z_][a-zA-Z0-9_]+$#', $dataString) !== 1) {
            return false;
        }
        return true;
    }

    /**
     * @param string $dataString
     * @return bool
     */
    protected static function checkForValidDataType(string $dataString): bool
    {
        $parts = \explode('\\', $dataString);
        foreach ($parts as $part) {
            if (self::checkForValidCharacters($part) === false) {
                return false;
            }
        }
        return true;
    }
}
