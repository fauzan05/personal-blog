<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class ThirdPage extends Component
{
    public $current_menu_id;
    public $current_tag_name;
    public $current_tag_id;
    public $months_years = [];
    public $count_month_years;
    public $posts = [];
    public $current_page = 1;
    public $last_page; //total page
    public $selected_page = 1;
    public $select_month_year;
    public $select_month_year_state = false;
    public $select_page_state = false;

    public function mount($current_menu_id, $current_tag_name, $current_tag_id)
    {
        $this->current_menu_id = $current_menu_id;
        $this->current_tag_name = $current_tag_name;
        $this->current_tag_id = $current_tag_id;
        $this->getPostsByTag();
    }

    public function getPostsByTag()
    {
        if (!$this->select_month_year_state) {
            $posts = Post::where('menu_id', $this->current_menu_id);
        } elseif ($this->select_month_year_state) {
            $parse = Carbon::createFromFormat('F Y', $this->select_month_year)->format('m Y');
            $month_year = explode(' ', $parse);
            $posts = Post::where('menu_id', $this->current_menu_id)
                ->whereMonth('created_at', '=', $month_year[0])
                ->whereYear('created_at', '=', $month_year[1]);
        }

        $all_posts_by_tag = [];
        foreach ($posts->get() as $post) :
            foreach ($post->tags as $post_tag) :
                if (strtolower($post_tag->name) == $this->current_tag_name) {
                    $all_posts_by_tag[] = $post;
                }
            endforeach;
        endforeach;
        // membuat object collection agar bisa membuat paginasi dari hasil $all_posts_by_tag
        $collection = collect($all_posts_by_tag);
        $total = $collection->count();
        $perPage = 12;
        $lastPage = ceil($total / $perPage);
        $page = request()->get('page', $this->current_page);
        $currentPageItems = $collection->forPage($page, $perPage);
        $paginator = new LengthAwarePaginator($currentPageItems, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        $items = $paginator->items();
        $this->last_page = $paginator->lastPage();
        $this->current_page = $paginator->currentPage();
        // dd($items, $last_page, $current_page);
        $this->posts = array_map(function ($post) {
            $created_at = Carbon::parse($post['created_at'])
                ->setTimezone('Asia/Jakarta')
                ->format('F j, Y');
            $created_at_sort_by_date = Carbon::parse($post['created_at'])
                ->setTimezone('Asia/Jakarta')
                ->format('F Y'); // membuat format untuk pencarian postingan berdasarkan bulan dan tahun
            $comments_count = count(
                Comment::where('post_id', $post['id'])
                    ->get()
                    ->toArray(),
            );
            $image = Media::select('name')
                ->where('post_id', $post['id'])
                ->first()->name;
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
                'created_at_sort_by_date' => $created_at_sort_by_date,
            ];
        }, $items);

        $this->reset('months_years');
        foreach ($this->posts as $key => $post) :
            $this->months_years[] = $post['created_at_sort_by_date'];
        endforeach;
        $this->months_years = array_count_values($this->months_years);
        // dd($this->months_years);
    }

    public function getPage($page)
    {
        $this->select_page_state = true;
        $this->current_page = $page;
        $this->selected_page = $page;
        $this->getPostsByTag();
    }

    public function findPostByMonthYear()
    {
        if (empty($this->select_month_year)) {
            $this->reset('posts');
            $this->reset('months_years');
            $this->reset('current_page');
            $this->reset('last_page');
            $this->select_month_year_state = false;
            return $this->getPostsByTag();
        }
        $parse = Carbon::createFromFormat('F Y', $this->select_month_year)->format('m Y');
        $this->select_month_year = $parse;
        $this->select_month_year_state = true;
        $parse = Carbon::createFromFormat('m Y', $this->select_month_year)->format('F Y');
        $this->select_month_year = $parse;
        $this->getPostsByTag();
    }
    public function render()
    {
        return view('livewire.third-page');
    }
}
