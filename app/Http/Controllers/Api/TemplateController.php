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
     * 获取某一条记录
     *
     * @param integer $id
     * @return void
     */
    public function find($id){
        try {
            $template = Template::find($id);
            if(empty($template)){
                return $this->error('记录不存在或已删除');
            }
            return $this->success($template);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

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

    /**
     * 模板修改
     *
     * @return mixed
     */
    public function update(Request $request){
        try{
            $params = $request->all();
            if(empty($params['id']) || empty($params['user_id'])){
                return $this->error('缺少参数');
            }
            $template = Template::where('id', $params['id'])->first();
            if(empty($template)) {
                return $this->error('模板不存在');
            }
            if($template['user_id'] != $params['user_id']){
                return $this->error('操作非法');
            }
            // $params['content'] = str_replace(PHP_EOL, '', $params['content']);
            Template::where('id', $params['id'])->update($params);
            return $this->success([], '模板修改成功');
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    /**
     * 模板删除
     *
     * @param integer $id 模板 id
     * @param integer $userId 用户id
     * @return void
     */
    public function delete($id, $userId){
        try{
            if(intval($id) == 0 || intval($userId) == 0){
                return $this->error('参数错误');
            }
            $template = Template::find($id);
            if(empty($template)){
                return $this->error('模板不存在或已删除');
            }
            if($template['user_id'] != $userId){
                return $this->error('非法操作');
            }
            return $this->success('删除成功');
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

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
