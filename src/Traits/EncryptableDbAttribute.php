<?php

namespace Izpixel\LaravelTracker\Traits;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Trait EncryptableDbAttribute
 * Provides automatic encryption and decryption of model attributes based on the configuration settings.
 * Ensures that specific fields like 'id', 'ip_address', 'created_at', and 'updated_at' are never encrypted.
 * @see https://github.com/betterapp/laravel-db-encrypter
 */
trait EncryptableDbAttribute
{
    /**
     * Decrypt attributes that are configured for decryption before casting.
     */
    protected function transformModelValue($key, $value)
    {
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        if ($this->shouldEncrypt($key) && !is_null($value) && $value !== '') {
            $value = $this->decrypt($value);
        }

        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        if ($this->isDateAttribute($key)) {
            return $this->asDateTime($value);
        }

        return $value;
    }

    /**
     * Encrypt attributes that are configured for encryption before saving.
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        if ($this->isDateAttribute($key)) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isClassCastable($key)) {
            $this->setClassCastableAttribute($key, $value);
            return $this;
        }

        if ($this->isJsonCastable($key) && $this->shouldEncrypt($key) && !is_null($value)) {
            $value = $this->encryptJson($value);
        }

        if ($this->shouldEncrypt($key) && !is_null($value) && !$this->isJsonCastable($key)) {
            $value = $this->encrypt($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Decrypt the attributes that are configured to be decrypted when converting to array.
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();
        foreach ($attributes as $key => $value) {
            if ($this->shouldEncrypt($key) && !is_null($value) && $value !== '') {
                if ($this->isJsonCastable($key)) {
                    $attributes[$key] = $this->decryptJson($value);
                } else {
                    $attributes[$key] = $this->decrypt($value);
                }
            }
        }
        return $attributes;
    }

    /**
     * Determine if the attribute should be encrypted based on the configuration.
     * Excludes 'ip_address', 'id', 'created_at', and 'updated_at' from being encrypted.
     */
    private function shouldEncrypt($key): bool
    {
        $neverEncrypt = ['id', 'ip_address', 'created_at', 'updated_at'];
        return !in_array($key, $neverEncrypt) &&
            in_array($key, config('laravel-tracker.encrypt.fields', [])) &&
            config('laravel-tracker.encrypt.enabled', false);
    }

    private function encryptJson($data)
    {
        array_walk_recursive($data, function (&$item, $key) {
            if (!is_null($item)) {
                $item = $this->encrypt($item);
            }
        });
        return $data;
    }

    private function decryptJson($data)
    {
        array_walk_recursive($data, function (&$item, $key) {
            if (!is_null($item)) {
                $item = $this->decrypt($item);
            }
        });
        return $data;
    }

    /**
     * Encrypt the given value.
     */
    private function encrypt($value): mixed
    {
        try {
            return Crypt::encrypt($value);
        } catch (EncryptException $e) {
            return $value; // Optionally log the error or handle it as needed
        }
    }

    /**
     * Decrypt the given value.
     */
    private function decrypt($value): mixed
    {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return $value; // Optionally log the error or handle it as needed
        }
    }
}
