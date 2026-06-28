<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!Schema::hasColumn('sales', 'payment_method')) {
                    $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'jazzcash', 'easypaisa'])->default('cash');
                }

                if (!Schema::hasColumn('sales', 'payment_status')) {
                    $table->enum('payment_status', ['pending', 'partial', 'paid', 'failed', 'refunded'])->default('pending');
                }

                if (!Schema::hasColumn('sales', 'paid_amount')) {
                    $table->decimal('paid_amount', 12, 2)->default(0);
                }

                if (!Schema::hasColumn('sales', 'due_amount')) {
                    $table->decimal('due_amount', 12, 2)->default(0);
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable();
                }

                if (!Schema::hasColumn('orders', 'cancelled_at')) {
                    $table->dateTime('cancelled_at')->nullable();
                }

                if (!Schema::hasColumn('orders', 'cancellation_reason')) {
                    $table->string('cancellation_reason')->nullable();
                }

                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }

        if (Schema::hasTable('shipments')) {
            Schema::table('shipments', function (Blueprint $table) {
                if (!Schema::hasColumn('shipments', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable();
                }

                if (!Schema::hasColumn('shipments', 'cancelled_at')) {
                    $table->dateTime('cancelled_at')->nullable();
                }

                if (!Schema::hasColumn('shipments', 'dispatch_by')) {
                    $table->foreignId('dispatch_by')->nullable()->constrained('users')->nullOnDelete();
                }

                if (!Schema::hasColumn('shipments', 'canceled_by')) {
                    $table->foreignId('canceled_by')->nullable()->constrained('users')->nullOnDelete();
                }

                if (!Schema::hasColumn('shipments', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }

        if (Schema::hasTable('delivery_assignments')) {
            Schema::table('delivery_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('delivery_assignments', 'failed_reason')) {
                    $table->string('failed_reason')->nullable();
                }
            });
        }

        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'pdf_path')) {
                    $table->string('pdf_path')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'pdf_path')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('pdf_path');
            });
        }

        if (Schema::hasTable('delivery_assignments') && Schema::hasColumn('delivery_assignments', 'failed_reason')) {
            Schema::table('delivery_assignments', function (Blueprint $table) {
                $table->dropColumn('failed_reason');
            });
        }

        if (Schema::hasTable('shipments')) {
            Schema::table('shipments', function (Blueprint $table) {
                if (Schema::hasColumn('shipments', 'dispatch_by')) {
                    $table->dropForeign(['dispatch_by']);
                }

                if (Schema::hasColumn('shipments', 'canceled_by')) {
                    $table->dropForeign(['canceled_by']);
                }
            });

            Schema::table('shipments', function (Blueprint $table) {
                $columns = [];

                foreach (['expected_delivery', 'cancelled_at', 'dispatch_by', 'canceled_by', 'notes'] as $column) {
                    if (Schema::hasColumn('shipments', $column)) {
                        $columns[] = $column;
                    }
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $columns = [];

                foreach (['expected_delivery', 'cancelled_at', 'cancellation_reason', 'notes'] as $column) {
                    if (Schema::hasColumn('orders', $column)) {
                        $columns[] = $column;
                    }
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                $columns = [];

                foreach (['payment_method', 'payment_status', 'paid_amount', 'due_amount'] as $column) {
                    if (Schema::hasColumn('sales', $column)) {
                        $columns[] = $column;
                    }
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};