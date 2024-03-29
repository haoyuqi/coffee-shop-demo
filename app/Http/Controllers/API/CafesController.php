<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCafeRequest;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CafesController extends Controller
{
    public function getCafes()
    {
        $cafes = Cafe::with('brewMethods')
            ->with(['tags' => function ($query) {
                $query->select('name');
            }])
            ->get();

        return response()->json($cafes);
    }

    public function getCafe($id)
    {
        $cafe = Cafe::where('id', $id)
            ->with('brewMethods')
            ->with('userLike')
            ->with('tags')
            ->first();

        return response()->json($cafe);

    }

    public function postNewCafe(StoreCafeRequest $request)
    {
        // 已添加的咖啡店
        $addedCafes = [];
        // 所有位置信息
        $locations = json_decode($request->input('locations'), true);

        // 父节点（可理解为总店）
        $parentCafe = new Cafe();

        // 咖啡店名称
        $parentCafe->name = $request->input('name');
        // 分店位置名称
        $parentCafe->location_name = $locations[0]['name'] ?: '';
        // 分店地址
        $parentCafe->address = $locations[0]['address'];
        // 所在城市
        $parentCafe->city = $locations[0]['city'];
        // 所在省份
        $parentCafe->state = $locations[0]['state'];
        // 邮政编码
        $parentCafe->zip = $locations[0]['zip'];
        $coordinates = GaodeMaps::geocodeAddress($parentCafe->address, $parentCafe->city, $parentCafe->state);
        // 纬度
        $parentCafe->latitude = $coordinates['lat'];
        // 经度
        $parentCafe->longitude = $coordinates['lng'];
        // 咖啡烘焙师
        $parentCafe->roaster = $request->input('roaster') ? 1 : 0;
        // 咖啡店网址
        $parentCafe->website = $request->input('website');
        // 描述信息
        $parentCafe->description = $request->input('description') ?: '';
        // 添加者
        $parentCafe->added_by = $request->user()->id;
        $parentCafe->save();

        $photo = $request->file('picture');
        if ($photo && $photo->isValid()) {
            $disk = Storage::disk('photos');

            if (!$disk->exists($parentCafe->id)) {
                $disk->makeDirectory($parentCafe->id);
            }

            $filename = time() . '-' . $photo->getClientOriginalName();
            $filePath = $disk->putFileAs($parentCafe->id, $photo, $filename);
            if ($filePath) {
                $cafePhoto = new CafePhoto();

                $cafePhoto->cafe_id = $parentCafe->id;
                $cafePhoto->uploaded_by = $request->user()->id;
                $cafePhoto->file_url = $filePath;

                $cafePhoto->save();
            }
        }

        // 冲泡方法
        $brewMethods = $locations[0]['methodsAvailable'];
        // 标签信息
        $tags = $locations[0]['tags'];
        // 保存与此咖啡店关联的所有冲泡方法（保存关联关系）
        $parentCafe->brewMethods()->sync($brewMethods);
        // 绑定咖啡店与标签
        Tagger::tagCafe($parentCafe, $tags, $request->user()->id);

        // 将当前咖啡店数据推送到已添加咖啡店数组
        array_push($addedCafes, $parentCafe->toArray());

        // 第一个索引的位置信息已经使用，从第 2 个位置开始
        if (count($locations) > 1) {
            // 从索引值 1 开始，以为第一个位置已经使用了
            for ($i = 1; $i < count($locations); $i++) {
                // 其它分店信息的获取和保存，与总店共用名称、网址、描述、烘焙师等信息，其他逻辑与总店一致
                $cafe = new Cafe();

                $cafe->parent = $parentCafe->id;
                $cafe->name = $request->input('name');
                $cafe->location_name = $locations[$i]['name'] ?: '';
                $cafe->address = $locations[$i]['address'];
                $cafe->city = $locations[$i]['city'];
                $cafe->state = $locations[$i]['state'];
                $cafe->zip = $locations[$i]['zip'];
                $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
                $cafe->latitude = $coordinates['lat'];
                $cafe->longitude = $coordinates['lng'];
                $cafe->roaster = $request->input('roaster') != '' ? 1 : 0;
                $cafe->website = $request->input('website');
                $cafe->description = $request->input('description') ?: '';
                $cafe->added_by = $request->user()->id;
                $cafe->save();

                $cafe->brewMethods()->sync($locations[$i]['methodsAvailable']);
                Tagger::tagCafe($cafe, $locations[$i]['tags'], $request->user()->id);

                array_push($addedCafes, $cafe->toArray());
            }
        }

        return response()->json($addedCafes, 201);
    }

    public function postLikeCafe($cafeID)
    {
        $now = now();
        $cafe = Cafe::where('id', $cafeID)->first();

        $cafe->likes()->attach(Auth::user()->id, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json(['cafe_liked' => true], 201);
    }

    public function deleteLikeCafe($cafeID)
    {
        $cafe = Cafe::where('id', $cafeID)->first();

        $cafe->likes()->detach(Auth::user()->id);

        return response()->json(null, 204);
    }

    public function postAddTags(Request $request, $cafeID)
    {
        $tags = $request->input('tags');
        $cafe = Cafe::find($cafeID);

        Tagger::tagCafe($cafe, $tags, auth()->user()->id);

        $cafe = Cafe::where('id', $cafeID)
            ->with('brewMethods')
            ->with('userLike')
            ->with('tags')
            ->first();

        return response()->json($cafe, 200);
    }

    public function deleteCafeTag($cafeID, $tagID)
    {
        DB::table('cafes_users_tags')
            ->where('cafe_id', $cafeID)
            ->where('tag_id', $tagID)
            ->where('user_id', auth()->user()->id)
            ->delete();

        return response()->json(null, 204);
    }
}
