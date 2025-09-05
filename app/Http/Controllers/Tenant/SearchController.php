<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class SearchController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:tenant')->only(['index']);
    }
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (!$q) {
            return response()->json([]);
        }

        $results = [];

        // 🔹 Search Users
        $users = User::where('full_name', 'LIKE', "%{$q}%")
            ->orWhere('email', 'LIKE', "%{$q}%")
            ->limit(5)
            ->get(['id', 'full_name as name', 'email']);

        foreach ($users as $u) {
            $results[] = [
                'id' => $u->id,
                'kind' => 'Staff',
                'name' => $u->name,
            ];
        }

        // 🔹 Search Students
        $students = Student::where('full_name', 'LIKE', "%{$q}%")
            ->limit(5)
            ->get(['id', 'full_name']);

        foreach ($students as $s) {
            $results[] = [
                'id' => $s->id,
                'kind' => 'Student',
                'name' => trim($s->first_name . ' ' . $s->last_name),
            ];
        }

        return response()->json($results);
    }
}
