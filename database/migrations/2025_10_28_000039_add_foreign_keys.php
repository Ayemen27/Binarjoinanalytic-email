<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->foreign('lang', 'blog_categories_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreign('category_id', 'blog_posts_category_id_foreign')
                  ->references('id')
                  ->on('blog_categories')
                  ->onDelete('cascade')
                  ->onUpdate('restrict');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->foreign('user_id', 'domains_user_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->foreign('lang', 'faqs_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('features', function (Blueprint $table) {
            $table->foreign('lang', 'features_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('lang', 'menus_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('user_id', 'messages_user_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreign('lang', 'pages_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('plan_features', function (Blueprint $table) {
            $table->foreign('plan_id', 'plan_features_plan_id_foreign')
                  ->references('id')
                  ->on('plans')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('plan_subscriptions', function (Blueprint $table) {
            $table->foreign('plan_id', 'plan_subscriptions_plan_id_foreign')
                  ->references('id')
                  ->on('plans')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        Schema::table('plan_subscription_usage', function (Blueprint $table) {
            $table->foreign('feature_id', 'plan_subscription_usage_feature_id_foreign')
                  ->references('id')
                  ->on('plan_features')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('lang', 'sections_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('seo', function (Blueprint $table) {
            $table->foreign('lang', 'seo_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('translates', function (Blueprint $table) {
            $table->foreign('lang', 'translates_lang_foreign')
                  ->references('code')
                  ->on('languages')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

    }

    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign('blog_categories_lang_foreign');
        });
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign('blog_posts_category_id_foreign');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->dropForeign('domains_user_id_foreign');
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign('faqs_lang_foreign');
        });
        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign('features_lang_foreign');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign('menus_lang_foreign');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_user_id_foreign');
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_lang_foreign');
        });
        Schema::table('plan_features', function (Blueprint $table) {
            $table->dropForeign('plan_features_plan_id_foreign');
        });
        Schema::table('plan_subscriptions', function (Blueprint $table) {
            $table->dropForeign('plan_subscriptions_plan_id_foreign');
        });
        Schema::table('plan_subscription_usage', function (Blueprint $table) {
            $table->dropForeign('plan_subscription_usage_feature_id_foreign');
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign('sections_lang_foreign');
        });
        Schema::table('seo', function (Blueprint $table) {
            $table->dropForeign('seo_lang_foreign');
        });
        Schema::table('translates', function (Blueprint $table) {
            $table->dropForeign('translates_lang_foreign');
        });
    }
};
