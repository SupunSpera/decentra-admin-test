<?php

namespace App\Http\Livewire\Referral;

use domain\Facades\ReferralFacade;
use Livewire\Component;

class TreeContent extends Component
{
    public $node, $level, $maxLevel, $lastLevel;

    protected $listeners = ['showChildren'];

    public function mount()
    {
        $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit(10);

        $this->maxLevel = session('maxLevel', 5); // Retrieve from session or default to 5
        $this->lastLevel = ReferralFacade::getMaxLevel();
    }

    public function render()
    {
        return view('pages.referrals.tree-node');
    }

    public function showChildren()
    {
        $this->maxLevel += 5;
        session(['maxLevel' => $this->maxLevel]); // Store updated value in session
    }
}
