<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Partner;
use App\Models\Service;

class WebsiteController extends Controller
{
    // 1. Get website hero & intro section
    public function home()
    {
        $website = Website::first(); // assuming only one record
        return response()->json([
            'hero_title'   => $website->hero_title ?? null,
            'hero_text'    => $website->hero_text ?? null,
            'hero_image'   => $website->hero_image ? asset('storage/' . $website->hero_image) : null,
            'intro_image'  => $website->intro_image ? asset('storage/' . $website->intro_image) : null,
            'about_image' => $website->about_image ? asset('storage/' . $website->about_image) : null,
        ]);
    }

    // 2. List partners
    public function partners()
    {
        $partners = Partner::all()->map(function ($partner) {
            return [
                'name' => $partner->name,
                'logo' => $partner->logo ? asset('storage/' . $partner->logo) : null,
            ];
        });

        return response()->json($partners);
    }

    // 3. List services
    public function services()
    {
        $services = Service::all()->map(function ($service) {
            return [
                'title'       => $service->title,
                'description' => $service->description,
                'image'       => $service->image ? asset('storage/' . $service->image) : null,
            ];
        });

        return response()->json($services);
    }

    public function faq()
    {
        $faqs = Faq::all()->map(function ($faq) {
            return [
                'question' => $faq->question,
                'answer' => $faq->answer,
            ];
        });

        return response()->json($faqs);
    }
}
