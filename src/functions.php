<?php

namespace Jaxon\Laravel;

use function is_array;
use function Jaxon\attr;

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
