<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account\Call;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Version;

/**
 * @property string accountSid
 * @property string apiVersion
 * @property string callSid
 * @property \DateTime dateCreated
 * @property \DateTime dateUpdated
 * @property string errorCode
 * @property string log
 * @property \DateTime messageDate
 * @property string messageText
 * @property string moreInfo
 * @property string requestMethod
 * @property string requestUrl
 * @property string sid
 * @property string uri
 * @property string requestVariables
 * @property string responseBody
 * @property string responseHeaders
 */
class NotificationInstance extends InstanceResource {
    /**
     * Initialize the NotificationInstance
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The account_sid
     * @param string $callSid The call_sid
     * @param string $sid The sid
     * @return \Twilio\Rest\Api\V2010\Account\Call\NotificationInstance 
     */
    public function __construct(Version $version, array $payload, $accountSid, $callSid, $sid = null) {
        parent::__construct($version);
        
        // Marshaled Properties
        $this->properties = array(
            'accountSid' => $payload['account_sid'],
            'apiVersion' => $payload['api_version'],
            'callSid' => $payload['call_sid'],
            'dateCreated' => Deserialize::iso8601DateTime($payload['date_created']),
            'dateUpdated' => Deserialize::iso8601DateTime($payload['date_updated']),
            'errorCode' => $payload['error_code'],
            'log' => $payload['log'],
            'messageDate' => Deserialize::iso8601DateTime($payload['message_date']),
            'messageText' => $payload['message_text'],
            'moreInfo' => $payload['more_info'],
            'requestMethod' => $payload['request_method'],
            'requestUrl' => $payload['request_url'],
            'sid' => $payload['sid'],
            'uri' => $payload['uri'],
            'requestVariables' => array_key_exists('request_variables', $payload) ? $payload['request_variables'] : null,
            'responseBody' => array_key_exists('response_body', $payload) ? $payload['response_body'] : null,
            'responseHeaders' => array_key_exists('response_headers', $payload) ? $payload['response_headers'] : null,
        );
        
        $this->solution = array(
            'accountSid' => $accountSid,
            'callSid' => $callSid,
            'sid' => $sid ?: $this->properties['sid'],
        );
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     * 
     * @return \Twilio\Rest\Api\V2010\Account\Call\NotificationContext Context for
     *                                                                 this
     *                                                                 NotificationInstance
     */
    protected function proxy() {
        if (!$this->context) {
            $this->context = new NotificationContext(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['callSid'],
                $this->solution['sid']
            );
        }
        
        return $this->context;
    }

    /**
     * Fetch a NotificationInstance
     * 
     * @return NotificationInstance Fetched NotificationInstance
     */
    public function fetch() {
        return $this->proxy()->fetch();
    }

    /**
     * Deletes the NotificationInstance
     * 
     * @return boolean True if delete succeeds, false otherwise
     */
    public function delete() {
        return $this->proxy()->delete();
    }

    /**
     * Magic getter to access properties
     * 
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }
        
        throw new TwilioException('Unknown property: ' . $name);
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
        return '[Twilio.Api.V2010.NotificationInstance ' . implode(' ', $context) . ']';
    }
}
