<?php

declare(strict_types=1);

namespace KetPHP\Utils;

use Throwable;

/**
 * Utility class for safely converting various values to boolean.
 *
 * Features:
 * - Strict and non-strict modes.
 * - Configurable truthy value list.
 * - Fully exception-safe (never throws).
 * - Handles all scalar and mixed types gracefully.
 *
 * Example:
 *  ```
 *  $data = ['key1' => 1, 'key2' => 'on', 'key3' => 'off'];
 *
 *  $result = Truth::of($data['key1']); // true
 *  $result = Truth::of($data['key2']); // true
 *  $result = Truth::of($data['key3']); // false
 *  ```
 *
 * @package KetPHP\Utils
 */
final class Truth
{

    /**
     * Default list of truthy values for non-strict comparison.
     *
     * @var array<int, mixed>
     */
    private static array $truthyValues = [
        1, true, '1', 'true', 'on', 'yes', 'y', 'ok', '+', 'active', 'enable', 'enabled'
    ];

    /**
     * Updates the global list of truthy values.
     *
     * You can pass any types — not just strings.
     *
     * @param array<int, mixed>|null $truthy Custom list of truthy values.
     *
     * @return void
     */
    public static function configure(?array $truthy = null): void
    {
        if (is_array($truthy)) {
            self::$truthyValues = array_values(array_filter($truthy, static fn($v) => $v !== null));
        }
    }

    /**
     * Safely convert a value to boolean.
     *
     * @param mixed $value The value to convert.
     * @param bool $strict Only accept true/1/'true'/'1' as true.
     * @param array<int, mixed>|null $customTruthies Optional custom truthy list.
     *
     * @return bool
     */
    public static function of(mixed $value, bool $strict = false, ?array $customTruthies = null): bool
    {
        return Safe::get($value, false, fn($v) => self::convert($v, $strict, $customTruthies), Safe::CAST_BOOL);
    }

    /**
     * Internal conversion logic.
     *
     * @param mixed $value
     * @param bool $strict
     * @param array<int, mixed>|null $customTruthies
     *
     * @return bool
     */
    private static function convert(mixed $value, bool $strict, ?array $customTruthies): bool
    {
        if ($strict === true) {
            return $value === true || $value === 1 || $value === '1' || $value === 'true';
        }
        if (is_bool($value) === true) {
            return $value;
        }
        if (is_int($value) === true || is_float($value) === true) {
            return $value != 0;
        }
        if ($value === null) {
            return false;
        }

        $list = $customTruthies ?? self::$truthyValues;
        if (is_string($value) === true) {
            $normalized = mb_strtolower(trim($value));
            foreach ($list as $truthy) {
                if (is_string($truthy) === true && mb_strtolower($truthy) === $normalized) {
                    return true;
                }
                if ($truthy === $value) {
                    return true;
                }
            }
        } else {
            return in_array($value, $list, true) === true;
        }

        return false;
    }
}