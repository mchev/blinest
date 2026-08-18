<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        $usedSlugs = [];

        Category::query()->orderBy('id')->each(function (Category $category) use (&$usedSlugs): void {
            $base = Str::slug($category->name);

            if ($base === '') {
                $base = 'category-'.$category->id;
            }

            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[] = $slug;

            $category->update(['slug' => $slug]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
