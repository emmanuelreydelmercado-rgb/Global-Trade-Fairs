<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Form;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('ExponName');
        });

        // Generate slugs for existing records
        Form::all()->each(function ($form) {
            $baseSlug = Str::slug($form->ExponName);
            $slug = $baseSlug;
            $count = 1;

            // Ensure uniqueness
            while (Form::where('slug', $slug)->where('id', '!=', $form->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $form->slug = $slug;
            $form->save();
        });

        // Make slug non-nullable after populating
        Schema::table('forms', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
