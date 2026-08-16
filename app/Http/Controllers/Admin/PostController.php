<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Concerns\HandlesBulkDestroy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Modul contoh — cetakan untuk modul berikutnya.
 *
 * Berbeda dengan modul admin lain yang dijaga middleware di route, modul ini
 * berotorisasi lewat PostPolicy untuk menunjukkan cara kedua memakai resource
 * key yang sama persis.
 */
class PostController extends Controller
{
    use HandlesBulkDestroy;

    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $table = TableBuilder::for(Post::query()->with('author'))
            ->searchable(['title', 'excerpt', 'author.name'])
            ->sortable(['title', 'status', 'published_at', 'created_at'], default: 'created_at', direction: 'desc')
            ->filter('status', fn (Builder $query, string $value) => $query->where('status', $value));

        return view('admin.posts.index', [
            'posts' => $table->paginate(),
            'table' => $table,
            'statuses' => PostStatus::options(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.create', ['statuses' => PostStatus::options()]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $post = Post::create($this->payload($request->validated()));

        return redirect()->route('admin.posts.index')->with('success', "Artikel “{$post->title}” dibuat.");
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('admin.posts.edit', ['post' => $post, 'statuses' => PostStatus::options()]);
    }

    /** Fragmen panel detail yang diambil drawer saat baris tabel diklik. */
    public function panel(Post $post): View
    {
        $this->authorize('view', $post);

        return view('admin.posts.panel', ['post' => $post->load('author')]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $post->update($this->payload($request->validated(), $post));

        return redirect()->route('admin.posts.index')->with('success', "Artikel “{$post->title}” diperbarui.");
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete', Post::class);

        return $this->destroyMany($request, Post::class, 'admin.posts.index');
    }

    public function export(): StreamedResponse
    {
        $this->authorize('export', Post::class);

        $table = TableBuilder::for(Post::query()->with('author'))
            ->searchable(['title', 'excerpt'])
            ->sortable(['title', 'created_at'], default: 'created_at', direction: 'desc');

        return $table->download(fn (Post $post): array => [
            'Judul' => $post->title,
            'Penulis' => $post->author?->name,
            'Status' => $post->status->label(),
            'Terbit' => $post->published_at?->format('Y-m-d H:i'),
        ], 'artikel-'.now()->format('Ymd-His').'.csv');
    }

    /** @param  array<string, mixed>  $data */
    private function payload(array $data, ?Post $post = null): array
    {
        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = $post?->user_id ?? auth()->id();

        // Tanggal terbit diisi otomatis saat pertama kali berstatus terbit.
        if ($data['status'] === PostStatus::Published->value && $post?->published_at === null) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
