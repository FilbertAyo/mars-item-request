
Auth::user()->departments; 
USERS ROLES ON PETTY CASH AND ADVANCE PURCHASE

how to manage roles in a system guid
-composer require spatie/laravel-permission
first you should install package

then you run this command = php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
which generate the following table
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions

Usertype | Role
0 - SuperUser 



# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=marscommunication.team@gmail.com
# MAIL_PASSWORD=pgawnzkgjjrbqsrp
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS="marscommunication.team@gmail.com"
# MAIL_FROM_NAME="${APP_NAME}"


# MAIL_MAILER=smtp
# MAIL_HOST=gator4468.hostgator.com
# MAIL_PORT=465
# MAIL_USERNAME=noreply@marscommltd.com
# MAIL_PASSWORD=Marscomm@2025
# MAIL_ENCRYPTION=ssl
# MAIL_FROM_ADDRESS=noreply@marscommltd.com
# MAIL_FROM_NAME="${APP_NAME}"



You're trying to build a dynamic form where users can add multiple customer names with file attachments, all linked to a specific `petty_id`. I’ll give you everything:

* **Database Migration**
* **Model**
* **Routes**
* **Controller**
* **Blade View with JS for adding/removing inputs dynamically**

---

### ✅ Database Migration

```php
php artisan make:migration create_customer_attachments_table
```

```php
// database/migrations/xxxx_xx_xx_create_customer_attachments_table.php
public function up()
{
    Schema::create('customer_attachments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('petty_id');
        $table->string('customer_name');
        $table->string('attachment'); // file path
        $table->timestamps();

        $table->foreign('petty_id')->references('id')->on('petty_cashes')->onDelete('cascade');
    });
}
```

---

### ✅ Model

```bash
php artisan make:model CustomerAttachment
```

```php
// app/Models/CustomerAttachment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAttachment extends Model
{
    protected $fillable = ['petty_id', 'customer_name', 'attachment'];

    public function petty()
    {
        return $this->belongsTo(PettyCash::class);
    }
}
```

---

### ✅ Controller

```bash
php artisan make:controller CustomerAttachmentController
```

```php
// app/Http/Controllers/CustomerAttachmentController.php
namespace App\Http\Controllers;

use App\Models\CustomerAttachment;
use Illuminate\Http\Request;

class CustomerAttachmentController extends Controller
{
    public function create()
    {
        return view('customer_attachments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'petty_id' => 'required|exists:petty_cashes,id',
            'attachments.*.customer_name' => 'required|string',
            'attachments.*.file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048'
        ]);

        foreach ($request->attachments as $attachment) {
            $filePath = $attachment['file']->store('attachments', 'public');

            CustomerAttachment::create([
                'petty_id' => $request->petty_id,
                'customer_name' => $attachment['customer_name'],
                'attachment' => $filePath
            ]);
        }

        return redirect()->back()->with('success', 'Attachments uploaded successfully.');
    }
}
```

---

### ✅ Web Routes

```php
// routes/web.php
use App\Http\Controllers\CustomerAttachmentController;

Route::get('/customer-attachments/create', [CustomerAttachmentController::class, 'create'])->name('attachments.create');
Route::post('/customer-attachments', [CustomerAttachmentController::class, 'store'])->name('attachments.store');
```

---

### ✅ Blade View (resources/views/customer\_attachments/create.blade.php)

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Customer Attachments</h2>

    <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="petty_id">Petty ID</label>
            <input type="number" name="petty_id" class="form-control" required>
        </div>

        <div id="attachment-container">
            <div class="attachment-group mb-3">
                <input type="text" name="attachments[0][customer_name]" class="form-control mb-2" placeholder="Customer Name" required>
                <input type="file" name="attachments[0][file]" class="form-control mb-2" required>
                <button type="button" class="btn btn-danger remove-btn d-none">Remove</button>
            </div>
        </div>

        <button type="button" id="add-more" class="btn btn-secondary mb-3">Add More</button>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
let index = 1;
document.getElementById('add-more').addEventListener('click', function() {
    const container = document.getElementById('attachment-container');
    const group = document.createElement('div');
    group.classList.add('attachment-group', 'mb-3');
    group.innerHTML = `
        <input type="text" name="attachments[${index}][customer_name]" class="form-control mb-2" placeholder="Customer Name" required>
        <input type="file" name="attachments[${index}][file]" class="form-control mb-2" required>
        <button type="button" class="btn btn-danger remove-btn">Remove</button>
    `;
    container.appendChild(group);
    index++;
});

// Remove group
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-btn')) {
        e.target.closest('.attachment-group').remove();
    }
});
</script>
@endsection
```

