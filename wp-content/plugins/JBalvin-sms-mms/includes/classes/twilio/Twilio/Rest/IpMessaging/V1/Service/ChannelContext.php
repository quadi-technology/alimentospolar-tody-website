<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\IpMessaging\V1\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Rest\IpMessaging\V1\Service\Channel\MemberList;
use Twilio\Rest\IpMessaging\V1\Service\Channel\MessageList;
use Twilio\Values;
use Twilio\Version;

/**
 * @property \Twilio\Rest\IpMessaging\V1\Service\Channel\MemberList members
 * @property \Twilio\Rest\IpMessaging\V1\Service\Channel\MessageList messages
 * @method \Twilio\Rest\IpMessaging\V1\Service\Channel\MemberContext members(string $sid)
 * @method \Twilio\Rest\IpMessaging\V1\Service\Channel\MessageContext messages(string $sid)
 */
class ChannelContext extends InstanceContext {
    protected $_members = null;
    protected $_messages = null;

    /**
     * Initialize the ChannelContext
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid The service_sid
     * @param string $sid The sid
     * @return \Twilio\Rest\IpMessaging\V1\Service\ChannelContext 
     */
    public function __construct(Version $version, $serviceSid, $sid) {
        parent::__construct($version);
        
        // Path Solution
        $this->solution = array(
            'serviceSid' => $serviceSid,
            'sid' => $sid,
        );
        
        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Channels/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a ChannelInstance
     * 
     * @return ChannelInstance Fetched ChannelInstance
     */
    public function fetch() {
        $params = Values::of(array());
        
        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );
        
        return new ChannelInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sid']
        );
    }

    /**
     * Deletes the ChannelInstance
     * 
     * @return boolean True if delete succeeds, false otherwise
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the ChannelInstance
     * 
     * @param array|Options $options Optional Arguments
     * @return ChannelInstance Updated ChannelInstance
     */
    public function update($options = array()) {
        $options = new Values($options);
        
        $data = Values::of(array(
            'FriendlyName' => $options['friendlyName'],
            'UniqueName' => $options['uniqueName'],
            'Attributes' => $options['attributes'],
            'Type' => $options['type'],
        ));
        
        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );
        
        return new ChannelInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sid']
        );
    }

    /**
     * Access the members
     * 
     * @return \Twilio\Rest\IpMessaging\V1\Service\Channel\MemberList 
     */
    protected function getMembers() {
        if (!$this->_members) {
            $this->_members = new MemberList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }
        
        return $this->_members;
    }

    /**
     * Access the messages
     * 
     * @return \Twilio\Rest\IpMessaging\V1\Service\Channel\MessageList 
     */
    protected function getMessages() {
        if (!$this->_messages) {
            $this->_messages = new MessageList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }
        
        return $this->_messages;
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
        return '[Twilio.IpMessaging.V1.ChannelContext ' . implode(' ', $context) . ']';
    }
}
