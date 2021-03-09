<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use ATehnix\VkClient\Auth;
use ATehnix\VkClient\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        $auth = new Auth('6803875', 'l1rMo05qkLGM8BSh5KbQ', 'http://autodon.herokuapp.com/admin', 'market');
//
//        $token = null;
//
//        if ($request->has("code")) {
//            $token = $auth->getToken($request->get('code'));
//
//            $api = new Client;
//            $api->setDefaultToken($token);
//
//            $response = $api->request('market.getAlbums', [
//                'owner_id' => -142695628,
//                'count' => 50
//            ]);
//
//
//            Product::truncate();
//            //работает
//            foreach ($response["response"]["items"] as $item) {
//                //echo $item["id"].$item["title"]." ".$item["photo"]["photo_807"]."<br>";
//
//                $response2 = $api->request('market.get', [
//                    'owner_id' => -142695628,
//                    'album_id' => $item["id"],
//                    'count' => 200
//                ]);
//
//                foreach ($response2["response"]["items"] as $item2) {
//                    //echo $item2["description"]." ".$item2["price"]["text"]." ".$item2["thumb_photo"]." ".$item2["title"]."<br>";
//
//
//                    preg_match_all('|\d+|', $item2["description"], $matches);
//
////                    $count = $matches[0][0] ?? 0;
////                    $weight = $matches[0][1] ?? 0;
//
//                    preg_match_all('|\d+|', $item2["price"]["text"], $matches);
//
//                    $price = $matches[0][0] ?? 0;
//
//                    Product::create([
//                        'title' => $item2["title"],
//                        'description' => $item2["description"],
//                        'category' => $item["title"],
//
//                        'price' => $price,
//
////                        'brand'=>$request->get('brand')??'',
////                        'quantity'=>$request->get('quantity')??0,
////                        'manufacturer_number'=>$request->get('manufacturer_number')??'',
////                        'units'=>$request->get('units')??'',
////                        'original_number'=>$request->get('original_number')??'',
////                        'min_in_pack'=>$request->get('min_in_pack')??'',
////
//                        'img' => $item2["thumb_photo"],
//                        'site_url' => '',
//                        'is_active' => true
//                    ]);
//                }
//
//
//                sleep(2);
//
//            }
//            //dd($response["items"]);
//
//        }
//
//        return view('home', compact("auth", "token"));
        return view('admin.index');
    }

    public function searchAjax(Request $request)
    {
        $vowels = array("(", ")", "-", " ");
        $tmp_phone = $request->get("query");
        $tmp_phone = str_replace($vowels, "", $tmp_phone);
        return User::where('phone', 'like', '%' . $tmp_phone . '%')->get();
    }

    public function search(Request $request)
    {
        $vowels = array("(", ")", "-", " ");
        $tmp_phone = $request->get("phone");
        $tmp_phone = str_replace($vowels, "", $tmp_phone);
        $user = User::where("phone", $tmp_phone)->first();
        if ($user)
            return redirect()
                ->route("users.show", $user->id);
        return back()
            ->with("success", "Пользователь не найден!");
    }
}
