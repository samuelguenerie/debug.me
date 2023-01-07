<?php

namespace Plugo\Services\Security;

class Security
{
    /**
     * @param array|string $mixed
     * @return array|string
     */
    public function secureXssVulnerabilities(array|string $mixed): array|string
    {
        if (is_string($mixed)) {
            return htmlspecialchars($mixed);
        }

        foreach ($mixed as $key => $value) {
            if (is_string($value)) {
                $mixed[$key] = htmlspecialchars($value);
            }
        }

        return $mixed;
    }
}
