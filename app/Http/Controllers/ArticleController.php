<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Article;

class ArticleController extends Controller
{
    

    public function createArticle()
    {
        $articleDao = new Article;
        $user = Auth::user();
        $request = $this->request->toArray();
        $params = [
            'title' => $request['title'],
            'content' => $request['content'],
            'user_id' => $user->id,
            'column_id' => 0,
        ];
        $article_id = $articleDao->createArticle($params);
        if ($article_id) {
            return ['result' => true, 'article_id' => $article_id];
        }
        return ['result' => false];
    }

    public function updateArticle($article_id)
    {
        $articleDao = new Article;
        $user_id = $articleDao->select('user_id')->where('id', $article_id)->first()['user_id'];
        $user = Auth::user();
        if ($user_id != $user->id) {
            return ['result' => false, 'error_message' => 'wrong user'];
        }
        $request = $this->request->toArray();
        $params = [];
        if (isset($request['title'])) {
            $params['title'] = $request['title'];
        }
        if (isset($request['content'])) {
            $params['content'] = $request['content'];
        }
        $result = $articleDao->updateArticle($article_id, $params);
        return ['result' => $result];
    }

    public function getArticleList($page)
    {
        $articleDao = new Article;
        $list = $articleDao->getArticleByPageSize($page);
        return $list;
    }

    public function getUserArticleList($page)
    {
        $articleDao = new Article;
        $user = Auth::user();
        $list = $articleDao->getArticleListByUserIdAndPageSize($user->id, $page);
        return $list;
    }

    public function getArticleDetail($article_id)
    {
        $articleDao = new Article;
        $article = $articleDao->getArticleDetail($article_id);
        return $article;
    }

    public function publish($article_id)
    {
        $articleDao = new Article;
        $user_id = $articleDao->select('user_id')->where('id', $article_id)->first()['user_id'];
        $user = Auth::user();
        if ($user_id != $user->id) {
            return ['result' => false, 'error_message' => 'wrong user'];
        }
        $params = ['is_published' => 1];
        $result = $articleDao->updateArticle($article_id, $params);
        return ['result' => $result];
    }

    public function unpublish($article_id)
    {
        $articleDao = new Article;
        $user_id = $articleDao->select('user_id')->where('id', $article_id)->first()['user_id'];
        $user = Auth::user();
        if ($user_id != $user->id) {
            return ['result' => false, 'error_message' => 'wrong user'];
        }
        $params = ['is_published' => 0];
        $result = $articleDao->updateArticle($article_id, $params);
        return ['result' => $result];
    }

    public function delete($article_id)
    {
        $articleDao = new Article;
        $user_id = $articleDao->select('user_id')->where('id', $article_id)->first()['user_id'];
        $user = Auth::user();
        if ($user_id != $user->id) {
            return ['result' => false, 'error_message' => 'wrong user'];
        }
        $params = ['is_deleted' => 1];
        $result = $articleDao->updateArticle($article_id, $params);
        return ['result' => $result];
    }
}
