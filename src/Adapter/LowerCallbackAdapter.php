<?php

declare(strict_types=1);

namespace Cacing69\Cquery\Adapter;

use Cacing69\Cquery\CallbackAdapter;
use Cacing69\Cquery\Support\RegExp;

class LowerCallbackAdapter extends CallbackAdapter
{
    protected static $signature = RegExp::IS_LOWER;

    public static function getSignature()
    {
        return self::$signature;
    }

    public function __construct(string $raw)
    {
        $this->raw = $raw;

        $this->callback = function (string $value) {
            return strtolower($value);
        };

        // check if function is nested
        if (preg_match('/^\s?lower\(\s?([a-z0-9_]*\(.+?\))\s?\)$/', $raw)) {
            preg_match('/^\s?lower\(\s?([a-z0-9_]*\(.+?\))\s?\)$/', $raw, $extract);

            $extractChild = $this->extractChild($extract[1]);
            $_childCallback = $extractChild->getAdapter()->getCallback();

            if ($_childCallback) {
                $this->callback = function (string $value) use ($_childCallback) {
                    return strtolower((string) $_childCallback($value));
                };
            }
        } else {
            preg_match(self::$signature, $raw, $node);

            if (array_key_exists(1, $node)) {
                $this->node = $node[1];
            }

            $this->callMethod = 'extract';
            $this->callMethodParameter = ['_text'];
        }
    }
}
