{{-- user info and avatar --}}
{{-- <div class="avatar av-l chatify-d-flex"></div> --}}
 <img src="{{ asset(Auth::user()->file ?? 'image/prof.jpeg') }}" alt="User Image"
                                class="img-fluid rounded-circle" width="200" height="200">

<p class="info-name">{{ config('chatify.name') }}</p>
<div class="messenger-infoView-btns">
    <a href="#" class="danger delete-conversation">Delete Conversation</a>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Shared Photos</span></p>
    <div class="shared-photos-list"></div>
</div>
