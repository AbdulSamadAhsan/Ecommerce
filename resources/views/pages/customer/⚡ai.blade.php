<?php

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Product;

new class extends Component {
    public string $message = '';

    public array $messages = [];

    public function mount()
    {
        $this->messages[] = [
            'role' => 'bot',
            'text' => 'Hello! I can help with order tracking, delivery, payments, and products.',
        ];
    }

    public function send()
    {
        $this->validate([
            'message' => 'required|min:2|max:500',
        ]);

        $question = trim($this->message);

        $this->messages[] = [
            'role' => 'user',
            'text' => $question,
        ];

        $this->message = '';

        /*
        AI ROUTER
        */

        $router = $this->askAI("

You are ecommerce intent detector.

Return only JSON.

Available actions:

track_order
product_search
general


Examples:

Where is my order?
{\"action\":\"track_order\"}


Do you have laptop?
{\"action\":\"product_search\"}


Question:
{$question}


JSON:

");

        $action = json_decode(trim($router), true);

        $context = '';

        /*
        ORDER TRACKING
        */

        if (($action['action'] ?? '') === 'track_order') {
            $customer = Auth::guard('customer')->user() ?? Auth::user();

            if (!$customer) {
                $context = 'Customer is not logged in.';
            } else {
                $orders = Order::with(['sale', 'shipment'])
                    ->whereHas('sale', function ($q) use ($customer) {
                        $q->where('customer_id', $customer->id);
                    })
                    ->latest()
                    ->limit(5)
                    ->get();

                if ($orders->isEmpty()) {
                    $context = 'No orders found.';
                } else {
                    foreach ($orders as $o) {
                        $context .=
                            "

Order ID: {$o->id}

Payment:
" .
                            ($o->sale->payment_status ?? 'N/A') .
                            "

Amount:
" .
                            ($o->sale->total_amount ?? 'N/A') .
                            "

Delivery:
" .
                            ($o->shipment->status ?? 'Not shipped yet') .
                            "

Tracking:
" .
                            ($o->shipment->tracking_number ?? 'Not assigned') .
                            "

Company:
" .
                            ($o->shipment->shipping_company ?? 'N/A') .
                            "

";
                    }
                }
            }
        } /*
        PRODUCTS
        */ elseif (($action['action'] ?? '') === 'product_search') {
            $products = Product::where('status', 1)->limit(10)->get();

            foreach ($products as $p) {
                $context .= "

Product:
{$p->name}

Price:
{$p->selling_price}

Stock:
{$p->quantity}

";
            }
        } else {
            $context = 'Answer general ecommerce customer questions.';
        }

        /*
        FINAL AI ANSWER
        */

        $answer = $this->askAI("

You are ecommerce customer support AI.

Answer naturally.

Never mention:
database
JSON
Laravel
Ollama

Customer:
{$question}


Information:

{$context}


Answer:

");

        $this->messages[] = [
            'role' => 'bot',
            'text' => $answer,
        ];
    }

    private function askAI($prompt)
    {
        try {
            $res = Http::timeout(120)->post('http://127.0.0.1:11434/api/generate', [
                'model' => 'llama3.2:3b',

                'prompt' => $prompt,

                'stream' => false,

                'options' => [
                    'temperature' => 0.1,
                ],
            ]);

            return trim($res->json('response'));
        } catch (\Throwable $e) {
            return 'AI service unavailable';
        }
    }
};

?>



<div class="container py-4">


    <div class="card shadow">


        <div class="card-header bg-primary text-white">

            Customer AI Agent

        </div>



        <div class="card-body" style="height:430px;overflow:auto;">


            @foreach ($messages as $chat)
                @if ($chat['role'] == 'user')
                    <div class="text-end mb-3">

                        <span class="bg-primary text-white p-2 rounded">

                            {{ $chat['text'] }}

                        </span>

                    </div>
                @else
                    <div class="mb-3">

                        <span class="bg-light border p-2 rounded">

                            {{ $chat['text'] }}

                        </span>

                    </div>
                @endif
            @endforeach



            <div wire:loading>

                AI typing...

            </div>



        </div>




        <div class="card-footer">


            <form wire:submit.prevent="send">


                <div class="input-group">


                    <input class="form-control" wire:model.defer="message" placeholder="Track my order...">



                    <button class="btn btn-primary">

                        Send

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>
