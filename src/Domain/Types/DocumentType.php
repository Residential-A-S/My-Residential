<?php

namespace Domain\Types;

enum DocumentType: string
{
    case LEASE = 'lease';
    case INSPECTION_REPORT = 'inspection_report';
}
