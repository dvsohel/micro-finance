<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('member_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('nid', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Member/Index', [
            'members' => $members,
            'filters' => $request->only('search', 'status'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Member/Create');
    }

    public function store(StoreMemberRequest $request)
    {

        // return $request->all();
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('members', 'public');
        }

        Member::create($validated);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    public function show(string $id)
    {
        $member = Member::findOrFail($id);

        return Inertia::render('Member/Show', [
            'member' => $member,
        ]);
    }

    public function edit(string $id)
    {
        $member = Member::findOrFail($id);

        return Inertia::render('Member/Edit', [
            'member' => $member,
        ]);
    }

    public function update(UpdateMemberRequest $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('members', 'public');
        }

        $member->update($validated);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);

        // Delete member photo
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        // Soft delete member
        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
