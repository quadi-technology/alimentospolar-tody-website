<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace\Worker;

use Twilio\Options;
use Twilio\Values;

abstract class WorkerStatisticsOptions {
    /**
     * @param string $minutes The minutes
     * @param \DateTime $startDate The start_date
     * @param \DateTime $endDate The end_date
     * @return FetchWorkerStatisticsOptions Options builder
     */
    public static function fetch($minutes = Values::NONE, $startDate = Values::NONE, $endDate = Values::NONE) {
        return new FetchWorkerStatisticsOptions($minutes, $startDate, $endDate);
    }
}

class FetchWorkerStatisticsOptions extends Options {
    /**
     * @param string $minutes The minutes
     * @param \DateTime $startDate The start_date
     * @param \DateTime $endDate The end_date
     */
    public function __construct($minutes = Values::NONE, $startDate = Values::NONE, $endDate = Values::NONE) {
        $this->options['minutes'] = $minutes;
        $this->options['startDate'] = $startDate;
        $this->options['endDate'] = $endDate;
    }

    /**
     * The minutes
     * 
     * @param string $minutes The minutes
     * @return $this Fluent Builder
     */
    public function setMinutes($minutes) {
        $this->options['minutes'] = $minutes;
        return $this;
    }

    /**
     * The start_date
     * 
     * @param \DateTime $startDate The start_date
     * @return $this Fluent Builder
     */
    public function setStartDate($startDate) {
        $this->options['startDate'] = $startDate;
        return $this;
    }

    /**
     * The end_date
     * 
     * @param \DateTime $endDate The end_date
     * @return $this Fluent Builder
     */
    public function setEndDate($endDate) {
        $this->options['endDate'] = $endDate;
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
        return '[Twilio.Taskrouter.V1.FetchWorkerStatisticsOptions ' . implode(' ', $options) . ']';
    }
}
