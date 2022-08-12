<?php

namespace App\Http\Controllers\Api;

use App\Models\Font;
use App\Models\Template;
use App\Models\UserFont;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * 模板控制器
 */
class TemplateController extends ApiBaseController
{
    public function lists(){}

    /**
     * 模板添加
     *
     * @return mixed
     */
    public function add(Request $request){
        try{
            $data = $request->all();
            if (empty($data)) {
                return $this->error('缺少参数');
            }
            if(empty($data['user_id'])) {
                return $this->error('缺少用户 id');
            }
            if (empty($data['image_id'])) {
                return $this->error('缺少素材 id');
            }
            $record = $this->_operation($data, 'add');
            return $this->success($record);
        }
        catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function update(){}

    public function delete(){}

    private function _operation(&$data, $do = 'add'){
        $md5 = md5($data['content']);
        if('add' == $do){
            $font = Font::where('md5', $md5)->first();
            if(empty($font)){
                $fontData = [
                    'md5'     => $md5,
                    'content' => $data['content'],
                ];
                $font = Font::create($fontData);
            }
            $userFontData = [
                'user_id' => $data['user_id'],
                'fonts_id' => $font->id,
                'md5' => $font->md5,
                'content' => $data['content'],
            ];
            $userFont = UserFont::where(Arr::except($userFontData, ['content']))->first();
            if (empty($userFont)) {
                $userFont = UserFont::create($userFontData);
            }
            $data['user_font_id'] = $userFont->id;
            $template = Template::where([
                'user_id' => $data['user_id'],
                'image_id' => $data['image_id'],
                'user_font_id' => $data['user_font_id'],
            ])->first();
            if(empty($template)) {
                $template = Template::create($data);
            }
            return $template;
        }
    }
}
