    <li>
        <a href="javascript:void(0);">
            <div class="member-view-box">
                <div class="member-image">
                    <i class="fas fa-user text-gray-300"></i>
                    <div class="member-details">

                        @if ($node->customer->first_name && $node->customer->last_name)
                            <h3>
                                {{ $node->customer->first_name }} {{ $node->customer->last_name }}
                                <a href="/referrals/tree-view/{{ $node->id }}" 
                                   class="text-primary ml-2" 
                                   title="View this user's tree"
                                   style="font-size: 0.8em;">
                                    <i class="fas fa-level-down-alt"></i>
                                </a>
                            </h3>
                        @else
                            <h3>
                                {{ $level == 1 ? ($node->email ?? $node->customer->email) : $node->customer->email }}
                                <a href="/referrals/tree-view/{{ $node->id }}" 
                                   class="text-primary ml-2" 
                                   title="View this user's tree"
                                   style="font-size: 0.8em;">
                                    <i class="fas fa-level-down-alt"></i>
                                </a>
                            </h3>
                        @endif

                        <p>{{ $node->customer->referral_code }}</p>

                        @if ($level == 1)
                            <p>({{ $node->id }}) / {{ $node->leftTotal ?? 0 }} | {{ $node->rightTotal ?? 0 }}</p>
                        @else
                            <p>({{ $node->id }}) / {{ $node->left_points ?? 0 }} | {{ $node->right_points ?? 0 }}</p>
                        @endif

                        {{-- Show Load More button if has children but not loaded
                        @if (($node->left_child_id || $node->right_child_id) && $level >= $maxDepth)
                            <button wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore" class="btn btn-sm btn-primary mt-2">
                                <span wire:loading.remove wire:target="loadMore">Load More</span>
                                <span wire:loading wire:target="loadMore" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                </span>
                            </button>
                        @endif --}}
                    </div>
                </div>
            </div>
        </a>

        {{-- Show children if within depth limit and loaded --}}
        @if ($childrenLoaded && ($leftChild || $rightChild))
            <ul>
                @if ($leftChild)
                    <livewire:referral.tree-content
                        :node="$leftChild"
                        :level="$level + 1"
                        :maxDepth="$maxDepth"
                        :key="'left-' . $leftChild->id"
                    />
                @else
                    <li><div class="member-view-box empty-node"></div></li>
                @endif

                @if ($rightChild)
                    <livewire:referral.tree-content
                        :node="$rightChild"
                        :level="$level + 1"
                        :maxDepth="$maxDepth"
                        :key="'right-' . $rightChild->id"
                    />
                @else
                    <li><div class="member-view-box empty-node"></div></li>
                @endif
            </ul>
        @endif
    </li>
