<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
    // Default sorting
    $sortColumn = $request->get('sort_by', 'name');
    $sortDirection = $request->get('direction', 'asc');

    // Validate sorting parameters
    $validSortColumns = ['name', 'created_at'];
    if (!in_array($sortColumn, $validSortColumns)) {
        $sortColumn = 'name';
    }

    if (!in_array($sortDirection, ['asc', 'desc'])) {
        $sortDirection = 'asc';
    }

    // Fetch contacts with sorting and searching
    $query = Contact::query();

    // Apply search filter if search term is present
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
        });
    }

    $contacts = $query->orderBy($sortColumn, $sortDirection)->paginate(10);

    return view('contacts.index', compact('contacts', 'sortColumn', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Create a new contact using the validated data
        Contact::create($validatedData);

        // Redirect to the contacts list page with a success message
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified resource.
     */
   
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.show', compact('contact'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
    
        // Find the contact by ID
        $contact = Contact::findOrFail($id);
    
        // Update the contact with the validated data
        $contact->update($validatedData);
    
        // Redirect to the contacts index page with a success message
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the contact by ID
        $contact = Contact::findOrFail($id);

        // Delete the contact
        $contact->delete();

        // Redirect to the contacts index page with a success message
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
