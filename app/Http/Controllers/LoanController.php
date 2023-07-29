<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanProgram; // Assuming the model for loan programs is LoanProgram

class LoanController extends Controller
{
    /**
     * Display a listing of the loan programs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanPrograms = LoanProgram::all();
        return response()->json($loanPrograms);
    }

    /**
     * Store a newly created loan program in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'program_name' => 'required|string|max:100',
            'target_min_credit_score' => 'required|integer',
            'target_min_income' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'min_loan_amount' => 'required|numeric',
            'max_loan_amount' => 'required|numeric',
            'max_loan_term' => 'required|integer',
        ]);

        // Create the loan program
        $loanProgram = LoanProgram::create($validatedData);

        return response()->json($loanProgram, 201); // Return the created loan program with HTTP status 201 (Created)
    }

    /**
     * Display the specified loan program.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loanProgram = LoanProgram::findOrFail($id);
        return response()->json($loanProgram);
    }

    /**
     * Update the specified loan program in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'program_name' => 'required|string|max:100',
            'target_min_credit_score' => 'required|integer',
            'target_min_income' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'min_loan_amount' => 'required|numeric',
            'max_loan_amount' => 'required|numeric',
            'max_loan_term' => 'required|integer',
        ]);

        // Find the loan program and update its attributes
        $loanProgram = LoanProgram::findOrFail($id);
        $loanProgram->update($validatedData);

        return response()->json($loanProgram, 200); // Return the updated loan program with HTTP status 200 (OK)
    }

    /**
 * Get recommended loan programs based on user's financial data.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function getLoanRecommendations(Request $request)
{
    // Assuming the user's financial data is provided in the request
    $userCreditScore = $request->input('credit_score');
    $userIncome = $request->input('income');

    // Fetch loan programs from the database
    $loanPrograms = LoanProgram::all();

    // Calculate the composite suitability score for each loan program
    $loanPrograms = $loanPrograms->map(function ($loanProgram) use ($userCreditScore, $userIncome) {
        // Calculate the score for credit score suitability
        $creditScoreScore = max(0, 100 - abs($loanProgram->target_min_credit_score - $userCreditScore));

        // Calculate the score for income suitability
        $incomeScore = max(0, 100 - ($loanProgram->target_min_income - $userIncome));

        // Calculate the score for interest rate suitability
        $interestRateScore = max(0, 100 - ($loanProgram->interest_rate * 10)); // Assuming interest rate is in decimal format

        // Calculate the score for loan amount suitability
        $loanAmountScore = max(0, 100 - (($loanProgram->max_loan_amount - $userIncome) / 1000)); // Normalize the difference

        // Calculate the overall suitability score as a weighted average
        $overallScore = ($creditScoreScore * 0.3) + ($incomeScore * 0.2) + ($interestRateScore * 0.25) + ($loanAmountScore * 0.25);

        $loanProgram->suitability_score = $overallScore;

        return $loanProgram;
    });

    // Sort loan programs by suitability score in descending order (best to worst)
    $recommendedLoanPrograms = $loanPrograms->sortByDesc('suitability_score');

    // Return the recommended loan programs as a JSON response
    return response()->json($recommendedLoanPrograms);
}


    /**
     * Remove the specified loan program from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loanProgram = LoanProgram::findOrFail($id);
        $loanProgram->delete();

        return response()->json(null, 204); // Return an empty response with HTTP status 204 (No Content)
    }
}
