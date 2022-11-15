<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCommentResource;
use App\Models\Comment;
use App\Models\CommentSentiment;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectFileRequest;
use App\Repositories\ProjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $projectRepository;

    public function __construct(ProjectRepository $projects)
    {
        $this->projectRepository = $projects;
    }

    final public function index(Request $request)
    {
        $projects = $request->user()->projects()->searchable();
        return $this->respondWithSuccess($projects);
    }

    final public function getMyProjects(Request $request)
    {
        $projects = $request->user()->projects()->searchable();
        return $this->respondWithSuccess($projects);
    }

    final public function featured()
    {
        $projects = $this->projectRepository->featuredProjects();
        return $this->respondWithSuccess($projects);
    }

    final public function create(ProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $project = getUser()->projects()->create($data);
        // dd($this->projectRepository->parse($project));
        return $this->respondWithSuccess(['data' => [
            'project' => $this->projectRepository->parse($project),
            "message" => "Successfully created " . $project->name]
        ], 201);
    }

    public function processFile(Request $request, Project $project)
    {

    }

    public function show(Project $project)
    {
        return $this->respondWithSuccess($this->projectRepository->parse($project));
    }

    final public function update(Request $request, Project $project)
    {
        $project->update($request->all());
        return $this->respondWithSuccess(['data' => [
            'project' => $this->projectRepository->parse($project),
            "message" => "Successfully update " . $project->name]], 201);
    }

    final public function destroy(Project $project)
    {
        $project->delete();
        return $this->respondWithSuccess('Deleted Successfully', 201);
    }


    /**
     * COMMENT SECTION
     * @param Project $project
     * @return JsonResponse|mixed
     */
    final public function comments(Project $project)
    {
        $comments = $project->comments()->searchable();
        $data = ProjectCommentResource::collection($comments->items());
        return $this->respondWithSuccess(array_merge($comments->toArray(), ['data' => $data]));
    }

    final public function addComment(Request $request, Project $project)
    {
        $comen = $project->comments()->create(['comment' => $request->comment]);
        return $this->comments($project);
    }

    final public function editComment(Request $request, Comment $comment)
    {
        $comment->update(['comment' => $request->comment]);
        return $this->comments($comment->project);
    }

    final public function addReply(Request $request, Comment $comment)
    {
        $comment->replies()->create(['comment' => $request->comment, 'project_id' => $comment->project_id]);
        return $this->comments($comment->project);
    }

    final public function getReplies(Comment $comment)
    {
        $comments = $comment->replies()->searchable();
        $data = ProjectCommentResource::collection($comments->items());
        return $this->respondWithSuccess(array_merge($comments->toArray(), ['data' => $data]));
    }

    final public function likeComment(Comment $comment)
    {
        //Delete if exist
        $setiment = $comment->sentiments()->where(['user_id' => auth()->id()])->first();
        if ($setiment && $setiment->isLiked) {
            $setiment->delete();
            return $this->respondWithSuccess('delete');
        }
        $comment->sentiments()->updateOrCreate(['user_id' => auth()->id()], ['sentiment' => 'liked']);
        return $this->respondWithSuccess('liked');
    }

    final public function dislikeComment(Comment $comment)
    {
        $setiment = $comment->sentiments()->where(['user_id' => auth()->id()])->first();
        if ($setiment && $setiment->isDisliked) {
            $setiment->delete();
            return $this->respondWithSuccess('delete');
        }
        $comment->sentiments()->updateOrCreate(['user_id' => auth()->id()], ['sentiment' => 'disliked']);
        return $this->respondWithSuccess('disliked');
    }

    final public function deleteComment(Comment $comment)
    {
        $comment->replies()->delete();
        $comment->delete();
        return $this->respondWithSuccess('Deleted Successfully', 201);
    }
    /**
     * END COMMENT SECTION
     *
     */

}
