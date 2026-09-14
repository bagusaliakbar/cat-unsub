<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use App\Models\ExamSession;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalExams = Exam::count();
        $activeExams = Exam::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_time')->orWhere('start_time', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_time')->orWhere('end_time', '>=', now());
            })->count();
            
        $totalQuestions = Question::count();
        $totalParticipants = User::where('role', 'participant')->count();
        
        $recentActivities = ExamSession::with(['user', 'exam'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get();

        // CHART 1: 7-Day Activity Trend
        $dates = [];
        $activityCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $dates[] = $date->format('d M');
            $activityCounts[] = ExamSession::whereDate('started_at', $date->format('Y-m-d'))->count();
        }
        $activityChart = [
            'categories' => $dates,
            'series' => $activityCounts
        ];

        // CHART 2: Average Score per Exam (Top 5 Recent)
        $recentExamsForChart = Exam::whereHas('sessions', function($q) {
                $q->where('status', 'completed');
            })
            ->withAvg('sessions', 'score')
            ->latest()
            ->take(5)
            ->get()
            ->reverse() // chronological order
            ->values();

        $scoreChart = [
            'categories' => $recentExamsForChart->pluck('title')->map(function($title) {
                return strlen($title) > 15 ? substr($title, 0, 15) . '...' : $title;
            })->toArray(),
            'series' => $recentExamsForChart->pluck('sessions_avg_score')->map(function($score) {
                return round($score, 2);
            })->toArray()
        ];

        // CHART 3: Status Distribution
        $completedCount = ExamSession::where('status', 'completed')->count();
        $startedCount = ExamSession::where('status', 'started')->count();
        $statusChart = [
            'labels' => ['Selesai', 'Sedang Mengerjakan'],
            'series' => [$completedCount, $startedCount]
        ];

        return view('livewire.admin.dashboard', compact(
            'totalExams', 
            'activeExams', 
            'totalQuestions', 
            'totalParticipants',
            'recentActivities',
            'activityChart',
            'scoreChart',
            'statusChart'
        ))->layout('layouts.app');
    }
}
