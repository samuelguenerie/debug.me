<?php

namespace Plugo\Services\Security;

class Security
{
    /**
     * @param array|string $input
     * @return array|string
     */
    public function secureXssVulnerabilities(array|string $input): array|string
    {
        if (is_array($input)) {
            $output = [];

            foreach ($input as $key => $value) {
                if (is_string($value)) {
                    $output[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                } else {
                    $output[$key] = $value;
                }
            }

            return $output;
        }

        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
