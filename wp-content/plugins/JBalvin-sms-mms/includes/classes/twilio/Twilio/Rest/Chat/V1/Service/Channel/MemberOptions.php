<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Chat\V1\Service\Channel;

use Twilio\Options;
use Twilio\Values;

abstract class MemberOptions {
    /**
     * @param string $roleSid The role_sid
     * @return CreateMemberOptions Options builder
     */
    public static function create($roleSid = Values::NONE) {
        return new CreateMemberOptions($roleSid);
    }
}

class CreateMemberOptions extends Options {
    /**
     * @param string $roleSid The role_sid
     */
    public function __construct($roleSid = Values::NONE) {
        $this->options['roleSid'] = $roleSid;
    }

    /**
     * The role_sid
     * 
     * @param string $roleSid The role_sid
     * @return $this Fluent Builder
     */
    public function setRoleSid($roleSid) {
        $this->options['roleSid'] = $roleSid;
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
        return '[Twilio.Chat.V1.CreateMemberOptions ' . implode(' ', $options) . ']';
    }
}
