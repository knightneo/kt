<?php

namespace App\Models;

class Article extends BaseModel
{
    protected $table = 'articles';

    protected $dateFormat = 'U';

    public function createArticle($params)
    {
        $this->user_id = $params['user_id'];
        $this->title = $params['title'];
        $this->column_id = $params['column_id'];
        $this->content = $params['content'];

        try {
            $this->save();
        } catch (Exception $e) {

        }
        return $this->id;
    }

    public function updateArticle($id, $params)
    {
        try {
            $this->where('id', $id)->update($params);
        } catch (Exception $e) {
           return false;
        }
        return true;
    }

    public function getArticleByPageSize($page, $size = 4)
    {
        $offset = ($page - 1) * $size;

        $query = $this->selectRaw('title, user_id, left(content, 20) as content, created_at')
            ->with('user')
            ->where('is_deleted', 0)
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($size);

        $result = $query->get();

        foreach ($result as &$item) {
            if (!isset($item['user'])) {
                unset($item);
                break;
            }

            $item['author'] = $item['user']['name'];
            $item['user_id'] = $item['user']['id'];
            unset($item['user']);
        }

        return $result ? $result->toArray() : [];
    }

    public function getArticleListByUserIdAndPageSize($user_id, $page, $size = 4)
    {
        $offset = ($page - 1) * $size;

        $query = $this->selectRaw('title, user_id, left(content, 20) as content, created_at, is_published')
            ->with('user')
            ->where('is_deleted', 0)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($size);

        $result = $query->get();

        foreach ($result as &$item) {
            if (!isset($item['user'])) {
                unset($item);
                break;
            }

            $item['author'] = $item['user']['name'];
            $item['user_id'] = $item['user']['id'];
            unset($item['user']);
        }

        return $result ? $result->toArray() : [];
    }

    public function getArticleDetail($id)
    {
        $article = $this->where('id', $id)->first();
        return $article ? $article->toArray() : [];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
