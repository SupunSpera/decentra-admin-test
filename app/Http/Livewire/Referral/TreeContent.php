<?php

namespace App\Http\Livewire\Referral;

use domain\Facades\ReferralFacade;
use Livewire\Component;

class TreeContent extends Component
{
    public $node;
    public $level;
    public $maxDepth; // How many levels deep to show from this node
    public $childrenLoaded = false;
    public $leftChild = null;
    public $rightChild = null;

    public function mount($maxDepth = 5)
    {
        $this->maxDepth = $maxDepth;

        // Load children if within depth limit
        if ($this->level < $this->maxDepth) {
            $this->loadChildren();
        }
    }

    public function loadChildren()
    {
        if ($this->childrenLoaded) {
            return;
        }

        \Log::info("🔄 Loading children for node {$this->node->id} at level {$this->level}");

        // OPTIMIZATION: Batch load both children in single query
        $childIds = array_filter([
            $this->node->left_child_id,
            $this->node->right_child_id
        ]);

        if (empty($childIds)) {
            $this->childrenLoaded = true;
            return;
        }

        \Log::info("📥 Batch database query: Fetching children " . implode(', ', $childIds));

        // Single query for all children with eager loading
        $children = \App\Models\Referral::with('customer')
            ->whereIn('id', $childIds)
            ->get([
                'id',
                'customer_id',
                'left_child_id',
                'right_child_id',
                'left_children_count',
                'right_children_count',
                'left_points',
                'right_points',
                'level'
            ])
            ->keyBy('id');

        // Assign to left/right with counter cache totals
        if ($this->node->left_child_id && isset($children[$this->node->left_child_id])) {
            $this->leftChild = $children[$this->node->left_child_id];
            $this->leftChild->leftTotal = $this->leftChild->left_children_count ?? 0;
            $this->leftChild->rightTotal = $this->leftChild->right_children_count ?? 0;
        }

        if ($this->node->right_child_id && isset($children[$this->node->right_child_id])) {
            $this->rightChild = $children[$this->node->right_child_id];
            $this->rightChild->leftTotal = $this->rightChild->left_children_count ?? 0;
            $this->rightChild->rightTotal = $this->rightChild->right_children_count ?? 0;
        }

        $this->childrenLoaded = true;
        \Log::info("✅ Children loaded for node {$this->node->id} (batch: 2 queries instead of 4)");
    }

    public function render()
    {
        return view('pages.referrals.tree-node');
    }
}
