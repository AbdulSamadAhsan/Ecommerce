<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public int $count = 0;

    #[On('cart-updated')]
    public function updateCount(int $count): void
    {
        $this->count = $count;
    }
};
?>

<span>
    {{ $count }}
</span>
