<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_accounts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('count_id')->nullable();                  
            $table->unsignedBigInteger('store_id')->nullable();            
            $table->unsignedBigInteger('parent_id')->nullable();                  
            $table->string('sort_code', 100)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('account_code', 100)->nullable();
            $table->double('balance', 20, 4)->nullable();              
            $table->text('note')->nullable(); 

          
            $table->unsignedBigInteger('supplier_id')->nullable();    
            $table->unsignedBigInteger('customer_id')->nullable();  
            $table->string('short_code', 100)->nullable();    
            
            // $table->string('created_by', 50)->nullable();
            // $table->date('created_date')->nullable();   
            $table->string('created_time', 50)->nullable();   
            $table->string('system_ip', 50)->nullable();   
            $table->string('system_name', 50)->nullable();   

            $table->unsignedBigInteger('delete_bit')->nullable();
            $table->string('account_selection_name', 50)->nullable();   

            $table->unsignedBigInteger('paymenttypes_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();

            $table->unsignedBigInteger('creator')->nullable();
            $table->string('slug')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_accounts');
    }
}
