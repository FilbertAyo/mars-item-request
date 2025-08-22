<?php

namespace App\Livewire;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Website;
use Livewire\Component;
use Livewire\WithFileUploads;


class WebsitePanel extends Component
{
    use WithFileUploads;

    // Website fields
    public $hero_title = '';
    public $hero_text  = '';
    public $hero_image;
    public $intro_image;

    public $about_image;

    // Partners fields
    public $partner_name;
    public $partner_logo;

    public $websiteId = null;
    public $service_title, $service_description, $service_image, $service_id;

    // Collections for display
    public $websites;
    public $partners;
    public $services;

    public $current_hero_image  = null; // saved path for preview
    public $current_intro_image = null; // saved path for preview
    public $current_about_image = null; // saved path for preview

    public $faq_question;
    public $faq_answer;
    public $faqs;

    public $faqId = null;

    public function mount()
    {
        // load latest website
        $latest = Website::latest()->first();

        if ($latest) {
            $this->websiteId           = $latest->id;
            $this->hero_title          = $latest->hero_title ?? '';
            $this->hero_text           = $latest->hero_text ?? '';
            $this->current_hero_image  = $latest->hero_image;
            $this->current_intro_image = $latest->intro_image;
            $this->current_about_image = $latest->about_image;
        }

        // load collections
        $this->websites = Website::all();
        $this->partners = Partner::all();
        $this->services = Service::all();
        $this->faqs = Faq::all();
    }

    public function saveWebsite()
    {
        $this->validate([
            'hero_title'   => 'nullable|string|max:255',
            'hero_text'    => 'nullable|string',
            'hero_image'   => 'nullable|image|max:2048',
            'intro_image'  => 'nullable|image|max:2048',
            'about_image'  => 'nullable|image|max:2048',
        ]);

        // Load existing or new website
        $website = $this->websiteId ? Website::find($this->websiteId) : new Website();

        // Text fields
        $website->hero_title = $this->hero_title;
        $website->hero_text  = $this->hero_text;

        // Handle images
        if ($this->hero_image) {
            $website->hero_image = $this->hero_image->store('website', 'public');
        }

        if ($this->intro_image) {
            $website->intro_image = $this->intro_image->store('website', 'public');
        }

        if ($this->about_image) {
            $website->about_image = $this->about_image->store('website', 'public');
        }

        // Save record
        $website->save();

        // Update current previews
        $this->websiteId          = $website->id;
        $this->current_hero_image  = $website->hero_image;
        $this->current_intro_image = $website->intro_image;
        $this->current_about_image = $website->about_image;

        // Reset file inputs
        $this->reset(['hero_image', 'intro_image', 'about_image']);

        session()->flash('success', 'Website info saved.');
    }

    public function savePartner()
    {
        $this->validate([
            'partner_name' => 'required|string|max:255',
            'partner_logo' => 'required|image|max:2048',
        ]);

        $data = [
            'name' => $this->partner_name,
            'logo' => $this->partner_logo
                ? $this->partner_logo->store('partners', 'public')
                : null,
        ];
        Partner::create($data);

        $this->reset(['partner_name', 'partner_logo']);
        $this->partners = Partner::all();
        session()->flash('success', 'Partner saved.');
    }

    public function deletePartner($id)
    {
        $partner = Partner::find($id);
        if ($partner) {
            // delete logo file if exists
            if ($partner->logo && \Storage::exists('public/' . $partner->logo)) {
                \Storage::delete('public/' . $partner->logo);
            }
            $partner->delete();
        }

        $this->partners = Partner::all(); // refresh the list
    }
    public function saveService()
    {
        $this->validate([
            'service_title' => 'required|string|max:255',
            'service_description' => 'required|string',
            'service_image' => 'nullable|image|max:2048',
        ]);

        $path = $this->service_image ? $this->service_image->store('services', 'public') : null;

        Service::create([
            'title' => $this->service_title,
            'description' => $this->service_description,
            'image' => $path,
        ]);

        $this->resetForm();
    }

    public function editService($id)
    {
        $service = Service::findOrFail($id);
        $this->service_id = $service->id;
        $this->service_title = $service->title;
        $this->service_description = $service->description;
    }

    public function updateService()
    {
        $this->validate([
            'service_title' => 'required|string|max:255',
            'service_description' => 'required|string',
            'service_image' => 'nullable|image|max:2048',
        ]);

        $service = Service::findOrFail($this->service_id);

        $path = $service->image;
        if ($this->service_image) {
            $path = $this->service_image->store('services', 'public');
        }

        $service->update([
            'title' => $this->service_title,
            'description' => $this->service_description,
            'image' => $path,
        ]);

        $this->resetForm();
    }

    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image) {
            \Storage::disk('public')->delete($service->image);
        }
        $service->delete();
    }

    public function resetForm()
    {
        $this->service_id = null;
        $this->service_title = '';
        $this->service_description = '';
        $this->service_image = null;
    }

    public function saveFaq()
    {
        $this->validate([
            'faq_question' => 'required|string|max:255',
            'faq_answer'   => 'required|string',
        ]);

        if ($this->faqId) {
            $faq = Faq::find($this->faqId);
            $faq->update([
                'question' => $this->faq_question,
                'answer'   => $this->faq_answer,
            ]);
        } else {
            $faq = Faq::create([
                'question' => $this->faq_question,
                'answer'   => $this->faq_answer,
            ]);
        }

        // Refresh FAQ list
        $this->faqs = Faq::all();

        // Reset inputs
        $this->reset(['faq_question', 'faq_answer', 'faqId']);

        session()->flash('success', 'FAQ saved.');
    }

    public function editFaq($id)
    {
        $faq = Faq::find($id);
        $this->faqId = $faq->id;
        $this->faq_question = $faq->question;
        $this->faq_answer = $faq->answer;
    }

    public function deleteFaq($id)
    {
        Faq::find($id)->delete();
        $this->faqs = Faq::all();
        session()->flash('success', 'FAQ deleted.');
    }

    public function render()
    {
        return view('livewire.website-panel');
    }
}
