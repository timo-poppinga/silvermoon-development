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
        }
        if (substr($key, -1) === '>') {
            $result['type'] = 'object';
            $openPosition = \strpos($key, '<');
            $result['name'] = \substr($key, 0, $openPosition);
            $result['dataType'] = \substr($key, $openPosition + 1, -1);
        }
        if (\preg_match('#^[a-zA-Z_][a-zA-Z0-9_]+$#', $result['name']) !== 1) {
            throw new InvalidNameException('Only the characters a-z, A-Z, 0-9 and _ or allowed the name must not start with an number 0-9');
        }
        return $result;
    }
}
