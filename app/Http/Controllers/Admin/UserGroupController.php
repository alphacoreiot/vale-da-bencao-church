<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function index()
    {
        $groups = UserGroup::withCount('users')->orderBy('created_at', 'desc')->get();
        return view('admin.user-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.user-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        UserGroup::create($validated);

        return redirect()->route('admin.user-groups.index')->with('success', 'Grupo criado com sucesso!');
    }

    public function edit(UserGroup $userGroup)
    {
        return view('admin.user-groups.edit', compact('userGroup'));
    }

    public function update(Request $request, UserGroup $userGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $userGroup->update($validated);

        return redirect()->route('admin.user-groups.index')->with('success', 'Grupo atualizado com sucesso!');
    }

    public function destroy(UserGroup $userGroup)
    {
        if ($userGroup->users()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um grupo com usuários.');
        }

        $userGroup->delete();

        return redirect()->route('admin.user-groups.index')->with('success', 'Grupo excluído com sucesso!');
    }
}
