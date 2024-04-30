<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Category;
use App\Models\Media;
use App\Models\Menu;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;


class AdminPost extends Component
{
    use WithFileUploads;
    public $api_address;
    public $menus;
    public $selected_menu;
    public $update_current_menu;
    public $user_id;
    public $posts;
    public $body;
    public $tags;
    public $tag;
    public $categories;
    public $category;
    public $addresses;
    public $custom_address;
    public $selected_tag = [];
    public $selected_category;
    public $selected_address;
    public $title;
    public $createLocationState = false;
    public $createCategoryState = false;
    public $createTagState = false;
    public $optionTagState = false;
    public $optionCategoryState = false;
    public $update_tag = [];
    public $update_category = [];
    public $show_image_state = false;
    // Config Mode
    public $current_post_id = 0;
    public $configState = false;
    public $selected_post_state = false;
    public $selected_post_id = 0;
    public $selected_post = [];
    public $current_image;
    public $update_title;
    public $update_image;
    public $body_update;
    public $update_current_tag = [];
    public $update_current_category;
    public $update_custom_address;
    public $update_selected_address;
    public $created_at;
    public $updated_at;
    public $searchTitle; // max 20mb

    #[Validate('image|min:20|max:20000')]
    public $image;

    // Posts Mode
    public $showPostsModeState = false;
    public $searchPostState = false;
    public $filterPostState = false;
    public $show_ckeditor_state = true;

    protected $rules = [
        'body' => 'required|string|min:1',
        'title' => 'required|string|max:100',
        'selected_tag' => 'required|array',
        'selected_category' => 'required|integer',
    ];

    public function mount()
    {
        $this->user_id = auth()->user()->id;
        $this->getAllData();
        $this->update_current_tag = $this->tags;
    }

    public function showCkEditor()
    {
        $this->show_ckeditor_state = !$this->show_ckeditor_state;
        $this->dispatch('show-ckeditor');
    }

    public function configMode()
    {
        $this->configState = !$this->configState;
        $this->selected_post_state = false;
        $this->dispatch('reset-body', data: '');
        $this->dispatch('reset-error');
        $this->optionTagState = false;
        $this->createTagState = false;
        $this->optionCategoryState = false;
        $this->optionCategoryState = false;
        $this->createLocationState = false;
        $this->reset('update_title');
        $this->reset('title');
        $this->reset('update_current_tag');
        $this->reset('update_current_category');
        $this->reset('update_custom_address');
        $this->reset('body_update');
        $this->reset('update_image');
        $this->reset('custom_address');
        $this->reset('created_at');
        $this->reset('updated_at');
        $this->reset('update_image');
        $this->reset('image');
        $this->reset('show_image_state');
        $this->getAllPosts();
    }

    public function resetFormCustomAddress()
    {
        $this->reset('custom_address');
        $this->reset('update_current_address');
    }

    public function createAddress()
    {
        $this->createLocationState = !$this->createLocationState;
        // $this->createCategoryState = !$this->createCategoryState;
        // $this->createTagState = !$this->createTagState;
    }

    public function setCreateCategoryState()
    {
        $this->createCategoryState = !$this->createCategoryState;
        // $this->createLocationState = !$this->createLocationState;
        // $this->createTagState = !$this->createTagState;
        $this->reset('category');
    }

    public function setCreateTagState()
    {
        $this->createTagState = !$this->createTagState;
        // $this->createCategoryState = !$this->createCategoryState;
        // $this->createLocationState = !$this->createLocationState;
        $this->reset('tag');
    }

    // LOADING
    public function loadingCreateTagState()
    {
        return;
    }
    public function loadingCreateCategoryState()
    {
        return;
    }
    public function loadingCreatePostState()
    {
        return;
    }

    // #[On('tags-update')]
    public function createTag()
    {
        Validator::make(['tag' => $this->tag], ['tag' => 'required|string'], ['required' => 'Kolom :attribute harus diisi'])->validate();
        $other_tag = Tag::where('name', 'like', '%' . trim($this->tag) . '%')->first();
        $other_tag_accurate = Tag::where('name', trim($this->tag))->first();
        if ($other_tag && $other_tag_accurate) {
            return session()->now('tag_status', ['message' => 'Nama tag sudah ada', 'color' => 'danger']);
        }
        Tag::create([
            'name' => $this->tag
        ]);
        $this->getTags();
        $this->dispatch('tags-update');
        session()->now('tag_status', ['message' => 'Berhasil membuat tag ' . $this->tag, 'color' => 'success']);
        $this->reset('tag');
    }

    #[On('body')]
    public function getBody($data)
    {
        $this->body = $data;
    }

    #[On('body-updated')]
    public function getBodyUpdated($data)
    {
        $this->body_update = $data;
    }

    public function post()
    {
        Validator::make(
            ['title' => $this->title],
            ['title' => 'required|string|max:50'],
            ['title.max' => 'Panjang judul maksimum :max karakter']
        )->validate();

        Validator::make(['image' => $this->image], ['image' => 'required|image|min:10|max:20000'], ['required' => 'Postingan wajib memiliki gambar utama, jika tidak ada buat terlebih dahulu'])->validate();
        Validator::make(['body' => $this->body], ['body' => 'required|string'], ['required' => 'Postingan wajib memiliki konten bodi, jika tidak ada buat terlebih dahulu'])->validate();
        Validator::make(['selected_tag' => $this->selected_tag], ['selected_tag' => 'required|array'], ['required' => 'Postingan wajib memiliki tag, jika tidak ada buat terlebih dahulu'])->validate();
        Validator::make(['selected_category' => $this->selected_category], ['selected_category' => 'required|integer'], ['required' => 'Postingan wajib memiliki kategori, jika tidak ada buat terlebih dahulu'])->validate();
        Validator::make(['selected_menu' => $this->selected_menu], ['selected_menu' => 'required|integer'], ['required' => 'Postingan wajib memiliki menu, jika tidak ada buat terlebih dahulu'])->validate();

        if (empty($this->tags)) {
            Validator::make(['selected_tag' => $this->selected_tag], ['selected_tag' => 'required|array'], ['required' => 'Postingan wajib memiliki tag, jika tidak ada buat terlebih dahulu'])->validate();
        }
        if (empty($this->categories)) {
            Validator::make(['selected_category' => $this->selected_category], ['selected_category' => 'required|integer'], ['required' => 'Postingan wajib memiliki kategori, jika tidak ada buat terlebih dahulu'])->validate();
        }

        // memindahkan file dari temp ke images
        $images_filename = [];
        // jika menggunakan 127.0.0.1, maka ganti localhost menjadi 127.0.0.1
        $pattern = '/src="http:\/\/localhost:8000\/temp\/([^"]+)"/';
        if (preg_match_all($pattern, $this->body, $matches)) {
            $images_filename = $matches;
        }

        foreach ($images_filename as $key => $image_filename) :
            if ($key === 1) {
                foreach ($image_filename as $item) :
                    // $image_path = public_path('temp/' . $item);
                    File::move(public_path('/temp/' . $item), public_path('/assets/images/' . $item));
                endforeach;
            }
        endforeach;
        // ubah lokasi dari temp ke images
        $this->body = str_replace('/temp/', '/assets/images/', $this->body);

        $other_post_accurate = Post::where('title', trim($this->title))->first();
        $other_post = Post::where('title', 'like', '%' . trim($this->title) . '%')->first();
        if ($other_post && $other_post_accurate) {
            return session()->now('post_title_conflict', ['message' => 'Postingan Dengan Judul ' . $this->title . ' Sudah Ada. Gunakan Judul Yang Lain']);
        }
        // membuat postingan
        $response = Post::create([
            'user_id' => $this->user_id,
            'category_id' => $this->selected_category,
            'menu_id' => $this->selected_menu,
            'title' => trim($this->title),
            'content' => $this->body,
            'slug' => Str::slug($this->title, '-'),
            'show_image' => (boolean) $this->show_image_state,
            // 'location' => $this->createLocationState ? $this->custom_address : $selected_address,
            'location' => "kosong"
        ]);

        foreach (array_keys($this->selected_tag) as $tag) :
            DB::table('post_tag')->insert([
                'post_id' => $response->id,
                'tag_id' => $tag
            ]);
        endforeach;

        // File Image Upload #2
        $type = $this->image->extension();
        $filename = $this->image->hashName();
        File::move($this->image->getRealPath(), public_path('assets/images/' . $filename));
        $post_id = $response->id;
        $path = public_path('assets/images' . $filename);
        Media::create([
            'post_id' => $post_id,
            'name' => $filename,
            'file_path' => $path,
            'type' => $type
        ]);

        $this->createCategoryState = false;
        $this->createLocationState = false;
        $this->createTagState = false;
        session()->flash('post_status', ['message' => 'Berhasil Membuat Postingan Dengan Judul ' . $this->title]);
        $this->redirect('posts');
    }

    public function getAllData()
    {
        $this->getTags();
        $this->getCategories();
        $this->addresses = Address::all()->toArray();
        $this->menus = Menu::all()->toArray();
    }

    // Option
    public function optionTag()
    {
        $this->optionTagState = !$this->optionTagState;
        // $this->selected_post_state = false;
        // $this->selectedPost($this->selected_post_id);
        $this->reset('selected_tag');
    }
    public function optionCategory()
    {
        $this->optionCategoryState = !$this->optionCategoryState;
        $this->reset('update_category');
    }

    // Edit
    public function editTag($name)
    {
        if (empty($this->update_tag)) {
            return;
        }
        // dd($this->update_tag);
        $id = array_keys($this->update_tag)[0];
        $name = $this->update_tag[$id];
        $current_tag = array_filter($this->tags, function ($tag) use ($id) {
            return $tag['id'] == $id;
        });
        $current_tag = reset($current_tag)['name'];
        $other_tag = Tag::where('name', 'like', '%' . trim($name) . '%')->first();
        $other_tag_accurate = Tag::where('name', trim($name))->first();
        if ($other_tag && $other_tag_accurate && $current_tag != $other_tag->name && $current_tag != $other_tag_accurate->name) {
            return session()->now('tag_status', ['message' => 'Nama tag sudah ada', 'color' => 'danger']);
        }
        Tag::where('id', $id)->update([
            'name' => $name
        ]);
        $this->reset('update_tag');
        $this->getTags();

        if ($this->selected_post_state) {
            $this->getAllPosts();
            foreach ($this->posts as $key => $post) :
                if ($post['id'] == $id) {
                    $this->selected_post = $post;
                    break;
                }
            endforeach;
            $this->reset('update_current_tag');
            foreach ($this->selected_post['tag'] as $tagid) :
                $this->update_current_tag[$tagid['id']] = $tagid;
            endforeach;
        }
        session()->now('tag_status', ['message' => 'Berhasil mengubah tag ' . $current_tag . ' menjadi ' . $name, 'color' => 'success']);
    }

    public function deleteTag($id)
    {
        $current_tag_name = array_filter($this->tags, function ($tag) use ($id) {
            return $tag['id'] == $id;
        });
        $current_tag_name = reset($current_tag_name)['name'];
        Tag::find($id)->delete();
        $this->getTags();
        session()->now('tag_status', ['message' =>  "Berhasil menghapus tag $current_tag_name", 'color' => 'success']);
    }

    public function createCategory()
    {
        Validator::make(['category' => $this->category], ['category' => 'required|string'], ['required' => 'Kolom :attribute harus diisi'])->validate();
        $other_category = Category::where('name', 'like', '%' . trim($this->category) . '%')->first();
        $other_category_accurate = Category::where('name', trim($this->category))->first();
        if ($other_category && $other_category_accurate) {
            return session()->now('category_status', ['message' => 'Nama kategori sudah digunakan', 'color' => 'danger']);
        }
        Category::create([
            'name' => trim($this->category),
            'description' => null
        ]);
        $this->getCategories();
        session()->now('create_category_status', 'Berhasil membuat kategori ' . trim($this->category));
        $this->reset('category');
    }

    public function editCategory($currentName)
    {
        // dd($this->update_category);
        if (empty($this->update_category)) {
            return;
        }
        $id = array_keys($this->update_category)[0];
        $name = $this->update_category[$id];
        $current_category = array_filter($this->categories, function ($category) use ($id) {
            return $category['id'] == $id;
        });
        $current_category = reset($current_category);
        $other_category = Category::where('name', 'like', '%' . trim($this->category) . '%')->first();
        $other_category_accurate = Category::where('name', trim($this->category))->first();
        if ($other_category && $other_category_accurate && $current_category['name'] != $other_category->name && $current_category['name'] != $other_category_accurate->name) {
            return session()->now('category_status', ['message' => 'Nama kategori sudah digunakan', 'color' => 'danger']);
        }
        Category::where('id', $id)->update([
            'name' => $name,
            'description' => null
        ]);
        $this->getCategories();
        session()->now('category_status', ['message' =>  'Berhasil mengubah nama kategori ' . $current_category['name'] . ' menjadi ' . $name, 'color' => 'success']);
        $this->reset('update_category');
    }

    public function deleteCategory($id)
    {
        $current_category_name = array_filter($this->categories, function ($category) use ($id) {
            return $category['id'] == $id;
        });
        $current_category_name = reset($current_category_name)['name'];
        Category::find($id)->delete();
        $this->getCategories();
        session()->now('category_status', ['message' => "Berhasil menghapus kategori $current_category_name", 'color' => 'success']);
    }

    public function getTags()
    {
        $this->tags = Tag::all()->toArray();
        $this->tags = array_map(function ($tag) {
            return [
                'id' => $tag['id'],
                'name' => $tag['name'],
                'has_post' => !Tag::find($tag['id'])->posts->first() ? false : true,
                'created_at' => $tag['created_at'],
                'updated_at' => $tag['updated_at']
            ];
        }, $this->tags);
    }

    public function getCategories()
    {
        $this->categories = Category::all()->toArray();
        $this->categories = array_map(function ($category) {
            return [
                'id' => $category['id'],
                'name' => $category['name'],
                'has_post' => !Category::find($category['id'])->posts->first() ? false : true
            ];
        }, $this->categories);
    }

    public function getMenus()
    {
        $response = Http::withHeaders($this->headers)->get($this->api_address . 'menu');
        $response = json_decode($response->body(), JSON_OBJECT_AS_ARRAY);
        $this->menus = $response['data'];
    }

    public function getAllPosts()
    {
        // date_default_timezone_set('UTC');
        $this->posts = Post::with(['category', 'tags', 'menu', 'media'])->get()->toArray();
        $this->posts = array_map(function ($post) {
            $category = Category::find($post['category_id']);
            $tags = Post::find($post['id'])->tags;
            $menu = Menu::find($post['menu_id']);
            $media = Media::where('post_id', $post['id'])->first();
            $updatedAt = Carbon::parse($post['updated_at'])->setTimezone('Asia/Jakarta')->format('l, j F Y H:i:s');
            $createdAt = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('l, j F Y H:i:s');
            return [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'category' => $category ? $category->toArray() : null,
                'tag' => $tags ? $tags->toArray() : null,
                'menu' => $menu ? $menu->toArray() : null,
                'title' => $post['title'],
                'content' => $post['content'],
                'location' => $post['location'],
                'slug' => $post['slug'],
                'image' => $media ? $media->name : null,
                'show_image' => (boolean)$post['show_image'],
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }, $this->posts);

    }

    public function selectedPost($id)
    {
        $this->reset('update_current_tag');
        if ($this->selected_post_id != $id) {
            $this->selected_post_id = $id;
            $this->selected_post_state = true;
            $this->reset('selected_tag');
            $this->reset('created_at');
        $this->reset('updated_at');
        } elseif ($this->selected_post_id == $id) {
            $this->selected_post_id = 0;
            $this->selected_post_state = !$this->selected_post_state;
            $this->reset('update_current_category');
            $this->reset('update_current_tag');
            $this->reset('body_update');
            $this->reset('current_image');
            $this->dispatch('reset-body', data: '');
            $this->reset('created_at');
        $this->reset('updated_at');
            return $this->reset('update_title');
        }
        $this->selected_post = array_filter($this->posts, function ($post) use ($id) {
            return $post['id'] == $id;
        });
        $this->selected_post = reset($this->selected_post);
        $this->update_current_menu = $this->selected_post['menu']['id'] ?? null;
        $this->update_title = $this->selected_post['title'];
        $this->body_update = $this->selected_post['content'];
        $this->current_image = $this->selected_post['image'];
        $this->update_current_category = $this->selected_post['category']['id'];
        // mengubah indeks array menjadi id tag agar mudah diambil sampel tag yang dipilih
        foreach ($this->selected_post['tag'] as $tagid) :
            $this->update_current_tag[$tagid['id']] = $tagid;
        endforeach;
        $this->created_at = $this->selected_post['created_at'];
        $this->updated_at = $this->selected_post['updated_at'];
        $this->update_custom_address = $this->selected_post['location'];
        $this->show_image_state = (boolean)$this->selected_post['show_image'];
        $this->dispatch('selected', data: $this->body_update);
    }

    public function editPost()
    {
        if (!$this->selected_post_id) {
            return session()->now('post_title_conflict', ['message' => 'Pilih Postingan Terlebih Dahulu']);
        }

        Validator::make(
            ['update_title' => $this->update_title],
            ['update_title' => 'required|string|max:50'],
            ['update_title.max' => 'Panjang judul maksimum :max karakter']
        )->validate();


        if (empty($this->update_current_tag)) {
            Validator::make(['update_current_tag' => $this->update_current_tag], ['update_current_tag' => 'required|array'], ['required' => 'Postingan wajib memiliki tag, jika tidak ada buat terlebih dahulu'])->validate();
        }

        if (empty($this->update_current_category)) {
            Validator::make(['update_current_category' => $this->update_current_category], ['update_current_category' => 'required|integer'], ['required' => 'Postingan wajib memiliki kategori, jika tidak ada buat terlebih dahulu'])->validate();
        }

        // memindahkan file dari temp ke images
        $images_filename = [];
        // jika menggunakan 127.0.0.1, maka ganti localhost menjadi 127.0.0.1
        $pattern = '/src="http:\/\/localhost:8000\/temp\/([^"]+)"/';
        if (preg_match_all($pattern, $this->body_update, $matches)) {
            $images_filename = $matches;
        }

        foreach ($images_filename as $key => $image_filename) :
            if ($key === 1) { //mengambil nama gambar
                foreach ($image_filename as $item):
                    // $image_path = public_path('temp/' . $item);
                    File::move(public_path('temp/' . $item), public_path('assets/images/' . $item));
                endforeach;
            }
        endforeach;
        // ubah lokasi dari temp ke images
        $this->body_update = str_replace('/temp/', '/assets/images/', $this->body_update);

        $other_post_accurate = Post::where('title', trim($this->update_title))->first();
        $other_post = Post::where('title', 'like', '%' . trim($this->update_title) . '%')->first();
        if ($other_post && $other_post_accurate && $other_post_accurate->title != $this->selected_post['title'] && $other_post->title != $this->selected_post['title']) {
            return session()->now('post_title_conflict', ['message' => 'Postingan Dengan Judul ' . $this->title . ' Sudah Ada. Gunakan Judul Yang Lain']);
        }
        Post::where('id', $this->selected_post['id'])->update([
            'category_id' => (int) $this->update_current_category,
            'menu_id' => $this->update_current_menu,
            'title' => trim($this->update_title),
            'content' => $this->body_update,
            'slug' => Str::slug($this->update_title, '-'),
            'show_image' => (boolean) $this->show_image_state,
            // 'location' => $this->createLocationState ? $this->update_custom_address : $selected_address,
            'location' => "kosong",
        ]);

        // menghapus semua tag yang ada di postingan yang dipilih
        for ($i = 1; $i <= count($this->selected_post['tag']); $i++) {
            DB::table('post_tag')->where('post_id', $this->selected_post['id'])->delete();
        }

        $selected_tag_filter = array_filter($this->update_current_tag, function ($var) {
            return $var != false;
        });
        // menambahkan semua tag yang dipilih ke postingan yang dipilih
        foreach (array_keys($selected_tag_filter) as $tag) :
            DB::table('post_tag')->insert([
                'post_id' => $this->selected_post['id'],
                'tag_id' => $tag
            ]);
        endforeach;

        if (!empty($this->update_image)) {
            // File image upload
            $type = $this->update_image->extension();
            $filename = $this->update_image->hashName();
            // File::move($this->update_image->getRealPath(), public_path('assets/images/' . $filename));
            File::move($this->update_image->getRealPath(), __DIR__ . "/../../../Zoom/" . $filename);
            $post_id = $this->selected_post['id'];
            $path = public_path('assets/images' . $filename);
            Media::where('post_id', $post_id)->update([
                'name' => $filename,
                'file_path' => $path,
                'type' => $type
            ]);
        }
        session()->flash('post_status', ['message' => 'Berhasil Memperbarui Postingan Dengan Judul ' . $this->update_title]);
        $this->redirect('posts');
    }

    public function deletePost()
    {
        Post::find($this->selected_post['id'])->delete();
        session()->flash('post_status', ['message' => 'Berhasil Menghapus Postingan Dengan Judul ' . $this->update_title]);
        $this->redirect('posts');
    }

    public function searchPost()
    {
        if (empty(trim($this->searchTitle))) {
            return $this->getAllPosts();
        }
        $this->posts = Post::search($this->searchTitle)->toArray();
        $this->posts = array_map(function ($post) {
            $createdAt = Carbon::parse($post['created_at']);
            $updatedAt = Carbon::parse($post['updated_at']);
            return [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'category' => Category::find($post['category_id'])->toArray(),
                'tag' => Post::find($post['id'])->tags->toArray(),
                'menu' => Menu::find($post['menu_id'])->toArray(),
                'title' => $post['title'],
                'content' => $post['content'],
                'location' => $post['location'],
                'slug' => $post['slug'],
                'image' => Media::where('post_id', $post['id'])->first()->name,
                'created_at' => $createdAt->format('l, j F Y H:i:s'),
                'updated_at' => $updatedAt->format('l, j F Y H:i:s')
            ];
        }, $this->posts);
    }

    public function setDefaultSearchPost()
    {
        $this->searchPostState = false;
        $this->reset('searchTitle');
        $this->getAllPosts();
    }

    public function render()
    {
        return view('livewire.admin-post');
    }
}
