<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\IpMessaging\V1;

use Twilio\Options;
use Twilio\Values;

abstract class CredentialOptions {
    /**
     * @param string $friendlyName The friendly_name
     * @param string $certificate The certificate
     * @param string $privateKey The private_key
     * @param string $sandbox The sandbox
     * @param string $apiKey The api_key
     * @return CreateCredentialOptions Options builder
     */
    public static function create($friendlyName = Values::NONE, $certificate = Values::NONE, $privateKey = Values::NONE, $sandbox = Values::NONE, $apiKey = Values::NONE) {
        return new CreateCredentialOptions($friendlyName, $certificate, $privateKey, $sandbox, $apiKey);
    }

    /**
     * @param string $friendlyName The friendly_name
     * @param string $certificate The certificate
     * @param string $privateKey The private_key
     * @param string $sandbox The sandbox
     * @param string $apiKey The api_key
     * @return UpdateCredentialOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $certificate = Values::NONE, $privateKey = Values::NONE, $sandbox = Values::NONE, $apiKey = Values::NONE) {
        return new UpdateCredentialOptions($friendlyName, $certificate, $privateKey, $sandbox, $apiKey);
    }
}

class CreateCredentialOptions extends Options {
    /**
     * @param string $friendlyName The friendly_name
     * @param string $certificate The certificate
     * @param string $privateKey The private_key
     * @param string $sandbox The sandbox
     * @param string $apiKey The api_key
     */
    public function __construct($friendlyName = Values::NONE, $certificate = Values::NONE, $privateKey = Values::NONE, $sandbox = Values::NONE, $apiKey = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['certificate'] = $certificate;
        $this->options['privateKey'] = $privateKey;
        $this->options['sandbox'] = $sandbox;
        $this->options['apiKey'] = $apiKey;
    }

    /**
     * The friendly_name
     * 
     * @param string $friendlyName The friendly_name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The certificate
     * 
     * @param string $certificate The certificate
     * @return $this Fluent Builder
     */
    public function setCertificate($certificate) {
        $this->options['certificate'] = $certificate;
        return $this;
    }

    /**
     * The private_key
     * 
     * @param string $privateKey The private_key
     * @return $this Fluent Builder
     */
    public function setPrivateKey($privateKey) {
        $this->options['privateKey'] = $privateKey;
        return $this;
    }

    /**
     * The sandbox
     * 
     * @param string $sandbox The sandbox
     * @return $this Fluent Builder
     */
    public function setSandbox($sandbox) {
        $this->options['sandbox'] = $sandbox;
        return $this;
    }

    /**
     * The api_key
     * 
     * @param string $apiKey The api_key
     * @return $this Fluent Builder
     */
    public function setApiKey($apiKey) {
        $this->options['apiKey'] = $apiKey;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.IpMessaging.V1.CreateCredentialOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateCredentialOptions extends Options {
    /**
     * @param string $friendlyName The friendly_name
     * @param string $certificate The certificate
     * @param string $privateKey The private_key
     * @param string $sandbox The sandbox
     * @param string $apiKey The api_key
     */
    public function __construct($friendlyName = Values::NONE, $certificate = Values::NONE, $privateKey = Values::NONE, $sandbox = Values::NONE, $apiKey = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['certificate'] = $certificate;
        $this->options['privateKey'] = $privateKey;
        $this->options['sandbox'] = $sandbox;
        $this->options['apiKey'] = $apiKey;
    }

    /**
     * The friendly_name
     * 
     * @param string $friendlyName The friendly_name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The certificate
     * 
     * @param string $certificate The certificate
     * @return $this Fluent Builder
     */
    public function setCertificate($certificate) {
        $this->options['certificate'] = $certificate;
        return $this;
    }

    /**
     * The private_key
     * 
     * @param string $privateKey The private_key
     * @return $this Fluent Builder
     */
    public function setPrivateKey($privateKey) {
        $this->options['privateKey'] = $privateKey;
        return $this;
    }

    /**
     * The sandbox
     * 
     * @param string $sandbox The sandbox
     * @return $this Fluent Builder
     */
    public function setSandbox($sandbox) {
        $this->options['sandbox'] = $sandbox;
        return $this;
    }

    /**
     * The api_key
     * 
     * @param string $apiKey The api_key
     * @return $this Fluent Builder
     */
    public function setApiKey($apiKey) {
        $this->options['apiKey'] = $apiKey;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.IpMessaging.V1.UpdateCredentialOptions ' . implode(' ', $options) . ']';
    }
}
