<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\SubscribeTransaction;

class FrontController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'teacher', 'students'])
            ->orderByDesc('id')
            ->get();
        return view('front.index', [
            'courses' => $courses
        ]);
    }

    public function details(Course $course)
    {
        return view('front.details', [
            'course' => $course
        ]);
    }

    public function learning(Course $course, $courseVideoId)
    {
        $user = Auth::user();

        if (!$user->hasActiveSubscription()) {
            return redirect()->route('front.pricing');
        };

        $video = $course->course_videos->firstWhere('id', $courseVideoId);

        // syncWithoutDetaching() digunakan untuk menambahkan data dan tidak akan membuat ID baru jika ID sebelumnya telah ada.
        $user->courses()->syncWithoutDetaching($course->id);

        return view('front.learning', [
            'course' => $course,
            'video' => $video
        ]);
    }

    public function category(Category $category)
    {
        $courses = $category->courses()->get();
        return view('front.category', [
            'courses' => $courses
        ]);
    }

    public function pricing()
    {
        if (Auth::user()->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }
        return view('front.pricing');
    }

    public function checkout()
    {
        if (Auth::user()->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }
        return view('front.checkout');
    }

    public function checkoutStore(StoreSubscribeTransactionRequest $request)
    {
        $user = Auth::user();

        if ($user->hasActiveSubscription()) {
            return redirect()->route('front.index');
        }

        DB::transaction(function () use ($request, $user) {
            $validated = $request->validated();

            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }

            $validated['user_id'] = $user->id;
            $validated['total_amount'] = 429000;
            $validated['is_paid'] = false;

            $transaction = SubscribeTransaction::create($validated);

            DB::commit();
        });

        return redirect()->route('dashboard');
    }
}
