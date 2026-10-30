<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ShopSettingCon extends Controller
{
    // Begin:: Shop Setting Added
    public function addSetting(Request $request) {
        $request->validate([
            'company_name' => 'required',
            'address' => 'required',
            'phone_1' => 'required',
            'email' => 'required',
        ]);

        $last_img = 'frontend/assets/images/logo/logo.png';
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $name_gen = hexdec(uniqid()) . "." . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $name_gen);
            $last_img = 'images/' . $name_gen;
        }

        $data = array();
        $data['company_name'] = $request->company_name;
        $data['logo'] = $last_img;
        $data['address'] = $request->address;
        $data['phone_1'] = $request->phone_1;
        $data['phone_2'] = $request->phone_2;
        $data['whatsapp_number'] = $request->whatsapp_number ?? $request->phone_1;
        $data['email'] = $request->email;
        $data['facebook'] = $request->facebook;
        $data['messenger_link'] = $request->messenger_link;
        $data['youtube'] = $request->youtube;
        $data['instagram'] = $request->instagram;
        $data['twitter'] = $request->twitter;
        $data['linkden'] = $request->linkden ?? $request->linkedin ?? null;
        $data['count1'] = $request->count1 ?? 25000;
        $data['count2'] = $request->count2 ?? 450;
        $data['count3'] = $request->count3 ?? 85;
        $data['count4'] = $request->count4 ?? 98;
        $data['privacy_policy'] = $request->editor1;
        $data['mission_and_vission'] = $request->editor2;
        $data['trams_and_condition'] = $request->editor3;
        $data['footer_des'] = $request->footer_des;
        $data['footer_credit'] = $request->footer_credit;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        DB::table('company_settings')->insert($data);
        return Redirect()->route('admin_setting')->with('status', 'Setting Added Successfully');
    }
    // End:: Shop Setting Added

    // Shop Setting Edit
    public function EditShopSetting($id) {
        $company_setting = DB::table('company_settings')->where('id', $id)->first();
        return view('cms.setting.edit_shop_setting', compact('company_setting'));
    }

    // Update Shop Settings
    public function UpdateShopSetting(Request $request, $id) {
        $old_image = $request->old_image;
        $logo = $request->file('logo');

        $data = array();
        $data['company_name'] = $request->company_name;
        $data['address'] = $request->address;
        $data['phone_1'] = $request->phone_1;
        $data['phone_2'] = $request->phone_2;
        $data['whatsapp_number'] = $request->whatsapp_number ?? $request->phone_1;
        $data['email'] = $request->email;
        $data['facebook'] = $request->facebook;
        $data['messenger_link'] = $request->messenger_link;
        $data['youtube'] = $request->youtube;
        $data['instagram'] = $request->instagram;
        $data['twitter'] = $request->twitter;
        $data['linkden'] = $request->linkden ?? $request->linkedin ?? null;
        $data['count1'] = $request->count1 ?? 0;
        $data['count2'] = $request->count2 ?? 0;
        $data['count3'] = $request->count3 ?? 0;
        $data['count4'] = $request->count4 ?? 0;
        $data['privacy_policy'] = $request->editor1;
        $data['mission_and_vission'] = $request->editor2;
        $data['trams_and_condition'] = $request->editor3;
        $data['footer_des'] = $request->footer_des;
        $data['footer_credit'] = $request->footer_credit;
        $data['updated_at'] = Carbon::now();

        if ($logo) {
            $name_gen = hexdec(uniqid()) . "." . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $name_gen);
            $last_img = 'images/' . $name_gen;

            if (!empty($old_image) && file_exists(public_path($old_image)) && !str_contains($old_image, 'logo/logo.png')) {
                @unlink(public_path($old_image));
            }

            $data['logo'] = $last_img;
        }

        DB::table('company_settings')->where('id', $id)->update($data);
        return Redirect()->route('admin_setting')->with('status', 'Company Setting Update Successfully');
    }
}
