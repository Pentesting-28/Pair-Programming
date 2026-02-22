<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeSwitcherController extends Controller
{
    /**
     * Switch the demonstration mode.
     *
     * @param  string  $mode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(string $mode)
    {
        if (in_array($mode, ['mvc', 'livewire'])) {
            Session::put('demo_mode', $mode);
        }

        return match ($mode) {
            'mvc' => redirect()->route('mvc.dashboard'),
            'livewire' => redirect()->route('livewire.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
