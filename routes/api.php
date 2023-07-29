<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// User Authentication and Profile
Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::get('/user/profile', 'UserController@show');
Route::put('/user/profile', 'UserController@update');

// Financial Profile and Credit Score
Route::get('/financial/profile', 'FinancialController@show');
Route::post('/financial/profile', 'FinancialController@update');
Route::get('/credit/score', 'FinancialController@getCreditScore');

// Loan Recommendations
Route::post('/loan/recommendations', 'App\Http\Controllers\LoanController@getLoanRecommendations');
Route::get('/loan/all', 'App\Http\Controllers\LoanController@index');
Route::get('/loan/programs', 'LoanController@index');

// Loan Applications
Route::post('/loan/apply', 'LoanController@store');
Route::get('/loan/applications', 'LoanController@getUserLoanApplications');
Route::get('/loan/application/{id}', 'LoanController@showLoanApplication');

// Reviews and Ratings
Route::post('/loan/review', 'LoanController@storeLoanReview');
Route::get('/loan/reviews', 'LoanController@getLoanReviews');

// Financial Education and Resources
Route::get('/resources/tips', 'FinancialController@getFinancialTips');
Route::get('/resources/webinars', 'FinancialController@getFinancialWebinars');

// Collaboration Features
Route::get('/users', 'UserController@index');
Route::post('/referral', 'ReferralController@store');
Route::get('/forums', 'ForumController@index');
Route::post('/forums/{id}/comment', 'ForumController@storeComment');
Route::get('/challenges', 'ChallengeController@index');

// Account Management and Transactions
Route::get('/accounts', 'UserController@getUserAccounts');
Route::get('/transactions', 'UserController@getUserAccountTransactions');
Route::post('/transfer', 'UserController@initiateFundTransfer');

// Security and Privacy
Route::post('/change_password', 'UserController@changePassword');
Route::post('/logout', 'UserController@logout');
