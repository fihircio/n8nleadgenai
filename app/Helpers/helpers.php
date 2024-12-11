<?php

if (!function_exists('theme')) {
    function theme($component, $key, $default = null) {
        $themeService = app(\App\Providers\ThemeService::class);
        return $themeService->getTheme($component, $key, $default);
    }
}

if (!function_exists('extractNumbers')) {
    function extractNumbers($input) {
        // Remove thousands separators (assuming they're either commas or periods)
        $input = preg_replace('/(\d),(\d{3})/', '$1$2', $input);

        // Match the numeric part of the string
        if (preg_match('/([0-9]*[.,]?[0-9]+)/', $input, $matches)) {
            $number = $matches[1];

            // Replace comma with period for decimal point (if comma is used)
            $number = str_replace(',', '.', $number);

            // Convert to float
            $value = (float) $number;

            // If the number has no decimal places, convert to integer
            return $value == (int)$value ? (int)$value : $value;
        }

        // If no numeric part found, return 0
        return 0;
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format price string, removing '.00' or ',00' if present.
     *
     * @param string $input The price string
     * @return string The formatted price string
     */
    function formatPrice(string $input): string
    {
        // Extract the numeric part
        $numericValue = extractNumbers($input);

        // Extract non-numeric parts (currency symbols, etc.)
        preg_match('/([^\d,.\s-]+)/', $input, $matches);
        $currencyPart = $matches[1] ?? '';

        // Determine the position of the currency symbol (before or after)
        $isCurrencyBefore = strpos($input, $currencyPart) === 0;

        // Format the numeric part
        if (is_int($numericValue) || (is_float($numericValue) && $numericValue == (int)$numericValue)) {
            $formattedNumber = number_format($numericValue, 0, '.', '');
        } else {
            $formattedNumber = number_format($numericValue, 2, ',', '');
        }

        // Combine the formatted number with the currency part
        if ($isCurrencyBefore) {
            return $currencyPart . '' . $formattedNumber;
        } else {
            return $formattedNumber . '' . $currencyPart;
        }
    }
}
