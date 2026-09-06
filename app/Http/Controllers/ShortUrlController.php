<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortUrlController extends Controller {
    public function index() {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
           $companies = Company::withCount(['users','shortUrls'])->withSum('shortUrls', 'clicks')->paginate(2, ['*'], 'companies_page');
           $shortUrls = ShortUrl::with(['user', 'company'])->latest()->paginate(3, ['*'], 'urls_page');
           return view('dashboard.superadmin', compact('companies', 'shortUrls'));
        }

        if ($user->isAdmin()) {
            $shortUrls = ShortUrl::where('company_id', $user->company_id)->with('user')->latest()->paginate(3, ['*'], 'urls_page');
            $teamMembers = User::where('company_id', $user->company_id)->withCount('shortUrls')->withSum('shortUrls', 'clicks')->paginate(2, ['*'], 'members_page'); 
            return view('dashboard.admin', compact('shortUrls', 'teamMembers'));
        }
        $shortUrls = ShortUrl::where('user_id', $user->id)->latest()->paginate(2);
        return view('dashboard.member', compact('shortUrls'));
    }

    public function store(Request $request) {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            abort(403, 'SuperAdmin cannot create short URLs.');
        }

        $validator = \Validator::make($request->all(), [
        'long_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'urlErrorBag')->withInput();
        }

        ShortUrl::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'long_url' => $request->long_url,
            'short_url' => Str::random(6),
        ]);
        return back()->with('success', 'Short URL created successfully!');
    }

    public function redirect($code) {
        $shortUrl = ShortUrl::where('short_url', $code)->firstOrFail();
        $shortUrl->increment('clicks');
        return redirect()->away($shortUrl->long_url);
    }
    public function exportShortUrls(Request $request): StreamedResponse
    {
        $user = $request->user();
        $query = ShortUrl::query();

        // 1. Role-based Scope
        if ($user->role === 'Admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($user->role === 'Member') {
            $query->where('user_id', $user->id);
        }

        // 2. Filter Switch Case
        switch ($request->get('filter')) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'last_week':
                $query->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;

            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;

            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;

            case 'all':
            default:
                // Fetch all records without date restriction
                break;
        }

        $shortUrls = $query->with('company', 'user')->latest()->get();

        $filterName = $request->get('filter') ?? 'all';
        $filename = "short_urls_{$filterName}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ];

        return response()->stream(function () use ($shortUrls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Original URL', 'Short Code', 'Created By', 'Company', 'Clicks', 'Created At']);

            foreach ($shortUrls as $url) {
                fputcsv($file, [
                    $url->long_url ?? $url->original_url,
                    $url->short_url ?? $url->short_code,
                    $url->user->name ?? 'N/A',
                    $url->company->name ?? 'N/A',
                    $url->clicks ?? 0,
                    $url->created_at ? $url->created_at->format('Y-m-d H:i') : 'N/A',
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }
public function exportCompanies(Request $request): StreamedResponse
{
    $query = Company::withCount(['users', 'shortUrls']);

    // Date Range Filter Logic
    switch ($request->get('filter')) {
        case 'today':
            $query->whereDate('created_at', Carbon::today());
            break;

        case 'last_week':
            $query->whereBetween('created_at', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ]);
            break;

        case 'this_month':
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            break;

        case 'last_month':
            $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                  ->whereYear('created_at', Carbon::now()->subMonth()->year);
            break;

        case 'all':
        default:
            // Fetch all records without filtering
            break;
    }

    $companies = $query->latest()->get();
    $filterName = $request->get('filter', 'all');
    $filename = "clients_list_{$filterName}.csv";

    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename={$filename}",
    ];

    return response()->stream(function () use ($companies) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Company Name', 'Users Count', 'URLs Count', 'Created At']);

        foreach ($companies as $company) {
            fputcsv($file, [
                $company->name, 
                $company->users_count, 
                $company->short_urls_count,
                $company->created_at ? $company->created_at->format('Y-m-d H:i') : 'N/A'
            ]);
        }
        fclose($file);
    }, 200, $headers);
}
}