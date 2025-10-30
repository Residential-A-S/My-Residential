<?php

namespace Application\Port;

use Domain\Entity\Issue;
use Domain\ValueObject\IssueId;

/**
 *
 */
interface IssueRepository
{
    /**
     * Find an issue by its ID.
     *
     * @param IssueId $id
     *
     * @return Issue
     */
    public function findById(IssueId $id): Issue;

    /**
     * Find all issues.
     * @return Issue[]
     */
    public function findAll(): array;

    /**
     * Create a new issue.
     * @param Issue $issue
     */
    public function save(Issue $issue): void;

    /**
     * Delete an issue by its ID.
     * @param IssueId $id
     */
    public function delete(IssueId $id): void;
}