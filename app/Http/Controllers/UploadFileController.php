<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        if ($request->file('F1')) {
            $request->file('F1')->storeAs('upload/F1', 'F1');
        }

        if ($request->file('F2')) {
            $request->file('F2')->storeAs('upload/F2', 'F2');
        }

        if ($request->file('F3')) {
            $request->file('F3')->storeAs('upload/F3', 'F3');
        }

        if ($request->file('F4')) {
            $request->file('F4')->storeAs('upload/F4', 'F4');
        }

        if ($request->file('F5')) {
            $request->file('F5')->storeAs('upload/F5', 'F5');
        }

        if ($request->file('F6')) {
            $request->file('F6')->storeAs('upload/F6', 'F6');
        }

        if ($request->file('F7')) {
            $request->file('F7')->storeAs('upload/F7', 'F7');
        }

        if ($request->file('F8')) {
            $request->file('F8')->storeAs('upload/F8', 'F8');
        }

        return redirect()->back();
    }
}
