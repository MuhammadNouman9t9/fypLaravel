<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\AddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AddressController extends Controller
{
    public function store(AddressRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $this->prepareData($request);

        $address = $user->addresses()->create($data);

        if (! $data['is_primary'] && ! $user->addresses()->where('is_primary', true)->exists()) {
            $address->is_primary = true;
            $address->save();
            $data['is_primary'] = true;
        }

        if ($data['is_primary']) {
            $this->syncPrimary($user->id, $address->id);
        }

        return Redirect::route('profile.edit')
            ->with('status', 'address-created')
            ->with('address_form', 'new');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $data = $this->prepareData($request);

        $address->update($data);

        if ($data['is_primary']) {
            $this->syncPrimary($request->user()->id, $address->id);
        } elseif (! $request->user()->addresses()->where('is_primary', true)->exists()) {
            $address->update(['is_primary' => true]);
        }

        return Redirect::route('profile.edit')
            ->with('status', 'address-updated')
            ->with('address_form', 'edit-'.$address->getKey());
    }

    public function destroy(Request $request, Address $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $wasPrimary = $address->is_primary;
        $address->delete();

        if ($wasPrimary) {
            $nextAddress = $request->user()->addresses()->latest()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_primary' => true]);
            }
        }

        return Redirect::route('profile.edit')
            ->with('status', 'address-deleted')
            ->with('address_form', 'list');
    }

    private function authorizeAddress(Request $request, Address $address): void
    {
        abort_unless($address->user_id === $request->user()->id, 404);
    }

    private function syncPrimary(int $userId, int $primaryId): void
    {
        Address::query()
            ->where('user_id', $userId)
            ->whereKeyNot($primaryId)
            ->update(['is_primary' => false]);
    }

    private function prepareData(AddressRequest $request): array
    {
        $data = $request->validated();
        $data['is_primary'] = $request->boolean('is_primary');
        $data['country_code'] = strtoupper($data['country_code'] ?? 'US');
        $data['type'] = 'shipping';
        
        // Split full_name into first_name and last_name
        if ($request->has('full_name')) {
            $fullName = trim($request->input('full_name'));
            $nameParts = explode(' ', $fullName, 2);
            $data['first_name'] = $nameParts[0] ?? '';
            $data['last_name'] = $nameParts[1] ?? '';
        }
        
        // Store additional notes in meta field
        if ($request->has('additional_notes')) {
            $data['meta'] = ['additional_notes' => $request->input('additional_notes')];
        }
        
        // Remove fields that don't exist in database
        unset($data['full_name'], $data['additional_notes']);

        return $data;
    }
}
