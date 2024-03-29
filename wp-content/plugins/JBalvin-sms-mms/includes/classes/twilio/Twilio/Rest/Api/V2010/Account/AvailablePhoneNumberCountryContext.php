<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\MobileList;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\TollFreeList;
use Twilio\Values;
use Twilio\Version;

/**
 * @property \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalList local
 * @property \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\TollFreeList tollFree
 * @property \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\MobileList mobile
 */
class AvailablePhoneNumberCountryContext extends InstanceContext {
    protected $_local = null;
    protected $_tollFree = null;
    protected $_mobile = null;

    /**
     * Initialize the AvailablePhoneNumberCountryContext
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $accountSid The account_sid
     * @param string $countryCode The country_code
     * @return \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountryContext 
     */
    public function __construct(Version $version, $accountSid, $countryCode) {
        parent::__construct($version);
        
        // Path Solution
        $this->solution = array(
            'accountSid' => $accountSid,
            'countryCode' => $countryCode,
        );
        
        $this->uri = '/Accounts/' . rawurlencode($accountSid) . '/AvailablePhoneNumbers/' . rawurlencode($countryCode) . '.json';
    }

    /**
     * Fetch a AvailablePhoneNumberCountryInstance
     * 
     * @return AvailablePhoneNumberCountryInstance Fetched
     *                                             AvailablePhoneNumberCountryInstance
     */
    public function fetch() {
        $params = Values::of(array());
        
        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );
        
        return new AvailablePhoneNumberCountryInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['countryCode']
        );
    }

    /**
     * Access the local
     * 
     * @return \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalList 
     */
    protected function getLocal() {
        if (!$this->_local) {
            $this->_local = new LocalList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }
        
        return $this->_local;
    }

    /**
     * Access the tollFree
     * 
     * @return \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\TollFreeList 
     */
    protected function getTollFree() {
        if (!$this->_tollFree) {
            $this->_tollFree = new TollFreeList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }
        
        return $this->_tollFree;
    }

    /**
     * Access the mobile
     * 
     * @return \Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\MobileList 
     */
    protected function getMobile() {
        if (!$this->_mobile) {
            $this->_mobile = new MobileList(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['countryCode']
            );
        }
        
        return $this->_mobile;
    }

    /**
     * Magic getter to lazy load subresources
     * 
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws \Twilio\Exceptions\TwilioException For unknown subresources
     */
    public function __get($name) {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }
        
        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     * 
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws \Twilio\Exceptions\TwilioException For unknown resource
     */
    public function __call($name, $arguments) {
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }
        
        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.AvailablePhoneNumberCountryContext ' . implode(' ', $context) . ']';
    }
}
