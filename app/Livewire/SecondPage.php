<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SecondPage extends Component
{
    public $current_category_name;
    public $current_category_id;
    public $current_menu_id;
    public $months_years = [];
    public $count_month_years;
    public $posts = [];
    public $current_page;
    public $last_page; //total page
    public $selected_page = 1;
    public $select_month_year;
    public $select_month_year_state = false;
    public $select_page_state = false;

    public function mount($current_menu_id, $current_category_name, $current_category_id, $selected_month, $selected_year)
    {
        $this->current_category_name = $current_category_name;
        $this->current_category_id = $current_category_id;
        $this->current_menu_id = $current_menu_id;
        if($selected_month && $selected_year) {
            $month_year = [$selected_month, $selected_year];
            $implode = join(" ", $month_year); // output format 01 2024            
            $carbonDate = Carbon::createFromFormat('m Y', $implode)->format('F Y');
            // dd($carbonDate);
            $this->select_month_year = $carbonDate;
            
            return $this->findPostByMonthYear();            
        }
        // dd($this->months_years);
        $this->getPostsByCategory();
    }

    public function getPostsByCategory()
    {
        if(!$this->select_page_state) {
            $posts = Post::where('menu_id', $this->current_menu_id)
            ->where('category_id', $this->current_category_id)->paginate(12);
        }else if($this->select_page_state) {
            $posts = Post::where('menu_id', $this->current_menu_id)
            ->where('category_id', $this->current_category_id)->paginate(12, ['*'], 'page', $this->selected_page);
            // dd($posts->items());
        }
        
        $this->posts = array_map(function ($post) {
            $created_at = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F j, Y');
            $created_at_sort_by_date = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F Y'); // membuat format untuk pencarian postingan berdasarkan bulan dan tahun
            $comments_count = count(Comment::where('post_id', $post['id'])->get()->toArray());
            $image = optional(Media::select('name')->where('post_id', $post['id'])->first())->name;
            // menghapus semua karakter html
            $filter_content = strip_tags($post['content']);
            $filter_content  = html_entity_decode($filter_content);
            $filter_content = htmlspecialchars(preg_replace('/\s+/u', ' ', $filter_content));
           
            return [
                'id' => $post['id'],
                'title' => $post['title'],
                'content' => $filter_content,
                'slug' => $post['slug'],
                'image' => $image,
                'total_comments' => $comments_count,
                'created_at' => $created_at,
                'created_at_sort_by_date' => $created_at_sort_by_date
            ];
        }, !$this->select_page_state ? $posts->items() : $posts->items());
        $this->reset('months_years');
        foreach ($this->posts as $key => $post) :
            $this->months_years[] = $post['created_at_sort_by_date'];
        endforeach;
        $this->months_years = array_count_values($this->months_years); // memasukkan semua postingan dengan kategori bulan dan tahun yang sama
        // dd($this->months_years);
        $this->current_page = $posts->currentPage();
        $this->last_page = $posts->lastPage();
    }

    public function findPostByMonthYear()
    {
        // dd($this->select_month_year);
        if(empty($this->select_month_year)) {
            $this->reset('posts');
            $this->reset('months_years');
            $this->reset('current_page');
            $this->reset('last_page');
            $this->select_month_year_state = false;
            return $this->getPostsByCategory();
        }
        // dd($this->select_month_year);
        $parse_month_year = Carbon::parse($this->select_month_year)->setTimezone('Asia/Jakarta')->format('m Y');
        $month_year = explode(' ', $parse_month_year); // output array 0 => 01, 1 => 2024
        $findPostByMonthYear = Post::where('menu_id', $this->current_menu_id)
            ->where('category_id', $this->current_category_id)
            ->whereMonth('created_at', '=', $month_year[0])
            ->whereYear('created_at', '=', $month_year[1])
            ->paginate(12);
            $this->current_page = $findPostByMonthYear->currentPage();
            $this->last_page = $findPostByMonthYear->lastPage();
        $this->posts = array_map(function ($post) {
            $created_at = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F j, Y');
            $created_at_sort_by_date = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F Y'); // membuat format untuk pencarian postingan berdasarkan bulan dan tahun
            $comments_count = count(Comment::where('post_id', $post['id'])->get()->toArray());
            $image = optional(Media::select('name')->where('post_id', $post['id'])->first())->name;

            // menghapus semua karakter html
            $filter_content = strip_tags($post['content']);
            $filter_content  = html_entity_decode($filter_content);
            $filter_content = htmlspecialchars(preg_replace('/\s+/u', ' ', $filter_content));
            return [
                'id' => $post['id'],
                'title' => $post['title'],
                'content' => $filter_content,
                'slug' => $post['slug'],
                'image' => $image,
                'total_comments' => $comments_count,
                'created_at' => $created_at,
                'created_at_sort_by_date' => $created_at_sort_by_date
            ];
        }, $findPostByMonthYear->items());
        $this->reset('months_years');
        foreach ($this->posts as $key => $post) :
            $this->months_years[] = $post['created_at_sort_by_date'];
        endforeach;
        $this->months_years = array_count_values($this->months_years); // memasukkan semua postingan dengan kategori bulan dan tahun yang sama
        // dd($this->months_years);
        $this->select_month_year_state = true;
    //    dd($this->posts);
    }

    public function getPage($page)
    {
        $this->select_page_state = true;
        $this->selected_page = $page;
        $this->getPostsByCategory();
       
    }

    public function render()
    {
        return view('livewire.second-page');
    }
}
