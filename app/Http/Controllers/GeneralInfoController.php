<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\GeneralInfo;
use App\Models\GoogleRecaptcha;
use App\Models\SocialLogin;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class GeneralInfoController extends Controller
{
    public function aboutUsPage(){
        $data = AboutUs::where('id', 1)->first();
        return view('backend.general_info.about_us', compact('data'));
    }

    public function updateAboutUsPage(Request $request){
        $data = AboutUs::where('id', 1)->first();

        $banner_bg = $data->banner_bg;
        if ($request->hasFile('banner_bg')){

            if($banner_bg != '' && file_exists(public_path($banner_bg))){
                unlink(public_path($banner_bg));
            }

            $get_image = $request->file('banner_bg');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('uploads/about_us/');
            $get_image->move($location, $image_name);
            $banner_bg = "uploads/about_us/" . $image_name;
        }


        $image = $data->image;
        if ($request->hasFile('image')){

            if($image != '' && file_exists(public_path($image))){
                unlink(public_path($image));
            }

            $get_image = $request->file('image');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('uploads/about_us/');
            $get_image->move($location, $image_name);
            $image = "uploads/about_us/" . $image_name;
        }

        AboutUs::where('id', 1)->update([
            'banner_bg' => $banner_bg,
            'image' => $image,
            'section_sub_title' => $request->section_sub_title,
            'section_title' => $request->section_title,
            'section_description' => $request->section_description,
            'btn_icon_class' => $request->btn_icon_class,
            'btn_text' => $request->btn_text,
            'btn_link' => $request->btn_link,
            'updated_at' => Carbon::now(),
        ]);

        Toastr::success('About Us Info Updated', 'Success');
        return back();
    }

    public function generalInfo(Request $request){
        $data = GeneralInfo::where('id', 1)->first();
        return view('backend.general_info.info', compact('data'));
    }

    public function updateGeneralInfo(Request $request){
        $data = GeneralInfo::where('id', 1)->first();

        $image = $data->logo;
        if ($request->hasFile('logo')){

            if($data->logo != '' && file_exists(public_path($data->logo))){
                unlink(public_path($data->logo));
            }

            $get_image = $request->file('logo');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('company_logo/');
            $get_image->move($location, $image_name);
            $image = "company_logo/" . $image_name;
        }

        $imageDark = $data->logo_dark;
        if ($request->hasFile('logo_dark')){

            if($data->logo_dark != '' && file_exists(public_path($data->logo_dark))){
                unlink(public_path($data->logo_dark));
            }

            $get_image = $request->file('logo_dark');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('company_logo/');
            $get_image->move($location, $image_name);
            $imageDark = "company_logo/" . $image_name;
        }


        $favIcon = $data->fav_icon;
        if ($request->hasFile('fav_icon')){

            if($data->fav_icon != '' && file_exists(public_path($data->fav_icon))){
                unlink(public_path($data->fav_icon));
            }

            $get_image = $request->file('fav_icon');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('company_logo/');
            $get_image->move($location, $image_name);
            $favIcon = "company_logo/" . $image_name;
        }

        $paymentBanner = $data->payment_banner;
        if ($request->hasFile('payment_banner')){

            if($data->payment_banner != '' && file_exists(public_path($data->payment_banner))){
                unlink(public_path($data->payment_banner));
            }

            $get_image = $request->file('payment_banner');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('company_logo/');

            if($get_image->getClientOriginalExtension() == 'svg'){
                $get_image->move($location, $image_name);
            } else {
                Image::make($get_image)->save($location . $image_name, 25);
            }

            $paymentBanner = "company_logo/" . $image_name;
        }

        GeneralInfo::where('id', 1)->update([
            'logo' => $image,
            'logo_dark' => $imageDark,
            'fav_icon' => $favIcon,
            'tab_title' => $request->tab_title,
            'company_name' => $request->company_name,
            'short_description' => $request->short_description,
            'contact' => $request->contact,
            'email' => $request->email,
            'address' => $request->address,
            'google_map_link' => $request->google_map_link,
            'play_store_link' => $request->play_store_link,
            'app_store_link' => $request->app_store_link,
            'footer_copyright_text' => $request->footer_copyright_text,
            'payment_banner' => $paymentBanner,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('General Info Updated', 'Success');
        return back();
    }

    public function websiteThemePage(){
        $data = GeneralInfo::where('id', 1)->first();
        return view('backend.general_info.website_theme', compact('data'));
    }

    public function updateWebsiteThemeColor(Request $request){

        GeneralInfo::where('id', 1)->update([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'tertiary_color' => $request->tertiary_color,
            'title_color' => $request->title_color,
            'paragraph_color' => $request->paragraph_color,
            'border_color' => $request->border_color,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Website Theme Color Updated', 'Success');
        return back();

    }

    public function socialMediaPage(){
        $data = GeneralInfo::where('id', 1)->first();
        return view('backend.general_info.social_media', compact('data'));
    }

    public function updateSocialMediaLinks(Request $request){

        GeneralInfo::where('id', 1)->update([
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
            'messenger' => $request->messenger,
            'whatsapp' => $request->whatsapp,
            'telegram' => $request->telegram,
            'tiktok' => $request->tiktok,
            'pinterest' => $request->pinterest,
            'viber' => $request->viber,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Website Theme Color Updated', 'Success');
        return back();

    }

    public function seoHomePage(){
        $data = GeneralInfo::where('id', 1)->select('meta_title', 'meta_keywords', 'meta_description', 'meta_og_title', 'meta_og_description', 'meta_og_image')->first();
        return view('backend.general_info.seo_homepage', compact('data'));
    }

    public function updateSeoHomePage(Request $request){

        $data = GeneralInfo::where('id', 1)->first();
        $meta_og_image = $data->meta_og_image;
        if ($request->hasFile('meta_og_image')){

            if($data->meta_og_image != '' && file_exists(public_path($data->meta_og_image))){
                unlink(public_path($data->meta_og_image));
            }

            $get_image = $request->file('meta_og_image');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('company_logo/');
            $get_image->move($location, $image_name);
            $meta_og_image = "company_logo/" . $image_name;
        }

        GeneralInfo::where('id', 1)->update([
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'meta_og_title' => $request->meta_og_title,
            'meta_og_description' => $request->meta_og_description,
            'meta_og_image' => $meta_og_image,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Homepage SEO Updated', 'Success');
        return back();
    }

    public function customCssJs(){
        $data = GeneralInfo::where('id', 1)->first();
        return view('backend.general_info.custom_css_js', compact('data'));
    }

    public function updateCustomCssJs(Request $request){
        GeneralInfo::where('id', 1)->update([
            'custom_css' => $request->custom_css,
            'header_script' => $request->header_script,
            'footer_script' => $request->footer_script,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Custom CSS & JS Code Updated', 'Success');
        return back();
    }

    public function socialChatScriptPage(){
        $googleRecaptcha = GoogleRecaptcha::where('id', 1)->first();
        $generalInfo = GeneralInfo::where('id', 1)->first();
        $socialLoginInfo = SocialLogin::where('id', 1)->first();
        return view('backend.general_info.social_chat_script', compact('googleRecaptcha', 'generalInfo', 'socialLoginInfo'));
    }

    public function updateGoogleRecaptcha(Request $request){
        GoogleRecaptcha::where('id', 1)->update([
            'captcha_site_key' => $request->captcha_site_key,
            'captcha_secret_key' => $request->captcha_secret_key,
            'status' => $request->status,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Google Recaptcha Info Updated', 'Success');
        return back();
    }

    public function updateGoogleAnalytic(Request $request){
        GeneralInfo::where('id', 1)->update([
            'google_analytic_status' => $request->google_analytic_status,
            'google_analytic_tracking_id' => $request->google_analytic_tracking_id,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Google Analytic Info Updated', 'Success');
        return back();
    }

    public function updateGoogleTagManager(Request $request){
        GeneralInfo::where('id', 1)->update([
            'google_tag_manager_status' => $request->google_tag_manager_status,
            'google_tag_manager_id' => $request->google_tag_manager_id,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Google Tag Manager Info Updated', 'Success');
        return back();
    }

    public function updateSocialLogin(Request $request){
        SocialLogin::where('id', 1)->update([
            'fb_login_status' => $request->fb_login_status,
            'fb_app_id' => $request->fb_app_id,
            'fb_app_secret' => $request->fb_app_secret,
            'fb_redirect_url' => $request->fb_redirect_url,
            'gmail_login_status' => $request->gmail_login_status,
            'gmail_client_id' => $request->gmail_client_id,
            'gmail_secret_id' => $request->gmail_secret_id,
            'gmail_redirect_url' => $request->gmail_redirect_url,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Google Analytic Info Updated', 'Success');
        return back();
    }

    public function updateFacebookPixel(Request $request){
        GeneralInfo::where('id', 1)->update([
            'fb_pixel_status' => $request->fb_pixel_status,
            'fb_pixel_app_id' => $request->fb_pixel_app_id,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Facebook Pixel Info Updated', 'Success');
        return back();
    }

    public function updateMessengerChat(Request $request){
        GeneralInfo::where('id', 1)->update([
            'messenger_chat_status' => $request->messenger_chat_status,
            'fb_page_id' => $request->fb_page_id,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Messenger Chat Info Updated', 'Success');
        return back();
    }

    public function updateTawkChat(Request $request){
        GeneralInfo::where('id', 1)->update([
            'tawk_chat_status' => $request->tawk_chat_status,
            'tawk_chat_link' => $request->tawk_chat_link,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Tawk Chat Info Updated', 'Success');
        return back();
    }

    public function updateCrispChat(Request $request){
        GeneralInfo::where('id', 1)->update([
            'crisp_chat_status' => $request->crisp_chat_status,
            'crisp_website_id' => $request->crisp_website_id,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Crisp Chat Info Updated', 'Success');
        return back();
    }
    
    public function changeGuestCheckoutStatus(){
        $info = GeneralInfo::where('id', 1)->first();
        if($info->guest_checkout == 1){
            GeneralInfo::where('id', 1)->update([
                'guest_checkout' => 0
            ]);
        } else {
            GeneralInfo::where('id', 1)->update([
                'guest_checkout' => 1
            ]);
        }

        return response()->json(['success' => 'Saved successfully.']);
    }
}