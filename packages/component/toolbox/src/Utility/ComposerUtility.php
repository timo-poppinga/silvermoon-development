<?php

declare(strict_types=1);

namespace Silvermoon\Toolbox\Utility;

use Silvermoon\Toolbox\Exception\ComposerJsonException;

class ComposerUtility
{

    /**
     * @param string $path
     * @return mixed[]
     */
    public static function readComposerJson(string $path): array
    {
        try {
            $composerString = \file_get_contents($path);
        } catch (\Exception $exception) {
            throw new ComposerJsonException(1600601832, $exception);
        }
        if ($composerString === false) {
            throw new ComposerJsonException(1600601541);
        }

        try {
            $composerArray = \json_decode($composerString, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            throw new ComposerJsonException(1600601715, $exception);
        }
        return $composerArray;
    }


}
