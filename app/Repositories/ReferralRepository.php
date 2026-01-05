<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

/**
 * ReferralRepository
 *
 * Handles all database queries for referral tree operations
 * Keeps SQL logic separate from business logic
 */
class ReferralRepository
{
    /**
     * Get all descendants of a referral using recursive CTE
     *
     * This is the ONLY place this complex SQL lives
     * Easier to test, maintain, and optimize
     *
     * @param int $childId - Starting child ID
     * @return array - Array of descendant IDs
     */
    public function getDescendants(int $childId): array
    {
        $query = "
            WITH RECURSIVE child_tree AS (
                -- Base case: start with the given child
                SELECT
                    id,
                    parent_referral_id,
                    left_child_id,
                    right_child_id,
                    customer_id
                FROM referrals
                WHERE id = ?

                UNION ALL

                -- Recursive case: get all children of current level
                SELECT
                    r.id,
                    r.parent_referral_id,
                    r.left_child_id,
                    r.right_child_id,
                    r.customer_id
                FROM referrals r
                INNER JOIN child_tree ct ON r.parent_referral_id = ct.id
            )
            SELECT id FROM child_tree
        ";

        $results = DB::select($query, [$childId]);

        return array_column($results, 'id');
    }

    /**
     * Get all descendants with full data (not just IDs)
     *
     * @param int $childId
     * @return array
     */
    public function getDescendantsWithData(int $childId): array
    {
        $query = "
            WITH RECURSIVE child_tree AS (
                SELECT
                    id,
                    parent_referral_id,
                    left_child_id,
                    right_child_id,
                    customer_id,
                    level,
                    level_index
                FROM referrals
                WHERE id = ?

                UNION ALL

                SELECT
                    r.id,
                    r.parent_referral_id,
                    r.left_child_id,
                    r.right_child_id,
                    r.customer_id,
                    r.level,
                    r.level_index
                FROM referrals r
                INNER JOIN child_tree ct ON r.parent_referral_id = ct.id
            )
            SELECT * FROM child_tree
        ";

        return DB::select($query, [$childId]);
    }

    /**
     * Get count of descendants (faster than fetching all)
     *
     * @param int $childId
     * @return int
     */
    public function getDescendantsCount(int $childId): int
    {
        $query = "
            WITH RECURSIVE child_tree AS (
                SELECT id
                FROM referrals
                WHERE id = ?

                UNION ALL

                SELECT r.id
                FROM referrals r
                INNER JOIN child_tree ct ON r.parent_referral_id = ct.id
            )
            SELECT COUNT(*) as total FROM child_tree
        ";

        $result = DB::selectOne($query, [$childId]);

        return $result->total ?? 0;
    }

    /**
     * Get descendants up to specific depth
     *
     * @param int $childId
     * @param int $maxDepth
     * @return array
     */
    public function getDescendantsWithDepthLimit(int $childId, int $maxDepth = 10): array
    {
        $query = "
            WITH RECURSIVE child_tree AS (
                SELECT
                    id,
                    parent_referral_id,
                    1 as depth
                FROM referrals
                WHERE id = ?

                UNION ALL

                SELECT
                    r.id,
                    r.parent_referral_id,
                    ct.depth + 1
                FROM referrals r
                INNER JOIN child_tree ct ON r.parent_referral_id = ct.id
                WHERE ct.depth < ?
            )
            SELECT id FROM child_tree
        ";

        $results = DB::select($query, [$childId, $maxDepth]);

        return array_column($results, 'id');
    }
}
