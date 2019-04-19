<?php
namespace App\Http\Transformers;

use App\Models\Comment;
use Dingo\Api\Http\Request;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{

 	public function transform(Comment $comment)
    {
        return $comment->attributesToArray();
    }

}