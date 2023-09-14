<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Rules\PhoneRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Contacts retrieved successfully',
            'data' => auth()->user()->contacts
        ],200);
    }
    public function store(ContactRequest $request)
    {
        try {
            $contact = Contact::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'user_id' => auth()->id(),
                'slug' => Str::uuid()
            ]);
            return response()->json(['message' => 'New contact added...','data' => $contact],201);
        } catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        try {
            $contact->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
            ]);
            return response()->json(['message' => 'Contact updated','data' => $contact]);
        } catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return response()->json(['message' => 'Contact deleted','data' => $contact]);
        } catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
