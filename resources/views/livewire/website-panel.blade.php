<div class="row">
    <div class="col-md-12">

        @if (session()->has('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-none border">


            <div class="card-body">

                <!-- Website Section -->
                <div class="border p-4 mb-3">
                    <h2 class="text-xl font-bold mb-4">Website Info</h2>

                    <form wire:submit.prevent="saveWebsite" class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Hero Title</label>
                            <input type="text" class="form-control" wire:model.defer="hero_title"
                                placeholder="Hero Title">
                            @error('hero_title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Hero Text</label>
                            <input type="text" class="form-control" wire:model.defer="hero_text"
                                placeholder="Hero Text">
                            @error('hero_text')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Hero Image <span class="text-danger">* (W:2432 H:920)px</span> </label>

                            @if ($current_hero_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $current_hero_image) }}?{{ now()->timestamp }}"
                                     alt="Hero image" class="rounded" style="height:100px;">
                            </div>
                            @endif

                            <input type="file" class="form-control" wire:model="hero_image">
                            @error('hero_image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Intro Image <span class="text-danger">* (W:2000 H:1125)px</span> </label>

                            @if ($current_intro_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $current_intro_image) }}?{{ now()->timestamp }}" alt="Intro image"
                                        class="rounded" style="height:100px;">
                                </div>
                            @endif

                            <input type="file" class="form-control" wire:model="intro_image">
                            @error('intro_image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label class="form-label d-block">About Image <span class="text-danger">* (W:1080 H:1080)px</span> </label>

                            @if ($current_about_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $current_about_image) }}?{{ now()->timestamp }}" alt="About image"
                                        class="rounded" style="height:100px;">
                                </div>
                            @endif

                            <input type="file" class="form-control" wire:model="about_image">
                            @error('about_image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">Save Website</button>
                        </div>
                    </form>
                </div>



                <!-- Partner Section -->
                <div class="border p-4 mb-3">
                    <h2 class="text-xl font-bold mb-4">Partners <small class="text-danger">* No Background</small> </h2>

                    {{-- Form to Add Partner --}}
                    <form wire:submit.prevent="savePartner" class="space-y-4">
                        <input type="text" wire:model="partner_name" placeholder="Partner Name"
                            class="form-control mb-2">
                        <input type="file" wire:model="partner_logo" class="form-control mb-2">
                        <button class="btn btn-primary">Save Partner</button>
                    </form>

                    {{-- Display Partners in a Grid --}}
                    <div class="row mt-4">
                        @foreach ($partners as $p)
                            <div class="col-3 mb-4 text-center">
                                @if ($p->logo)
                                    <img src="{{ asset('storage/' . $p->logo) }}" alt="{{ $p->name }}"
                                        class="img-fluid mb-2" style="max-height: 100px; object-fit: contain;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center mb-2"
                                        style="height: 100px;">
                                        <span>No Logo</span>
                                    </div>
                                @endif

                                <div class="fw-bold">{{ $p->name }}</div>

                                <button wire:click="deletePartner({{ $p->id }})"
                                    class="btn btn-sm btn-danger mt-2">
                                    Delete
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>


                <!-- Service Section -->
                <div class="border p-4 mb-3">
                    <h2 class="text-xl font-bold mb-4">Services <small class="text-danger">* (W:1080 H:1080)px</small> </h2>

                    {{-- Form (Create / Update) --}}
                    <form wire:submit.prevent="{{ $service_id ? 'updateService' : 'saveService' }}" class="space-y-3">
                        <input type="text" wire:model="service_title" placeholder="Service Title"
                            class="form-control mb-2">

                        <textarea wire:model="service_description" placeholder="Service Description" class="form-control mb-2"></textarea>

                        <input type="file" wire:model="service_image" class="form-control mb-2">

                        <button class="btn btn-primary">
                            {{ $service_id ? 'Update Service' : 'Save Service' }}
                        </button>

                        @if ($service_id)
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">
                                Cancel
                            </button>
                        @endif
                    </form>

                    {{-- Services Table --}}
                    <div class="mt-4">
                        <table class="table table-bordered w-full text-left">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $s)
                                    <tr>
                                        <td>{{ $s->title }}</td>
                                        <td>{{ $s->description }}</td>
                                        <td>
                                            @if ($s->image)
                                                <img src="{{ asset('storage/' . $s->image) }}" alt="Service Image"
                                                    height="100">
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="editService({{ $s->id }})"
                                                class="btn btn-sm btn-warning">Edit</button>
                                            <button wire:click="deleteService({{ $s->id }})"
                                                class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="border p-4 mb-3">
                    <h2 class="text-xl font-bold mb-4">FAQs</h2>

                    {{-- FAQ Form --}}
                    <form wire:submit.prevent="saveFaq" class="space-y-3">
                        <input type="text" class="form-control mb-2" wire:model="faq_question" placeholder="Question">
                        @error('faq_question') <div class="text-danger small">{{ $message }}</div> @enderror

                        <textarea class="form-control mb-2" wire:model="faq_answer" placeholder="Answer"></textarea>
                        @error('faq_answer') <div class="text-danger small">{{ $message }}</div> @enderror

                        <button class="btn btn-primary">{{ $faqId ? 'Update FAQ' : 'Add FAQ' }}</button>
                    </form>

                    {{-- FAQ List --}}
                    <div class="mt-4">
                        <table class="table table-bordered w-full">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>{{ $faq->question }}</td>
                                        <td>{{ $faq->answer }}</td>
                                        <td>
                                            <button wire:click="editFaq({{ $faq->id }})" class="btn btn-sm btn-warning">Edit</button>
                                            <button wire:click="deleteFaq({{ $faq->id }})" class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No FAQs added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
