   @php
       $chatUser = \App\Models\User::find($id); // $id is the ID of the other user in the chat
   @endphp

@if ($chatUser)
   <div class="text-center mb-3">
       <img src="{{ asset($chatUser->file ?? 'image/prof.jpeg') }}" alt="User Image" class="img-fluid rounded-circle"
           width="100" height="100">
   </div>
       @endif

   <p class="info-name text-center">{{ config('chatify.name') }}</p>

   <div class="messenger-infoView-btns text-center">
       <a href="#" class="danger delete-conversation">Delete Conversation</a>
   </div>

   {{-- shared photos --}}
   <div class="messenger-infoView-shared">
       <p class="messenger-title"><span>Shared Photos</span></p>
       <div class="shared-photos-list"></div>
   </div>
