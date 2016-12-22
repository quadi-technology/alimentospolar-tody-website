<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Chat\V1\Service;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class ChannelTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));
        
        try {
            $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                   ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}
        
        $this->assertRequest(new Request(
            'get',
            'https://chat.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "d816d8da-51c0-44e1-928a-44822f49bc95",
                "unique_name": "c64ad6b0-0090-4cfc-b574-b1ce5208ac0b",
                "attributes": null,
                "type": "public",
                "date_created": "2015-12-16T22:18:37Z",
                "date_updated": "2015-12-16T22:18:37Z",
                "created_by": "system",
                "url": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "members": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Members",
                    "messages": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Messages"
                }
            }
            '
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->fetch();
        
        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));
        
        try {
            $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                   ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}
        
        $this->assertRequest(new Request(
            'delete',
            'https://chat.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->delete();
        
        $this->assertTrue($actual);
    }

    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));
        
        try {
            $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                   ->channels->create();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}
        
        $this->assertRequest(new Request(
            'post',
            'https://chat.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels'
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "d816d8da-51c0-44e1-928a-44822f49bc95",
                "unique_name": "c64ad6b0-0090-4cfc-b574-b1ce5208ac0b",
                "attributes": null,
                "type": "public",
                "date_created": "2015-12-16T22:18:37Z",
                "date_updated": "2015-12-16T22:18:37Z",
                "created_by": "system",
                "url": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "members": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Members",
                    "messages": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Messages"
                }
            }
            '
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels->create();
        
        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));
        
        try {
            $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                   ->channels->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}
        
        $this->assertRequest(new Request(
            'get',
            'https://chat.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "channels": [
                    {
                        "sid": "CHc12e6f1419b244fe8da312bc2cdebebc",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "service_sid": "IS034e4a0c83f94e10a2ab4a3c19a16a86",
                        "friendly_name": "d816d8da-51c0-44e1-928a-44822f49bc95",
                        "unique_name": "c64ad6b0-0090-4cfc-b574-b1ce5208ac0b",
                        "attributes": null,
                        "type": "public",
                        "date_created": "2015-12-16T22:18:37Z",
                        "date_updated": "2015-12-16T22:18:37Z",
                        "created_by": "system",
                        "url": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels/CHc12e6f1419b244fe8da312bc2cdebebc",
                        "links": {
                            "members": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels/CHc12e6f1419b244fe8da312bc2cdebebc/Members",
                            "messages": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels/CHc12e6f1419b244fe8da312bc2cdebebc/Messages"
                        }
                    }
                ],
                "meta": {
                    "page": 0,
                    "page_size": 1,
                    "first_page_url": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels?PageSize=1&Page=0",
                    "previous_page_url": null,
                    "url": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels?PageSize=1&Page=0",
                    "next_page_url": null,
                    "key": "channels"
                }
            }
            '
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels->read();
        
        $this->assertGreaterThan(0, count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "channels": [],
                "meta": {
                    "page": 0,
                    "page_size": 1,
                    "first_page_url": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels?PageSize=1&Page=0",
                    "previous_page_url": null,
                    "url": "https://ip-messaging.twilio.com/v1/Services/IS034e4a0c83f94e10a2ab4a3c19a16a86/Channels?PageSize=1&Page=0",
                    "next_page_url": null,
                    "key": "channels"
                }
            }
            '
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels->read();
        
        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));
        
        try {
            $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                   ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}
        
        $this->assertRequest(new Request(
            'post',
            'https://chat.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
        ));
    }

    public function testUpdateResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "d816d8da-51c0-44e1-928a-44822f49bc95",
                "unique_name": "c64ad6b0-0090-4cfc-b574-b1ce5208ac0b",
                "attributes": null,
                "type": "public",
                "date_created": "2015-12-16T22:18:37Z",
                "date_updated": "2015-12-16T22:18:37Z",
                "created_by": "system",
                "url": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "members": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Members",
                    "messages": "https://ip-messaging.twilio.com/v1/Services/ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Messages"
                }
            }
            '
        ));
        
        $actual = $this->twilio->chat->v1->services("ISaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->channels("CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")->update();
        
        $this->assertNotNull($actual);
    }
}
