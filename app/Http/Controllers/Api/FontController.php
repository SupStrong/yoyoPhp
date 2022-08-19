<?php

namespace App\Http\Controllers\Api;
use App\Models\UserFont;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Font;

class FontController extends ApiBaseController
{
    public function find($id)
    {
        try {
            $userFont = UserFont::find($id);
            if (empty($userFont)) {
                return $this->error('记录不存在或已删除');
            }
            return $this->success($userFont);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 字体添加
     *
     * @return mixed
     */
    public function add(Request $request)
    {
        try {
            $data = $request->all();
            if (empty($data)) {
                return $this->error('缺少参数');
            }
            if (empty($data['user_id'])) {
                return $this->error('缺少用户 id');
            }
            $record = $this->_operation($data, 'add');
            return $this->success($record);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 字体修改
     *
     * @return mixed
     */
    public function update(Request $request)
    {
        try {
            $params = $request->all();
            if (empty($params['id']) || empty($params['user_id'])) {
                return $this->error('缺少参数');
            }
            $template = UserFont::where('id', $params['id'])->first();
            if (empty($template)) {
                return $this->error('字体不存在');
            }
            if ($template['user_id'] != $params['user_id']) {
                return $this->error('操作非法');
            }
            UserFont::where('id', $params['id'])->update($params);
            return $this->success([], '字体修改成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 字体删除
     *
     * @param integer $id 字体 id
     * @param integer $userId 用户id
     * @return void
     */
    public function delete($id, $userId)
    {
        try {
            if (intval($id) == 0 || intval($userId) == 0) {
                return $this->error('参数错误');
            }
            $userFont = UserFont::find($id);
            if (empty($userFont)) {
                return $this->error('字体不存在或已删除');
            }
            if ($userFont['user_id'] != $userId) {
                return $this->error('非法操作');
            }
            return $this->success('删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    private function _operation(&$data, $do = 'add')
    {
        $md5 = md5($data['content']);
        if ('add' == $do) {
            $font = Font::where('md5', $md5)->first();
            if (empty($font)) {
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
            return $userFont;
        }
    }
}
