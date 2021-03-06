<?php

namespace App\Http\Controllers;

use App\LoanRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLoanRequest;

class LoanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ambil semua loan request untuk user yang login. paginasi 3 item per halaman
        $loanRequests = auth()->user()->loanRequests()->paginate(3);

        // tampilkan view listing loan request, passing variable $loanRequest yang didapat sebelumnya
        return view('loan-request.index', compact('loanRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', LoanRequest::class);
        // tampilkan form pembuatan loan request
        return view('loan-request.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoanRequest $request)
    {
        $this->authorize('create', LoanRequest::class);
        // mengambil data form
        $payload = $request->only('amount', 'duration', 'is_submitted') + ['member_id' => auth()->user()->id];
        // membuat record di db
        LoanRequest::create($payload);

        // redirect user ke halaman list loan-request
        return redirect()->route('loan-requests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function show(LoanRequest $loanRequest)
    {
        $this->authorize('view', $loanRequest);

        // Tampilkan view untuk detail loan request
        return view('loan-request.show', compact('loanRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanRequest $loanRequest)
    {
        $this->authorize('update', $loanRequest);
        // tampilkan form edit
        return view('loan-request.edit', compact('loanRequest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLoanRequest $request, LoanRequest $loanRequest)
    {
        $this->authorize('update', $loanRequest);
        // mengambil data form
        $payload = $request->only('amount', 'duration', 'is_submitted');
        // update record di db
        $loanRequest->update($payload);

        // redirect user ke halaman list loan-request
        return redirect()->route('loan-requests.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanRequest $loanRequest)
    {
        $this->authorize('delete', $loanRequest);
        // hapus loan request yang didapatkan berdasarkan id loan request di URL
        $loanRequest->delete();

        // redirect user ke halaman listing loan request dengan paginasi sebelumnya
        return redirect()->route('loan-requests.index', ['page' => request()->get('page')]);
    }
}
