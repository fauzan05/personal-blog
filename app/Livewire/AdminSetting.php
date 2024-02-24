<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\ApplicationSettings;
use App\Models\Menu;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminSetting extends Component
{
    use WithFileUploads;

    public $applicationSettings;
    public $headers;
    public $api_address;
    public $token;
    public $user;
    public $first_name;
    public $last_name;
    // public $username;
    public $email;
    public $email_blog;
    public $place_of_birth;
    public $date_of_birth;
    public $phone_number;
    public $phone_number_blog;
    public $role;
    public $profile_photo_filename;
    public $bio;
    public $app_version;
    public $blog_name;
    public $navbar_color = 'var(--body-color)';
    public $navbar_text_color = 'var(--text-color)';
    public $footer_color = 'var(--body-color)';
    public $footer_text_color = 'var(--text-color)';
    public $logo_filename;
    public $update_password_state = false;
    public $create_address_state = false;
    public $edit_address_state = false;
    public $old_password;
    public $new_password;

    public $addresses;
    // public $street;
    // public $village;
    // public $subdistrict;
    // public $city;
    public $selected_address;

    public $edit_selected_province_address = [];
    public $edit_selected_country_address = [];
    public $province;
    public $country;
    // public $postal_code;

    public $new_password_confirmation; // max 20mb

    #[Validate('image|min:20|max:20000')]
    public $update_profile_image; //

    #[Validate('image|min:20|max:20000')]
    public $update_logo_image;


    public $menus;
    public $menu;
    public $create_menu_state = false;
    public $edit_menu_state = false;
    public $edit_menu = [];

    public $social_medias;
    public $create_social_media_state = false;
    public $edit_social_media_state = false;
    public $edit_account_name = [];
    public $edit_account_link = [];
    public $edit_account_type = [];
    public $account_name;
    public $account_type = 'instagram';
    public $account_link;
    public $application_settings;
    public $show_title_state;

    public function mount()
    {
        $this->getAdminSettings();
    }

    public function getAdminSettings()
    {
        $this->applicationSettings = ApplicationSettings::first();
        if (!empty($this->applicationSettings)) {
            $this->applicationSettings->toArray();
        }
        $this->app_version = $this->applicationSettings['app_version'] ?? "1.0";
        $this->blog_name = $this->applicationSettings['blog_name'] ?? "Untitled";
        $this->navbar_color = $this->applicationSettings['navbar_color'] ?? "var(--navbar-color)";
        $this->navbar_text_color = $this->applicationSettings['navbar_text_color'] ?? "var(--navbar-text-color)";
        $this->footer_color = $this->applicationSettings['footer_color'] ?? "var(--footer-color)";
        $this->footer_text_color = $this->applicationSettings['footer_text_color'] ?? "var(--footer-text-color)";
        $this->logo_filename = $this->applicationSettings['logo_filename'] ?? "Untitled";
        $this->email_blog = $this->applicationSettings['email'] ?? "example@mail.com";
        $this->phone_number_blog = $this->applicationSettings['phone_number'] ?? "08123456789";
        $this->show_title_state = optional($this->applicationSettings)['show_blog_name'] ?? false;
        // User
        // dd(auth()->user());
        $this->user = auth()->user()->toArray();
        $this->first_name = $this->user['first_name'];
        $this->last_name = $this->user['last_name'];
        $this->email = $this->user['email'];
        $this->place_of_birth = $this->user['place_of_birth'];
        $this->date_of_birth = $this->user['date_of_birth'];
        $this->phone_number = $this->user['phone_number'];
        $this->role = $this->user['role'];
        $this->profile_photo_filename = $this->user['profile_photo_filename'];
        $this->bio = $this->user['bio'];
        // Address
        $this->addresses = Address::all()->toArray();
        if (!empty($this->addresses)) {
            $this->selected_address = array_filter($this->addresses, function ($address) {
                return $address['is_active'] == true;
            });
        }
        // Menu
        $this->menus = Menu::all()->toArray();
        // Media Socials
        $this->social_medias = SocialMedia::where('user_id', $this->user['id'])->get()->toArray();
    }

    public function updateUserProfileState()
    {
        return;
    }

    public function updateUserProfile()
    {

        Validator::make(['old_password' => $this->old_password], ['old_password' => 'required|string'])->validate();
        $profile_filename = null;
        if (!empty($this->update_profile_image)) {
            $profile_filename = $this->update_profile_image->hashName();
            File::move($this->update_profile_image->getRealPath(), public_path('assets/user-profile-image/' . $profile_filename));
        }
        if (!$this->user || !Hash::check($this->old_password, $this->user['password'])) {
            return session()->now('status_error', ['message' => 'Password Lama Salah', 'color' => 'danger']);
        }
        $user = User::find($this->user['id']);
        $user->first_name = !trim($this->first_name) ? $user->first_name : trim($this->first_name);
        $user->last_name = !trim($this->last_name) ? $user->last_name : trim($this->last_name);
        $user->email = !trim($this->email) ? $user->email : trim($this->email);
        $user->bio = !trim($this->bio) ? $user->bio : trim($this->bio);
        $user->place_of_birth = !trim($this->place_of_birth) ? $user->place_of_birth : trim($this->place_of_birth);
        $user->date_of_birth = !trim($this->date_of_birth) ? $user->date_of_birth : trim($this->date_of_birth);
        $user->phone_number = !trim($this->phone_number) ? $user->phone_number : trim($this->phone_number);
        $user->profile_photo_filename = !$profile_filename ? $user->profile_photo_filename : $profile_filename;
        $user->save();
        session()->flash('status_success', ['message' => 'Berhasil Mengubah Profil', 'color' => 'success']);
        $this->redirect('settings');
    }

    public function updateUserPassword()
    {
        $inputs = Validator::make(['old_password' => $this->old_password, 'new_password' => $this->new_password, 'new_password_confirmation' => $this->new_password_confirmation], ['old_password' => 'required|min:3|max:100|string', 'new_password' => 'required|min:3|max:50|string', 'new_password_confirmation' => 'required|min:3|max:50|same:new_password_confirmation'])->validate();
        if (!$this->user || !Hash::check($this->old_password, $this->user['password'])) {
            return session()->now('status_error', ['message' => 'Password Lama Salah', 'color' => 'danger']);
        }
        User::find($this->user['id'])->update([
            'password' => Hash::make($this->new_password)
        ]);
        session()->flash('status_success', ['message' => 'Berhasil Mengubah Password', 'color' => 'success']);
        $this->redirect('settings');
    }

    public function updateUserPasswordState()
    {
        $this->update_password_state = !$this->update_password_state;
    }
    public function updateAppSettingsState()
    {
        return;
    }

    public function createAddress()
    {
        return;
    }

    public function updateAppSettings()
    {
        // dd($this->email_blog, $this->phone_number_blog);
        Validator::make(['blog_name' => $this->blog_name], ['blog_name' => 'required|string'])->validate();
        $logo_filename = null;
        if (!empty($this->update_logo_image)) {
            $logo_filename = $this->update_logo_image->hashName();
            File::move($this->update_logo_image->getRealPath(), public_path('assets/logo/' . $logo_filename));
        }
        if (empty($this->applicationSettings)) {
            ApplicationSettings::create([
                'blog_name' => trim($this->blog_name),
                'navbar_color' => $this->navbar_color,
                'navbar_text_color' => $this->navbar_text_color,
                'footer_color' => $this->footer_color,
                'footer_text_color' => $this->footer_text_color,
                'logo_filename' => $this->logo_filename ? $logo_filename : $this->logo_filename,
                'email' => $this->email_blog,
                'phone_number' => $this->phone_number_blog,
                'show_blog_name' => (boolean)$this->show_title_state
            ]);
        }
        if (!empty($this->applicationSettings)) {
            ApplicationSettings::where('id', $this->applicationSettings['id'])->update([
                'blog_name' => trim($this->blog_name),
                'navbar_color' => $this->navbar_color,
                'navbar_text_color' => $this->navbar_text_color,
                'footer_color' => $this->footer_color,
                'footer_text_color' => $this->footer_text_color,
                'logo_filename' => empty($logo_filename) ? $this->logo_filename : $logo_filename,
                'email' => $this->email_blog,
                'phone_number' => $this->phone_number_blog,
                'show_blog_name' => (boolean)$this->show_title_state
            ]);
        }

        session()->flash('status_success', ['message' => 'Berhasil Mengubah Konfigurasi Blog', 'color' => 'success']);
        $this->redirect('settings');
    }

    public function createAddressState()
    {
        if ($this->edit_address_state) {
            $this->edit_address_state = false;
        }
        $this->create_address_state = !$this->create_address_state;
    }

    public function createNewAddress()
    {
        Validator::make(
            ['province' => $this->province, 'country' => $this->country],
            ['province' => 'required|string', 'country' => 'required|string']
        )->validate();

        Address::create([
            'province' => $this->province,
            'country' => $this->country,
            'user_id' => $this->user['id']
        ]);
        session()->now('status_address', 'Berhasil membuat alamat');
        $this->getAllAddress();
    }

    public function getAllAddress()
    {
        $this->addresses = Address::all()->toArray();
    }

    public function editAddressState()
    {
        if ($this->create_address_state) {
            $this->create_address_state = false;
        }
        $this->edit_address_state = !$this->edit_address_state;
        $this->reset('edit_selected_province_address');
        $this->reset('edit_selected_country_address');
    }

    public function editAddress($key)
    {
        if (empty($this->edit_selected_country_address) && empty($this->edit_selected_province_address)) {
            return;
        }
        if (!empty($this->edit_selected_country_address) && empty($this->edit_selected_province_address)) {

            $id = array_keys($this->edit_selected_country_address)[0];
            // $edit_value_province = $this->edit_selected_province_address[$id];
            $edit_value_country = $this->edit_selected_country_address[$id];
            $current_value_province = "";
            foreach ($this->addresses as $key => $address) :
                if ($address['id'] == $id) {
                    $current_value_province = $address['province'];
                    break;
                }
            endforeach;
            Address::where('id', $id)->update([
                'user_id' => $this->user['id'],
                'province' => $current_value_province,
                'country' => $edit_value_country,
            ]);
        }
        if (empty($this->edit_selected_country_address) && !empty($this->edit_selected_province_address)) {

            $id = array_keys($this->edit_selected_province_address)[0];
            // $edit_value_province = $this->edit_selected_province_address[$id];
            $edit_value_province = $this->edit_selected_province_address[$id];
            $current_value_country = "";
            foreach ($this->addresses as $key => $address) :
                if ($address['id'] == $id) {
                    $current_value_country = $address['country'];
                    break;
                }
            endforeach;
            Address::where('id', $id)->update([
                'user_id' => $this->user['id'],
                'province' => $edit_value_province,
                'country' => $current_value_country,
            ]);
        }
        if (!empty($this->edit_selected_country_address) && !empty($this->edit_selected_province_address)) {
            $id = array_keys($this->edit_selected_country_address)[0];
            $edit_value_province = $this->edit_selected_province_address[$id];
            $edit_value_country = $this->edit_selected_country_address[$id];
            Address::where('id', $id)->update([
                'user_id' => $this->user['id'],
                'province' => $edit_value_province,
                'country' => $edit_value_country,
            ]);
        }
        $this->reset('edit_selected_province_address');
        $this->reset('edit_selected_country_address');
        $this->getAllAddress();
        session()->now('status_address', 'Berhasil mengubah alamat');
    }

    public function deleteAddress($id)
    {
        Http::withHeaders($this->headers)
            ->delete($this->api_address . 'address/' . $id);
        $this->getAllAddress();
        session()->now('status_address', 'Berhasil menghapus alamat');
    }
    public function setMainAddress($id)
    {
        foreach ($this->addresses as $key => $address) :
            if ($address['is_active'] == true) {
                Address::where('id', $address['id'])->update([
                    'user_id' => $this->user['id'],
                    'province' => $address['province'],
                    'country' => $address['country'],
                    'is_active' => (bool)false
                ]);
                break;
            }
        endforeach;
        $current_province = "";
        $current_country  = "";
        foreach ($this->addresses as $key => $address) :
            if ($address['id'] == $id) {
                $current_province = $address['province'];
                $current_country = $address['country'];
            }
        endforeach;
        Address::where('id', $id)->update([
            'user_id' => $this->user['id'],
            'province' => $current_province,
            'country' => $current_country,
            'is_active' => (bool)true
        ]);
        $this->getAllAddress();
        session()->now('status_address', 'Berhasil mengubah alamat utama');
    }

    public function getAllMenus()
    {
        $this->menus = Menu::all()->toArray();
    }

    public function createMenuState()
    {
        if ($this->edit_menu_state) {
            $this->edit_menu_state = false;
        }
        $this->create_menu_state = !$this->create_menu_state;
    }

    public function editMenuState()
    {
        $this->reset('edit_menu');
        if ($this->create_menu_state) {
            $this->create_menu_state = false;
        }
        $this->edit_menu_state = !$this->edit_menu_state;
    }

    public function createMenu()
    {
        $other_menu_accurate = Menu::where('name', $this->menu)->first();
        $other_menu = Menu::where('name', 'like', '%' . $this->menu . '%')->first();
        if ($other_menu_accurate && $other_menu) {
            return session()->now('status_menu', ['message' =>  'Nama menu sudah digunakan', 'color' => 'danger']);
        }
        Menu::create([
            'name' => trim($this->menu)
        ]);

        $this->getAllMenus();
        session()->now('status_menu', ['message' => 'Berhasil membuat menu ' . $this->menu, 'color' => 'success']);
        $this->reset('menu');
    }

    public function editMenu($id)
    {
        if (trim(empty($this->edit_menu))) {
            $this->reset('edit_menu');
            return;
        }
        $id = array_keys($this->edit_menu)[0];
        $name = $this->edit_menu[$id];
        $other_menu_accurate = Menu::where('name', $this->menu)->first();
        $other_menu = Menu::where('name', 'like', '%' . $this->menu . '%')->first();
        $current_menu = array_filter($this->menus, function ($menu) use ($id) {
            return $menu['id'] == $id;
        });
        $current_menu = reset($current_menu);
        if ($other_menu_accurate && $other_menu && $other_menu_accurate->name != $current_menu['name'] && $other_menu->name != $current_menu['name']) {
            return session()->now('status_menu', ['message' =>  'Nama menu sudah digunakan', 'color' => 'danger']);
        }
        Menu::where('id', $id)->update([
            'name' => $name
        ]);

        $this->getAllMenus();
        session()->now('status_menu', ['message' => 'Berhasil mengubah menu ' . $current_menu['name'] . ' menjadi ' . $name, 'color' => 'success']);
        $this->reset('edit_menu');
    }

    public function deleteMenu($id, $name)
    {
        Menu::find($id)->delete();
        $this->getAllMenus();
        session()->now('status_menu', ['message' => 'Berhasil menghapus menu ' . $name, 'color' => 'success']);
    }
    public function getAllSocialMedia()
    {

        $this->social_medias = SocialMedia::all()->toArray();
    }

    public function createSocialMediaState()
    {
        if ($this->edit_social_media_state) {
            $this->edit_social_media_state = false;
        }
        $this->create_social_media_state = !$this->create_social_media_state;
    }
    public function editSocialMediaState()
    {
        if ($this->create_social_media_state) {
            $this->create_social_media_state = false;
        }
        $this->edit_social_media_state = !$this->edit_social_media_state;
    }

    public function createSocialMedia()
    {
        // dd($this->account_name, $this->account_link, $this->account_type);
        $inputs = Validator::make(
            ['account_name' => $this->account_name, 'account_link' => $this->account_link, 'account_type' => $this->account_type],
            ['account_name' => 'required|string', 'account_link' => 'required|string', 'account_type' => 'required|string']
        )->validated();
        SocialMedia::create([
            'user_id' => $this->user['id'],
            'account_name' => trim($inputs['account_name']),
            'account_link' => trim($inputs['account_link']),
            'access_token' => null,
            'additional_information' => null,
            'type' => $inputs['account_type']
        ]);
        $this->reset('account_name');
        $this->reset('account_link');
        $this->reset('account_type');
        $this->getAllSocialMedia();
        session()->now('status_social_media', ['message' => 'Berhasil menambahkan informasi akun sosial media ' . $this->account_name, 'color' => 'success']);
    }
    public function editSocialMedia($id)
    {
        // dd($id, $this->edit_account_name, $this->edit_account_link, $this->edit_account_type);
        $name = null;
        $link = null;
        $type = null;
        if (!empty($this->edit_account_name)) {
            $name = $this->edit_account_name[$id];
        }
        if (!empty($this->edit_account_link)) {
            $link = $this->edit_account_link[$id];
        }
        if (!empty($this->edit_account_type)) {
            $type = $this->edit_account_type[$id];
        }
        if (empty($this->edit_account_name) && empty($this->edit_account_link) && empty($this->edit_account_type)) {
            return;
        }

        $current_social_media = array_filter($this->social_medias, function ($social_media) use ($id) {
            return $social_media['id'] == $id;
        });
        $current_social_media = reset($current_social_media);
        SocialMedia::where('id', $id)->update([
            'user_id' => $this->user['id'],
            'account_name' => !trim($name) ? $current_social_media['account_name'] : $name,
            'account_link' => !trim($link) ? $current_social_media['account_link'] : $link,
            'type' => !$type ? $current_social_media['type'] : $type,
            'access_token' => null,
            'additional_information' => null
        ]);
        $this->reset('account_name');
        $this->reset('account_link');
        $this->reset('account_type');
        $this->getAllSocialMedia();
        session()->now('status_social_media', ['message' => 'Berhasil mengubah informasi akun sosial media', 'color' => 'success']);
    }

    public function deleteSocialMedia($id)
    {
        SocialMedia::find($id)->delete();
        $this->getAllSocialMedia();
        session()->now('status_social_media', ['message' => 'Berhasil mengubah menghapus akun sosial media', 'color' => 'success']);
    }
    public function render()
    {
        return view('livewire.admin-setting');
    }
}
