    <li>
        <a href="javascript:void(0);">
            <div class="member-view-box">
                <div class="member-image">
                    <i class="fas fa-user  text-gray-300"></i>
                    <div class="member-details">

                        @if ($node->customer->first_name && $node->customer->last_name)
                            <h3>{{ $node->customer->first_name }} {{ $node->customer->last_name }} </h3>
                        @else
                            <h3>{{ $level == 1 ? $node->email : $node->customer->email }} </h3>
                        @endif

                        <p>{{ $node->customer->referral_code }}</p>

                        <button wire:click="showChildren" wire:loading.attr="disabled" class="btn btn-primary">Load More
                        </button>

                        @if ($level == 1)
                            <p>({{ $node->id }}) / {{ $node->leftTotal == 0 ? 0 : $node->leftTotal }} |
                                {{ $node->rightTotal == 0 ? 0 : $node->rightTotal }}</p>
                        @else
                            <p>({{ $node->id }}) / {{ $node->left_points == 0 ? 0 : $node->left_points }} |
                                {{ $node->right_points == 0 ? 0 : $node->right_points }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>

        @if (($node->left_child_id || $node->right_child_id) && $node->level < $maxLevel)
            <ul>
                @if ($node->leftChild)
                    <livewire:referral.tree-content :node="$node->leftChild" :level="$level + 1" />
                @else
                    <li>
                        <div class="member-view-box empty-node"></div>
                    </li>
                @endif
                @if ($node->rightChild)
                    <livewire:referral.tree-content :node="$node->rightChild" :level="$level + 1" />
                @else
                    <li>
                        <div class="member-view-box empty-node"></div>
                    </li>
                @endif
            </ul>
        @endif
    </li>
