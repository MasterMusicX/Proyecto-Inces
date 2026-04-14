<?php
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {
    // Chatbot
    Route::post('/chatbot/message',             [ChatbotController::class, 'sendMessage']    );
    Route::get ('/chatbot/conversations',        [ChatbotController::class, 'getConversations']);
    Route::get ('/chatbot/conversations/{id}',   [ChatbotController::class, 'getHistory']    );
    Route::post('/chatbot/rate/{queryId}',       [ChatbotController::class, 'rateResponse']  );
    Route::delete('/chatbot/conversations/{id}', [ChatbotController::class, 'deleteConversation']);

    // Search
    Route::get('/search', [SearchController::class, 'search']);

    // Document AI
    Route::get ('/documents/{resource}/analysis',  [DocumentController::class, 'analysis']       );
    Route::post('/documents/{resource}/ask',        [DocumentController::class, 'askAboutDocument']);
    Route::get ('/documents/{resource}/summarize',  [DocumentController::class, 'summarize']      );
});
