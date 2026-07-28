<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalStudents    = Student::count();
        $totalSubjects    = Subject::count();
        $totalAttendances = Attendance::count();
        $todayAttendances = Attendance::whereDate('attendance_time', Carbon::today())->count();
        $activeSessions   = AttendanceSession::whereNull('ended_at')->count();

        $recentSessions = AttendanceSession::with('subject')
            ->withCount('attendances')
            ->latest('started_at')
            ->paginate(5, ['*'], 'sessions_page');

        $recentAttendances = Attendance::with(['student', 'subject', 'session'])
            ->latest('attendance_time')
            ->paginate(5, ['*'], 'attendances_page');

        // NEW: recently registered students
        $recentStudents = Student::latest()->take(5)->get();

        // NEW: how many students joined in the last 7 days (used on a stat card)
        $newStudentsThisWeek = Student::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $registrationSetting = RegistrationSetting::first();

        // Weekly attendance (last 7 days) — feeds the bar chart
        $start = Carbon::today()->subDays(6)->startOfDay();
        $end   = Carbon::today()->endOfDay();

        $raw = Attendance::selectRaw('DATE(attendance_time) as date, count(*) as count')
            ->whereBetween('attendance_time', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $weeklyLabels = [];
        $weeklyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i)->toDateString();
            $weeklyLabels[] = $d;
            $weeklyValues[] = isset($raw[$d]) ? (int) $raw[$d] : 0;
        }

        // NEW: subject-wise attendance distribution — feeds the pie chart
        $subjectDistributionRaw = Attendance::orderByDesc('id')
            ->with('subject:id,name')
            ->take(6)
            ->get();

        $subjectLabels = $subjectDistributionRaw->map(fn($row) => $row->subject?->name ?? 'Unknown')->values();
        $subjectValues = $subjectDistributionRaw->pluck('count')->values();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'totalAttendances',
            'todayAttendances',
            'activeSessions',
            'recentSessions',
            'recentAttendances',
            'recentStudents',
            'newStudentsThisWeek',
            'registrationSetting',
            'weeklyLabels',
            'weeklyValues',
            'subjectLabels',
            'subjectValues'
        ));
    }

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function registrationSettings()
    {
        $setting = RegistrationSetting::first();

        return view(
            'admin.registration-settings.index',
            compact('setting')
        );
    }

    public function updateRegistrationSettings(Request $request)
    {
        $request->validate([
            'status' => [
                'required',
                'in:active,inactive'
            ],
        ]);


        RegistrationSetting::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'status' => $request->status
            ]
        );


        return back()
            ->with('success', 'Registration status updated successfully.');
    }

    public function updateRegisterSimilarity(Request $request)
    {
        $request->validate([
            'similarity_threshold' => 'required|numeric|min:0|max:1.00',
        ]);


        RegistrationSetting::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'similarity_threshold' => $request->similarity_threshold
            ]
        );


        return back()
            ->with('success', 'Similarity threshold updated successfully.');
    }
    
    public function ip_status(Request $request)
    {
        $request->validate([
            'ip_status' => [
                'required',
                'in:enable,disable'
            ],
        ]);


        RegistrationSetting::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'ip_status' => $request->ip_status
            ]
        );


        return back()
            ->with('success', 'IP Blocking status updated successfully.');
    }
    
        public function whitelist_ips(Request $request)
        {
            $request->validate([
                'whitelist_ips' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
        
                        if (blank($value)) {
                            return;
                        }
        
                        $networks = array_filter(
                            array_map('trim', preg_split('/[\r\n,]+/', $value))
                        );
        
                        foreach ($networks as $network) {
        
                            // Must be in CIDR format (e.g. 192.168.1.0/24)
                            if (!preg_match('/^(.+)\/(\d{1,2})$/', $network, $matches)) {
                                $fail("'{$network}' is not a valid CIDR notation.");
                                continue;
                            }
        
                            $ip = $matches[1];
                            $prefix = (int) $matches[2];
        
                            // IPv4 only
                            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                $fail("'{$network}' is not a valid IPv4 network.");
                                continue;
                            }
        
                            // CIDR prefix must be between 0 and 32
                            if ($prefix < 0 || $prefix > 32) {
                                $fail("'{$network}' has an invalid subnet prefix.");
                            }
                        }
                    },
                ],
            ]);
        
            $networks = array_filter(
                array_map('trim', preg_split('/[\r\n,]+/', $request->input('whitelist_ips', '')))
            );
        
            RegistrationSetting::updateOrCreate(
                ['id' => 1],
                [
                    'whitelist_ips' => implode(',', $networks),
                ]
            );
        
            return back()->with('success', 'Whitelist networks updated successfully.');
        }

    public function edit()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {

            // Delete old photo
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Upload new photo
            $validated['photo'] = $request
                ->file('photo')
                ->store('users', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->to('/')->withSuccess('Logout Success!');
    }
}
