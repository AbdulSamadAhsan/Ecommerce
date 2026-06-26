<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public int $count = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function refreshCount()
    {
        $session_id = session()->getId();

        $user_id = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        $cart = Cart::when(
            $user_id,
            function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            },
            function ($query) use ($session_id) {
                $query->where('session_id', $session_id);
            },
        )->first();

        $this->count = $cart ? CartItem::where('cart_id', $cart->id)->sum('quantity') : 0;
    }
};
?>

<span>
    {{ $count }}
</span>
