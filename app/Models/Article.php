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

        $query = $this->selectRaw('id, title, user_id, left(content, 20) as content, created_at')
            ->with('user')
            ->where('is_deleted', 0)
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc');

        $count = $query->count();
        $data = $query->skip($offset)->take($size)->get();

        foreach ($data as &$item) {
            if (!isset($item['user'])) {
                unset($item);
                break;
            }

            $item['username'] = $item['user']['name'];
            $item['user_id'] = $item['user']['id'];
            unset($item['user']);
        }

        $result = [];
        $result['list'] = $data ? $data->toArray() : [];
        $result['number'] = ceil($count/$size);
        if ($result['number'] == 0) {
            $result['found0'] = true;
        }
        return $result;
    }

    public function getArticleListByUserIdAndPageSize($user_id, $page, $size = 4)
    {
        $offset = ($page - 1) * $size;

        $query = $this->selectRaw('id, title, user_id, left(content, 20) as content, created_at, is_published')
            ->with('user')
            ->where('is_deleted', 0)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc');

        $count = $query->count();
        $data = $query->skip($offset)->take($size)->get();

        foreach ($data as &$item) {
            if (!isset($item['user'])) {
                unset($item);
                break;
            }

            $item['username'] = $item['user']['name'];
            $item['user_id'] = $item['user']['id'];
            unset($item['user']);
        }

        $result = [];
        $result['list'] = $data ? $data->toArray() : [];
        $result['number'] = ceil($count/$size);
        return $result;
    }

    public function getArticleDetail($id)
    {
        $article = $this->where('id', $id)->with('user')->first();
        if (!$article) {
            return [];
        }
        $article = $article->toArray();
        $article['username'] = $article['user']['name'];
        unset($article['user']);
        return $article;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
