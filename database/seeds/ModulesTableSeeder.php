<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('modules')->delete();
        
        \DB::table('modules')->insert(array (
            
            array (
                'id' => 1,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Users',
                'model_name' => 'User',
                'table_name' => 'users',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{first_name}} {{last_name}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-users',
                'reporting' => 1,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-04-01 09:17:48',
                'updated_at' => '2016-04-01 11:29:51',
            ),
            
            array (
                'id' => 2,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Invitations',
                'model_name' => 'Invitations',
                'table_name' => 'invitations',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{email}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-envelope-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-08-18 08:10:56',
                'updated_at' => '2016-08-18 08:10:56',
            ),
            
            array (
                'id' => 3,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Invitation Statuses',
                'model_name' => 'InvitationStatuses',
                'table_name' => 'invitation_statuses',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{title}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-list',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-08-18 12:18:07',
                'updated_at' => '2016-08-18 12:18:07',
            ),
            
            array (
                'id' => 4,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Assets',
                'model_name' => 'Assets',
                'table_name' => 'assets',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{file_name}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-file-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-08-30 12:01:17',
                'updated_at' => '2016-08-30 12:01:17',
            ),
            
            array (
                'id' => 5,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Roles',
                'model_name' => 'Roles',
                'table_name' => 'roles',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{title}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-user-circle-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-09-02 12:24:28',
                'updated_at' => '2016-09-02 12:24:28',
            ),
            
            array (
                'id' => 6,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Permissions',
                'model_name' => 'Permissions',
                'table_name' => 'permissions',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{title}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-minus-circle',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-09-02 12:43:44',
                'updated_at' => '2016-09-02 12:43:44',
            ),
            
            array (
                'id' => 7,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'File Tags',
                'model_name' => 'FileTags',
                'table_name' => 'file_tags',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{title}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-tags',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2016-10-07 07:14:34',
                'updated_at' => '2016-10-07 07:14:34',
            ),
            
            array (
                'id' => 8,
                'category' => 0,
                'type' => 'sortable',
                'name' => 'Widgets',
                'model_name' => 'Widgets',
                'table_name' => 'widgets',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => NULL,
                'anchor_html' => NULL,
                'icon' => 'fa fa-square',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 0,
                'created_at' => '2018-01-10 22:23:31',
                'updated_at' => '2018-01-11 08:18:51',
            ),
            
            array (
                'id' => 9,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Resized Images',
                'model_name' => 'ResizedImages',
                'table_name' => 'resized_images',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{top_x}},{{top_y}} - {{height}},{{width}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-picture-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2017-06-13 06:41:12',
                'updated_at' => '2017-06-13 06:41:12',
            ),
            
            array (
                'id' => 10,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Image sizes',
                'model_name' => 'ImageSizes',
                'table_name' => 'image_sizes',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{name}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-picture-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2017-06-13 06:52:01',
                'updated_at' => '2017-06-13 06:52:01',
            ),
            
            array (
                'id' => 12,
                'category' => NULL,
                'type' => 'multilevel_sortable',
                'name' => 'Galleries',
                'model_name' => 'Galleries',
                'table_name' => 'galleries',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{id}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-camera',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2017-08-20 16:32:56',
                'updated_at' => '2017-08-20 16:32:56',
            ),
            
            array (
                'id' => 13,
                'category' => 12,
                'type' => 'multilevel_sortable',
                'name' => 'Gallery Items',
                'model_name' => 'GalleryItems',
                'table_name' => 'gallery_items',
                'max_depth' => 0,
                'slug' => NULL,
                'anchor_text' => '{{id}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-camera',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 1,
                'created_at' => '2017-08-20 16:33:27',
                'updated_at' => '2017-08-20 16:33:27',
            ),
            
            array (
                'id' => 32321525772188,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Articles',
                'model_name' => 'Articles',
                'table_name' => 'articles',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => NULL,
                'anchor_html' => NULL,
                'icon' => 'fa fa-bars',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 0,
                'created_at' => '2018-05-08 09:36:28',
                'updated_at' => '2018-05-08 09:36:28',
            ),
            
            array (
                'id' => 34281524734445,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'News',
                'model_name' => 'News',
                'table_name' => 'news',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{title}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-newspaper-o',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 0,
                'created_at' => '2018-04-26 09:20:45',
                'updated_at' => '2018-06-10 14:51:52',
            ),
            
            array (
                'id' => 34521524734394,
                'category' => NULL,
                'type' => 'non_sortable',
                'name' => 'Categories',
                'model_name' => 'Categories',
                'table_name' => 'categories',
                'max_depth' => NULL,
                'slug' => NULL,
                'anchor_text' => '{{name}}',
                'anchor_html' => NULL,
                'icon' => 'fa fa-bars',
                'reporting' => 0,
                'lazy_loading' => 0,
                'is_system' => 0,
                'created_at' => '2018-04-26 09:19:54',
                'updated_at' => '2018-04-26 09:22:36',
            ),
        ));
        
        
    }
}