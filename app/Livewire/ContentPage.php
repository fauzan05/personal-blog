<?php

namespace App\Livewire;

use App\Enum\UserRoleEnum;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ContentPage extends Component
{
    public $post_id;
    public $post;
    public $current_page;
    public $nextPost;
    public $previousPost;
    public $comments;
    public $comments_parents;
    public $current_url;
    public $email;

    public $name;
    public $save_identity_state = false;
    public $comment_content;
    public $reply_comment_state = false;
    public $selected_comment;
    public $selected_comment_id;
    public $current_menu;
    public $current_category;
    public $current_month;
    public $current_year;
    public $show_image_state;

    public function mount($post_id)
    {
        $this->post_id = $post_id;
        $this->getPost();
        $this->getAllComments();
        $this->setCurrentUrl();
    }

    public function setCurrentUrl()
    {
        // menyimpan url terkini (halaman saat ini) agar ketika di get url, yang didapat adalah url halaman saat ini, bukan url livewire
        $current_url = Request::url();
        $this->current_url = str_replace('http://127.0.0.1:8000', '', $current_url);
    }

    public function getPost()
    {
        $post[] = Post::with('media', 'comments', 'tags')
            ->where('id', $this->post_id)
            ->first();
        $tags = $post[0]->tags->toArray();
        $image = optional($post[0]->media->toArray())[0]['name'] ?? null;
        $menu = $post[0]->menu->name;
        $this->current_menu = $menu;
        $total_comments = count($post[0]->comments->toArray());
        $this->comments = $post[0]->comments;
        // dd($comments->first()->users);
        $category = $post[0]->category->toArray()['name'];
        $this->current_category = $category;
        $created_at = $post[0]->created_at->format('F j, Y');
        $this->post = array_map(function ($post) use ($tags, $image, $total_comments, $category, $created_at) {
            return [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'menu_id' => $post['menu_id'],
                'title' => $post['title'],
                'slug' => $post['slug'],
                'tags' => $tags,
                'content' => $this->convertOembedToIframe($post['content']),
                'image' => $image ?? null,
                'category' => $category,
                'location' => $post['location'],
                // 'all_comments' => $comments,
                'total_comments' => $total_comments,
                'show_image' => (bool) $post['show_image'],
                'created_at' => $created_at,
            ];
        }, $post);
        $this->post = reset($this->post);

        // dd($this->post);
        $nextPost =
            Post::select('title', 'slug')
                ->where('id', '>', $this->post_id)
                ->orderBy('id', 'asc')
                ->where('menu_id', $this->post['menu_id'])
                ->first() ?? null;
        if ($nextPost) {
            $this->nextPost = $nextPost->toArray();
        }
        $previousPost =
            Post::select('title', 'slug')
                ->where('id', '<', $this->post_id)
                ->orderBy('id', 'desc')
                ->where('menu_id', $this->post['menu_id'])
                ->first() ?? null;
        if ($previousPost) {
            $this->previousPost = $previousPost->toArray();
        }
        $parse_month_year = Carbon::parse($this->post['created_at'])
            ->setTimezone('Asia/Jakarta')
            ->format('m Y');
        $parse_month_year = explode(' ', $parse_month_year);
        $this->current_month = $parse_month_year[0];
        $this->current_year = $parse_month_year[1];
        $this->show_image_state = (bool) $this->post['show_image'];
        // dd($parse_month_year);
        // dd($this->nextPost, $this->previousPost);
    }

    function convertToIframe($youtubeUrl)
    {
        // Mendapatkan ID video dari URL YouTube
        $videoId = $this->getYouTubeVideoId($youtubeUrl);

        // Menyusun ulang URL untuk tag <iframe>
        $iframeUrl = "https://www.youtube.com/embed/{$videoId}";

        // Membuat tag <iframe>
        $iframeTag = "<iframe width=\"560\" height=\"315\" src=\"{$iframeUrl}\" frameborder=\"0\" allowfullscreen></iframe>";

        return $iframeTag;
    }

    function convertOembedToIframe($content)
    {
        // Temukan semua tag <oembed> dalam konten
        preg_match_all('/<oembed\s+url="([^"]+)"\s*><\/oembed>/', $content, $matches);

        // Loop melalui hasil pencarian dan ganti dengan tag <iframe>
        foreach ($matches[1] as $youtubeUrl) {
            $iframeTag = $this->convertToIframe($youtubeUrl);
            $content = str_replace("<oembed url=\"$youtubeUrl\"></oembed>", $iframeTag, $content);
        }

        return $content;
    }

    function getYouTubeVideoId($youtubeUrl)
    {
        // Mengekstrak ID video dari URL YouTube
        $videoId = '';
        parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $urlParams);
        if (isset($urlParams['v'])) {
            $videoId = $urlParams['v'];
        }

        return $videoId;
    }

    public function createComment()
    {
        Validator::make(['name' => $this->name, 'email' => $this->email, 'comment_content' => $this->comment_content], ['name' => 'required|string', 'email' => 'required|string', 'comment_content' => 'required|string'], ['required' => 'Kolom :attribute harus diisi'])->validate();
        // cek apakah user ada atau tidak di database, jika ada maka ambil . jika tidak maka create
        $user = User::firstOrCreate(['username' => trim($this->name), 'email' => trim($this->email)], ['role' => UserRoleEnum::GUEST]);

        $user_id = $user->id;

        Comment::create([
            'post_id' => $this->post_id,
            'user_id' => $user_id,
            'parent_id' => $this->selected_comment_id ?? null,
            'content' => trim($this->comment_content),
        ]);
        if ($this->save_identity_state) {
            // jika save identity true maka cek user by cookie
            $current_user_by_cookie = Cookie::get('user'); // user by cookie
            $current_user = implode(',', [$user_id, $user->username, $user->email]); // user by database

            if ($current_user_by_cookie) {
                // jika cookie ada, cek apakah user by cookie itu sama dengan user by database
                $current_user_by_cookie = explode(',', $current_user_by_cookie);
                if ($current_user_by_cookie[0] != $user_id) {
                    // jika beda, maka buat cookie baru lagi karena sekarang usernya sudah berganti
                    Cookie::queue('user', $current_user, 525600);
                    $this->save_identity_state = true;
                    $this->name = $user->username;
                    $this->email = $user->email;
                }
            } else if(!$current_user_by_cookie) {
                // jika cookie tidak ada, maka buat cookie baru untuk user baru
                Cookie::queue('user', $current_user, 525600);
            }
        } elseif (!$this->save_identity_state && Cookie::get('user')) {
            // jika ada cookie namun save identity false (artinya seseorang tidak ingin menyimpan informasi lagi kedalam cookie)
            // maka cookie langsung dihapus
            Cookie::expire('user');
            $this->redirect($this->current_url);
        } else {
            // jika cookie tidak ada dan identity false maka hanya menghapus komponen saja
            $this->reset(['name', 'email', 'comment_content', 'save_identity_state']);
        }
        $this->getAllComments();
    }

    public function getAllComments()
    {
        $current_user = Cookie::get('user'); //cek apakah ada cookie
        // dd($current_user);
        if ($current_user && !$this->save_identity_state) {
            $current_user = explode(',', $current_user);
            $this->name = $current_user[1] != $this->name ? $current_user[1] : $this->name;
            $this->email = $current_user[2] != $this->email ? $current_user[2] : $this->email;
            // dd($current_user, $this->name);
            $this->save_identity_state = true;
        }
        $this->comments = Comment::where('post_id', $this->post_id)->get();
        $comments = array_map(function ($comment) {
            $username = User::select('username')
                ->find($comment['user_id'])
                ->toArray()['username'];
            // $created_at = $comment['created_at'] ? Carbon::parse($comment['created_at'])->setTimezone('Asia/Jakarta')->format('d M Y') : null;
            $created_at = $comment['created_at']
                ? Carbon::parse($comment['created_at'])
                    ->setTimezone('Asia/Jakarta')
                    ->diffForHumans()
                : null;
            return [
                'id' => $comment['id'],
                'post_id' => $this->post_id,
                'username' => $username,
                'parent_id' => $comment['parent_id'],
                'content' => $comment['content'],
                'created_at' => $created_at ?? null,
            ];
        }, $this->comments->toArray());
        $comments_has_parents = array_filter($comments, function ($comment) {
            return $comment['parent_id'] != null;
        });
        $comments_parents = array_values($comments_has_parents);
        $comments = array_filter($comments, function ($comment) {
            return $comment['parent_id'] == null;
        });
        // dd($comments, $comments_parents);
        $this->comments = $comments;
        $this->comments_parents = $comments_parents;
        $this->reset('comment_content');
        $this->removeReplyComment();
        // $this->dispatch('clear-error');
    }

    public function replyComment($username, $comment_id)
    {
        $this->selected_comment_id = $comment_id;
        $this->selected_comment = $username;
        $this->reply_comment_state = true;
    }

    public function removeReplyComment()
    {
        $this->reply_comment_state = false;
        $this->reset('selected_comment');
        $this->reset('selected_comment_id');
    }

    public function render()
    {
        return view('livewire.content-page');
    }
}
