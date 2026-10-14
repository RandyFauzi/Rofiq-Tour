<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Article;
use App\Models\Departure;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\FeatureBanner;
use App\Models\GalleryPhoto;
use App\Models\Office;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'packages' => Package::catalog()->with('features')->orderByDesc('is_featured')->orderBy('sort_order')->limit(4)->get(),
            'departures' => Departure::publiclyVisible()->whereDate('departure_date', '<=', today()->addYear())->with(['package.destination', 'airline', 'rates.makkahHotel', 'rates.madinahHotel'])->orderBy('departure_date')->get(),
            'banners' => FeatureBanner::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get(),
            'siteSettings' => SiteSetting::first(),
            'faqs' => Faq::where('is_published', true)->orderBy('sort_order')->limit(11)->get(),
            'articles' => Article::published()->latest('published_at')->limit(3)->get(),
            'testimonials' => Testimonial::where('is_published', true)->limit(3)->get(),
        ]);
    }

    public function packages(SearchRequest $request): View
    {
        $query = Package::catalog()->orderBy('sort_order');
        if ($request->filled('destination')) {
            $query->whereHas('destination', fn ($q) => $q->where('slug', $request->string('destination')->toString()));
        }
        if ($request->filled('q')) {
            $term = mb_substr($request->string('q')->toString(), 0, 100);
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%"));
        }
        if ($request->filled('month') && preg_match('/^\\d{4}-(0[1-9]|1[0-2])$/', $request->string('month')->toString())) {
            [$year,$month] = explode('-', $request->string('month'));
            $query->whereHas('departures', fn ($q) => $q->upcoming()->whereYear('departure_date', $year)->whereMonth('departure_date', $month));
            $query->with(['departures' => fn ($q) => $q->upcoming()->whereYear('departure_date', $year)->whereMonth('departure_date', $month)->with(['airline', 'rates'])->orderBy('departure_date')]);
        }

        return view('pages.packages', [
            'packages' => $query->paginate(9)->withQueryString(),
            'destinations' => Destination::orderBy('name')->get(),
            'months' => Departure::publiclyVisible()->orderBy('departure_date')->get(['departure_date'])->map(fn ($d) => $d->departure_date->format('Y-m'))->unique(),
        ]);
    }

    public function package(Package $package): View
    {
        abort_unless($package->is_published, 404);
        $package->load(['destination', 'features', 'itineraryDays', 'departures' => fn ($q) => $q->upcoming()->with(['airline', 'rates.makkahHotel', 'rates.madinahHotel'])->orderBy('departure_date')]);

        return view('pages.package', compact('package'));
    }

    public function articles(SearchRequest $request): View
    {
        $q = Article::published()->latest('published_at');
        if ($request->filled('q')) {
            $term = mb_substr($request->string('q')->toString(), 0, 100);
            $q->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"));
        }
        if ($request->filled('category')) {
            $q->where('category', $request->string('category')->toString());
        }

        return view('pages.articles', ['articles' => $q->paginate(9)->withQueryString(), 'categories' => Article::published()->distinct()->pluck('category')]);
    }

    public function article(Article $article): View
    {
        abort_unless($article->published_at && $article->published_at->lte(now()), 404);

        return view('pages.article', compact('article'));
    }

    public function faq(SearchRequest $request): View
    {
        $q = Faq::where('is_published', true)->orderBy('sort_order');
        if ($request->filled('q')) {
            $term = mb_substr($request->string('q')->toString(), 0, 100);
            $q->where(fn ($q) => $q->where('question', 'like', "%{$term}%")->orWhere('answer', 'like', "%{$term}%"));
        }
        if ($request->filled('category')) {
            $q->where('category', $request->string('category')->toString());
        }

        return view('pages.faq', ['faqs' => $q->get(), 'categories' => Faq::where('is_published', true)->distinct()->pluck('category')]);
    }

    public function gallery(SearchRequest $request): View
    {
        $q = GalleryPhoto::where('is_published', true)->orderBy('sort_order');
        if ($request->filled('destination')) {
            $q->where('destination_id', $request->integer('destination'));
        }

        return view('pages.gallery', ['photos' => $q->paginate(12)->withQueryString(), 'destinations' => Destination::all()]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact', ['offices' => Office::where('is_published', true)->get()]);
    }

    public function consultation(Request $request): View
    {
        $selectedPackage = Package::published()->find($request->integer('package'));
        $selectedDeparture = $selectedPackage?->departures()->upcoming()->find($request->integer('departure'));

        return view('pages.consultation', [
            'packages' => Package::published()->orderBy('name')->get(),
            'selectedPackage' => $selectedPackage,
            'selectedDeparture' => $selectedDeparture,
        ]);
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}
