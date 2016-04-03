<?php

namespace App\Models;

class Article extends Model
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

    public function getArticleList()
    {

    }

    public function getArticleDetail($id)
    {
        $article = $this->where('id', $id)->first();
        return $article ? $article->toArray(); [];
    }
}
