<?php

namespace App\Http\Controllers\Api;

use App\Models\KeyWord;
use Illuminate\Http\Request;

class SearchController extends ApiBaseController
{
    public function words(Request $request){
        try {
            $data = $request->all();
            if(empty($data['words'])){
                return $this->error('缺少关键词');
            }
            $words = KeyWord::where('words', $data['words'])->first();
            if(empty($words)){
                $words = KeyWord::create($data);
            }else{
                $words['nums'] += 1;
                $words->save();
            }
            return $this->success([], '操作成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
