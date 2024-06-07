<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TablerSwitchLayout extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->has('layout'))
            session(['backpack.theme-tabler.layout' => $request->layout]);

        return redirect()->back();
    }
}
