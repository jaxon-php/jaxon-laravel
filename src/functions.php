<?php

/**
 * Set event handlers
 *
 * @param array $events
 *
 * @return string
 */
function setJxnEvent(array $events): string
{
    return isset($events[0]) && is_array($events[0]) ?
        attr()->events($events) : attr()->event($events);
}
