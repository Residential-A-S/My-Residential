<?php

namespace Domain\Types;

enum IssueStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';
}
