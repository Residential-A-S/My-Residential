<?php

namespace models;

use abstract\Meta;
use core\Validate;

class Property extends Meta
{
    private int $id {
        get {
            return $this->id;
        }
    }
    private string $country {
        get {
            return $this->country;
        }
    }
    private string $postal_code {
        get {
            return $this->postal_code;
        }
    }
    private string $city {
        get {
            return $this->city;
        }
    }
    private string $address {
        get {
            return $this->address;
        }
    }

    public function __construct(
        int $id,
        string $country,
        string $postal_code,
        string $city,
        string $address,
    ) {
        $this->id             = $id;
        $this->country        = $country;
        $this->postal_code    = $postal_code;
        $this->city           = $city;
        $this->address        = $address;
    }

    public static function create(
        string $country,
        string $postal_code,
        string $city,
        string $address,
        Organization $organization
    ): bool {
        if (
            !Validate::country($country) ||
            !Validate::postalCode($postal_code) ||
            !Validate::city($city) ||
            !Validate::address($address)
        ) {
            return false; // Invalid input
        }

        $property_id = db()->insert("property", [
            "country"       => $country,
            "postal_code"   => $postal_code,
            "city"          => $city,
            "address"       => $address,
        ]);

        if (!is_int($property_id)) {
            return false;
        }

        $organization->addProperty($property_id);
        return true;
    }
}
