<?php

namespace App\Observers;

use App\Mail\CustomerCreated;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerObserver
{
    /**
     * Handle the Customer "creating" event.
     */
    public function creating(Customer $customer): void
    {
        if (isset($customer->password))
            return;

        $password = Str::password(20);

        $customer->password = $password;

        Mail::to($customer)
            ->send(new CustomerCreated($customer, $password, backpack_user()));
    }
}
