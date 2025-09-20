<?php

namespace Application\Port;

use Domain\Exception\IssueException;
use src\Entity\Issue;

interface IssueRepository
{
    /**
     * Find an issue by its ID.
     * Throws an IssueException if the issue is not found.
     * @throws IssueException
     */
    public function findById(int $id): Issue;

    /**
     * Find all issues.
     * @return Issue[]
     */
    public function findAll(): array;

    /**
     * Create a new issue.
     * @throws IssueException
     */
    public function save(Issue $issue): Issue;

    /**
     * Delete an issue by its ID.
     * Throws an IssueException if the issue is not found.
     * @throws IssueException
     */
    public function delete(int $id): void;
}